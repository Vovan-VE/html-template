<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpConcatenation extends BaseListOperation implements FilterBubbleInterface
{
    /** @var string */
    protected $subtype = DataTypes::STR_TEXT;

    public function __construct(?string $subtype, PhpValue ...$values)
    {
        $this->subtype = $subtype;

        $new_values = [];
        foreach ($values as $value) {
            if ($value instanceof self) {
                array_push($new_values, ...$value->getValues());
            } else {
                $new_values[] = $value;
            }
        }

        /** @var PhpValue[] $temp */
        $temp = [];
        /** @var PhpValue|null $last */
        $last = null;
        foreach ($new_values as $value) {
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

        parent::__construct(...$temp);
    }

    /**
     * @return PhpValue|static
     * @since 0.4.0
     */
    public function finalize(): PhpValue
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = $value->finalize();
        }

        return new static($this->subtype, ...$values);
    }

    /**
     * @param BaseFilter $filter
     * @return PhpValue|null
     * @since 0.4.0
     */
    public function bubbleFilter(BaseFilter $filter): ?PhpValue
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

        if (!$result) {
            return "''";
        }

        if (1 === count($result)) {
            return $result[0];
        }
        return '(' . join('.', $result) . ')';
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
