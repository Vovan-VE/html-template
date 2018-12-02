<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpTernary extends PhpValue implements FilterBubbleInterface
{
    /** @var PhpValue */
    private $cond;
    /** @var PhpValue|null */
    private $then;
    /** @var PhpValue */
    private $else;

    public static function create(PhpValue $cond, ?PhpValue $then, PhpValue $else): PhpValue
    {
        $bool_cond = ToBooleanCast::create($cond);

        if ($bool_cond->isConstant()) {
            // const ? ...
            if ($bool_cond->getConstValue()) {
                // true ? ...
                return $then ?: $bool_cond;
            }
            // false ? ... : ...
            return $else;
        }

        // x ? A : A
        // =>
        // A
        if (
            null !== $then
            && $then->isConstant()
            && $else->isConstant()
            && $then->getConstValue() === $else->getConstValue()
            && $then->getDataType() === $else->getDataType()
        ) {
            return $then;
        }

        if ($bool_cond instanceof ToBooleanCast) {
            $bool_cond = $bool_cond->getValue();
        }

        return new static($bool_cond, $then, $else);
    }

    public function __construct(PhpValue $cond, ?PhpValue $then, PhpValue $else)
    {
        parent::__construct();
        $this->cond = $cond;
        $this->then = $then;
        $this->else = $else;
    }

    /**
     * @param BaseFilter $filter
     * @return PhpValue|null
     * @since 0.4.0
     */
    public function bubbleFilter(BaseFilter $filter): ?PhpValue
    {
        $then = $this->then ?: $this->cond;
        $else = $this->else;

        // f(A ? B : C)
        // =>
        // A ? f(B) : f(C)

        if ($filter::willSinkInto($then) || $filter::willSinkInto($else)) {
            return new static($this->cond, $filter::create($then), $filter::create($else));
        }

        return null;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        $a = ($this->then ?: $this->cond)->getDataType();
        $b = $this->else->getDataType();

        if (!$a || !$b || $a[0] !== $b[0]) {
            return [];
        }

        if ($a[1] !== $b[1]) {
            return [$a[0]];
        }

        return $a;
    }

    public function getPhpCode(CompileScope $scope): string
    {
        if ($this->cond->isConstant()) {
            // const ? ...
            if ($this->cond->getConstValue()) {
                // true ? ...
                return ($this->then ?: $this->cond)->getPhpCode($scope);
            }
            // false ? ... : ...
            return $this->else->getPhpCode($scope);
        }

        // ... ? ... : ...
        $cond_code = $this->cond->getPhpCode($scope);
        $else_code = $this->else->getPhpCode($scope);

        if ($this->then) {
            return "(({$cond_code})?({$this->then->getPhpCode($scope)}):({$else_code}))";
        }
        return "(({$cond_code})?:({$else_code}))";
    }

    public function isConstant(): bool
    {
        // true ? const : ...
        // false ? ... : const
        return $this->cond->isConstant()
            && ($this->cond->getConstValue()
                ? !$this->then || $this->then->isConstant()
                : $this->else->isConstant()
            );
    }

    public function getConstValue()
    {
        return $this->cond->getConstValue()
            ? ($this->then ?: $this->cond)->getConstValue()
            : $this->else->getConstValue();
    }
}
