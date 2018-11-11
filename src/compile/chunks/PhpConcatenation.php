<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpConcatenation implements PhpValueInterface, FilterBubbleInterface
{
    /** @var string */
    private $subtype = DataTypes::STR_TEXT;
    /** @var PhpValueInterface[] */
    private $values;
    /** @var bool */
    private $isConst;

    public function __construct(?string $subtype, PhpValueInterface ...$values)
    {
        $this->subtype = $subtype;

        /** @var PhpValueInterface[] $temp */
        $temp = [];
        $is_const = true;
        /** @var PhpValueInterface|null $last */
        $last = null;
        foreach ($values as $value) {
            if ($is_const && !$value->isConstant()) {
                $is_const = false;
            }

            if (null !== $last && $last->isConstant() && $value->isConstant()) {
                array_pop($temp);
                $last = new PhpStringConst(
                    $last->getConstValue() . $value->getConstValue(),
                    $this->subtype
                );
            } else {
                if ($value->getDataType() !== [DataTypes::T_STRING, $this->subtype]) {
                    throw new \LogicException('Unexpected item data type');
                }

                $last = $value;
            }
            $temp[] = $last;
        }

        $this->values = $temp;
        $this->isConst = $is_const;
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
    public function bubbleFilter(BaseFilter $filter): ?PhpValueInterface
    {
        if (DataTypes::T_STRING !== ($filter->getDataType()[0] ?? null)) {
            return null;
        }

        $new_values = [];
        foreach ($this->values as $value) {
            $new_values[] = $filter::create($value);
        }

        return new static($filter->getDataType()[1] ?? null, ...$new_values);
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [DataTypes::T_STRING, $this->subtype];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        if (!$this->values) {
            return "''";
        }

        $result = [];
        foreach ($this->values as $value) {
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

        if (1 === count($result)) {
            return $result[0];
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

        $result = '';
        foreach ($this->values as $value) {
            $result .= $value->getConstValue();
        }
        return $result;
    }
}
