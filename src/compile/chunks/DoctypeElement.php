<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class DoctypeElement implements PhpValueInterface
{
    /** @var PhpValueInterface[] */
    private $values;
    /** @var bool */
    private $isConst;

    public function __construct(PhpValueInterface ...$values)
    {
        $this->values = $values;

        $this->isConst = true;
        foreach ($values as $value) {
            if (!$value->isConstant()) {
                $this->isConst = false;
                break;
            }
        }
    }

    /**
     * @return PhpValueInterface[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_STRING, DataTypes::STR_HTML];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        if (!$this->values) {
            return "''";
        }

        /**
         * @return \Generator|PhpValueInterface[]
         */
        $get_parts = function () {
            yield new PhpStringConst('<!DOCTYPE', DataTypes::STR_HTML);
            foreach ($this->values as $value) {
                yield new PhpStringConst(' ', DataTypes::STR_HTML);
                yield $value;
            }
            yield new PhpStringConst('>', DataTypes::STR_HTML);
        };

        /** @var PhpValueInterface[] $temp */
        $temp = [];
        /** @var PhpValueInterface|null $last */
        $last = null;
        foreach ($get_parts() as $value) {
            if (null !== $last && $last->isConstant() && $value->isConstant()) {
                if ($last->getDataType() !== $value->getDataType()) {
                    throw new \LogicException('Mixed string subtypes concatenation');
                }
                array_pop($temp);
                $last = new PhpStringConst(
                    $last->getConstValue() . $value->getConstValue(),
                    DataTypes::STR_HTML
                );
            } else {
                $last = $value;
            }
            $temp[] = $last;
        }

        $result = [];
        foreach ($temp as $value) {
            if ($value->isConstant() && '' === (string)$value->getConstValue()) {
                continue;
            }
            $code = $value->getPhpCode($scope);
            if (preg_match('/^[.\\d]/', $code)) {
                $code = " $code";
            }
            if (preg_match('/[.\\d]$/D', $code)) {
                $code = "$code ";
            }
            $result[] = $code;
        }

        return '(' . join('.', $result) . ')';
    }

    public function isConstant(): bool
    {
        return $this->isConst;
    }

    public function getConstValue(): string
    {
        if (!$this->isConst) {
            throw new \RuntimeException('Not a constant');
        }

        $result = '<!DOCTYPE';
        foreach ($this->values as $value) {
            $result .= ' ' . $value->getConstValue();
        }
        $result .= '>';
        return $result;
    }
}
