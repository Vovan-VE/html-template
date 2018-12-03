<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpLogicOr extends BaseLogicOperation implements FilterBubbleInterface
{
    public static function create(PhpValue $first, PhpValue $second): PhpValue
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

    public function __construct(PhpValue $first, PhpValue $second)
    {
        if ($first instanceof self) {
            $values = $first->values;
        } else {
            $values = [$first];
        }

        $values[] = $second;

        parent::__construct(...$values);
    }

    /**
     * @return PhpValue|static
     * @since 0.4.0
     */
    public function finalize(): PhpValue
    {
        $values = $this->values;
        $result = new static(array_shift($values), array_shift($values));
        while ($values) {
            $result = new static($result, array_shift($values));
        }
        return $result;
    }

    /**
     * @param BaseFilter $filter
     * @return PhpValue|null
     * @since 0.4.0
     */
    public function bubbleFilter(BaseFilter $filter): ?PhpValue
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
            return $filter::create($values[0]);
        }

        if (!$filter::willSinkIntoAny(...$values)) {
            return null;
        }

        // f(A || B || C || D)
        // =>
        // ($1 = A) ? f($1) : (
        //     ($2 = B) ? f($2) : (
        //         ($3 = C) ? f($3) : (
        //             D
        //         )
        //     )
        // )

        /** @var PhpValue $last */
        $last = array_pop($values);
        $result = $filter::create($last);
        while ($values) {
            $last = array_pop($values);
            $var = new PhpTempVar;
            $result = new PhpTernary(
                // ($1 = A)
                PhpTempVarAssign::create($var, $last),
                // f($1)
                $filter::create(PhpTempVarRead::create($var)),
                // ...
                $result
            );
        }

        return $result;
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

        /** @var PhpValue $last */
        $last = array_pop($values);
        $code = $last->getPhpCode($scope);
        while ($values) {
            $last = array_pop($values);
            $code = "(({$last->getPhpCode($scope)})?:($code))";
        }

        return $code;
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
