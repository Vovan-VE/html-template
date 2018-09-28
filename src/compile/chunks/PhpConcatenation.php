<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class PhpConcatenation implements PhpValueInterface
{
    /** @var PhpValueInterface[] */
    private $values;
    /** @var bool */
    private $isConst;

    public function __construct(PhpValueInterface ...$values)
    {
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
                $last = new PhpStringConst($last->getConstValue() . $value->getConstValue());
            } else {
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

    public function getPhpCode(): string
    {
        if (!$this->values) {
            return "''";
        }

        $result = [];
        foreach ($this->values as $value) {
            if ($value->isConstant() && '' === (string)$value->getConstValue()) {
                continue;
            }
            $code = $value->getPhpCode();
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
