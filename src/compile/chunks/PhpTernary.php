<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpTernary implements PhpValueInterface, FilterBubbleInterface
{
    /** @var PhpValueInterface */
    private $cond;
    /** @var PhpValueInterface */
    private $then;
    /** @var PhpValueInterface */
    private $else;

    public static function create(PhpValueInterface $cond, PhpValueInterface $then, PhpValueInterface $else): PhpValueInterface
    {
        if ($cond->isConstant()) {
            // const ? ...
            if ($cond->getConstValue()) {
                // true ? ...
                return $then;
            }
            // false ? ... : ...
            return $else;
        }
        return new self($cond, $then, $else);
    }

    public function __construct(PhpValueInterface $cond, PhpValueInterface $then, PhpValueInterface $else)
    {
        $this->cond = $cond;
        $this->then = $then;
        $this->else = $else;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function bubbleFilter(BaseFilter $filter): ?PhpValueInterface
    {
        $then = $this->then;
        $else = $this->else;

        if (!$then->isConstant() && !$else->isConstant()) {
            return null;
        }

        return new static($this->cond, $filter::create($then), $filter::create($else));
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        $a = $this->then->getDataType();
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
                return $this->then->getPhpCode($scope);
            }
            // false ? ... : ...
            return $this->else->getPhpCode($scope);
        }
        // ... ? ... : ...
        return "(({$this->cond->getPhpCode($scope)})?({$this->then->getPhpCode($scope)}):({$this->else->getPhpCode($scope)}))";
    }

    public function isConstant(): bool
    {
        // true ? const : ...
        // false ? ... : const
        return $this->cond->isConstant()
            && ($this->cond->getConstValue()
                ? $this->then->isConstant()
                : $this->else->isConstant()
            );
    }

    public function getConstValue()
    {
        return $this->cond->getConstValue()
            ? $this->then->getConstValue()
            : $this->else->getConstValue();
    }
}
