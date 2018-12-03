<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

/**
 * @since 0.4.0
 */
class PhpTempVar
{
    /** @var PhpValue|null */
    private $value;
    /** @var string */
    private $var;
    /** @var int */
    private $readsCount = 0;
    /** @var self|null */
    private $finalized;

    public function finalize(): self
    {
        if (null !== $this->finalized) {
            return $this->finalized;
        }

        $new = new self;
        $new->setValue($this->getValue()->finalize());
        return $this->finalized = $new;
    }

    public function setValue(PhpValue $value): void
    {
        $this->value = $value;
    }

    public function getValue(): ?PhpValue
    {
        return $this->value;
    }

    public function setName(CompileScope $scope): ?string
    {
        if ($this->value->isConstant()) {
            return $this->var = null;
        }
        return $this->var = $scope->newTempVar();
    }

    public function getName(): string
    {
        if ($this->value->isConstant()) {
            throw new \LogicException('Variable skipped for constant value');
        }
        if (null === $this->var) {
            throw new \LogicException('Name did not assign yet');
        }
        return $this->var;
    }

    public function hasReadAccess(): bool
    {
        return $this->readsCount > 0;
    }

    public function addReadAccess(): void
    {
        $this->readsCount++;
    }

    public function removeReadAccess(): void
    {
        if (0 === $this->readsCount) {
            throw new \LogicException('Already reached count = 0; check if everything is right');
        }
        $this->readsCount--;
    }
}