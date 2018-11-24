<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpLogicAnd implements PhpValueInterface, FilterBubbleInterface
{
    /** @var PhpValueInterface[] */
    private $values;
    /** @var bool */
    private $isConst;

    public static function create(PhpValueInterface $first, PhpValueInterface $second): PhpValueInterface
    {
        if ($first->isConstant()) {
            // const && ...
            if ($first->getConstValue()) {
                // true && ...
                return $second;
            }
            // false && ...
            return $first;
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

    /**
     * @param BaseFilter $filter
     * @return PhpValueInterface|null
     * @since 0.4.0
     */
    public function bubbleFilter(BaseFilter $filter): ?PhpValueInterface
    {
        $values = $this->values;
        while (count($values) > 1 && $values[0]->isConstant()) {
            // const && ...
            if ($values[0]->getConstValue()) {
                // true && ... => ...
                array_shift($values);
                continue;
            }
            // false && ... => false
            return $filter::create($values[0]);
        }

        if (!$filter::willSinkIntoAny(...$values)) {
            return null;
        }

        // f(A && B && C && D)
        // =>
        // !($1 = A) ? f($1) : (
        //     !($2 = B) ? f($2) : (
        //         !($3 = C) ? f($3) : (
        //             f(D)
        //         )
        //     )
        // )

        /** @var PhpValueInterface $last */
        $last = array_pop($values);
        $result = $filter::create($last);
        while ($values) {
            $last = array_pop($values);
            $var = new PhpTempVar;
            $result = new PhpTernary(
                // !($1 = A)
                PhpNot::create(PhpTempVarAssign::create($var, $last)),
                // f($1)
                $filter::create(PhpTempVarRead::create($var)),
                // ...
                $result
            );
        }

        return $result;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        $type = null;
        foreach ($this->values as $value) {
            $t = $value->getDataType();
            if (!$t) {
                // untyped
                return [];
            }

            if (null === $type) {
                $type = $t;
            } else {
                if ($type[0] !== $t[0]) {
                    // different type
                    return [];
                }
                // same type
                if (($type[1] ?? null) !== ($t[1] ?? null)) {
                    // different subtype
                    return [$type[0]];
                }
                // same subtype
                // continue
            }
        }

        return $type ?? [];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $values = $this->values;
        while (count($values) > 1 && $values[0]->isConstant()) {
            // const && ...
            if ($values[0]->getConstValue()) {
                // true && ... => ...
                array_shift($values);
                continue;
            }
            // false && ... => false
            return $values[0]->getPhpCode($scope);
        }

        // ... && ...
        // A && B && C && D
        // =>
        // !($1 = A) ? $1 : (
        //     !($2 = B) ? $2 : (
        //         !($3 = C) ? $3 : (
        //             D
        //         )
        //     )
        // )

        /** @var PhpValueInterface $last */
        $last = array_pop($values);
        $code = $last->getPhpCode($scope);
        while ($values) {
            $last = array_pop($values);
            $var = $scope->newTempVar();
            $code = "(!($var={$last->getPhpCode($scope)})?$var:($code))";
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
            if (!$v) {
                return $v;
            }
        }
        // sure, $value is set since count >= 2
        return $value->getConstValue();
    }
}
