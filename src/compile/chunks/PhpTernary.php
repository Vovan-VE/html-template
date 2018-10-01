<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpTernary implements PhpValueInterface
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
