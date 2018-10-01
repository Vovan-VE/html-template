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

    public function getPhpCode(CompileScope $scope): string
    {
        if (!$this->values) {
            return "''";
        }

        $get_parts = function () {
            yield new PhpStringConst('<!DOCTYPE');
            foreach ($this->values as $value) {
                yield new PhpStringConst(' ');
                yield $value;
            }
            yield new PhpStringConst('>');
        };

        /** @var PhpValueInterface[] $temp */
        $temp = [];
        /** @var PhpValueInterface|null $last */
        $last = null;
        foreach ($get_parts() as $value) {
            if (null !== $last && $last->isConstant() && $value->isConstant()) {
                array_pop($temp);
                $last = new PhpStringConst($last->getConstValue() . $value->getConstValue());
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
