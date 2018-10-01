<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpLogicOr implements PhpValueInterface
{
    /** @var PhpValueInterface[] */
    private $values;
    /** @var bool */
    private $isConst;

    public static function create(PhpValueInterface $first, PhpValueInterface $second): PhpValueInterface
    {
        if ($first->isConstant()) {
            // const || ...
            if ($first->getConstValue()) {
                // true || ...
                return $first;
            }
            // false || ...
            return $second;
        }
        return new self($first, $second);
    }

    public function __construct(PhpValueInterface $first, PhpValueInterface $second)
    {
        if ($first instanceof self) {
            $this->values = $first->values;
        } else {
            $this->values = [$first];
        }

        $this->values[] = $second;

        $this->isConst = $first->isConstant() && $second->isConstant();
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $values = $this->values;
        while (count($values) > 1 && $values[0]->isConstant()) {
            // const || ...
            if (!$values[0]->getConstValue()) {
                // false || ... => ...
                array_shift($values);
                continue;
            }
            // true || ... => true
            return $values[0]->getPhpCode($scope);
        }

        // ... || ...
        // A || B || C || D
        // =>
        // A ?: (
        //     B ?: (
        //         C ?: (
        //             D
        //         )
        //     )
        // )

        /** @var PhpValueInterface $last */
        $last = array_pop($values);
        $code = $last->getPhpCode($scope);
        while ($values) {
            $last = array_pop($values);
            $code = "(({$last->getPhpCode($scope)})?:($code))";
        }

        return $code;
    }

    public function isConstant(): bool
    {
        return $this->isConst;
    }

    public function getConstValue()
    {
        $value = null;
        foreach ($this->values as $value) {
            $v = $value->getConstValue();
            if ($v) {
                return $v;
            }
        }
        // sure, $value is set since count >= 2
        return $value->getConstValue();
    }
}
