<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpArray extends PhpValue
{
    /** @var PhpArrayPair[] */
    private $pairs = [];
    /** @var bool */
    private $isConst;

    public function __construct(PhpArrayPair ...$pairs)
    {
        parent::__construct();
        $this->isConst = true;
        foreach ($pairs as $pair) {
            $key = $pair->getKey()->getConstValue();
            if (isset($this->pairs[$key])) {
                throw new \RuntimeException("Pair with key `$key` is already exist");
            }

            $this->pairs[$key] = $pair;
            if ($this->isConst && !$pair->getValue()->isConstant()) {
                $this->isConst = false;
            }
        }
    }

    public function append(PhpArrayPair ...$pairs): self
    {
        $copy = clone $this;

        foreach ($pairs as $pair) {
            $key = $pair->getKey()->getConstValue();
            if (isset($copy->pairs[$key])) {
                throw new \RuntimeException("Pair with key `$key` is already exist");
            }

            $copy->pairs[$key] = $pair;
            if ($copy->isConst && !$pair->getValue()->isConstant()) {
                $copy->isConst = false;
            }
        }

        return $copy;
    }

    public function isEmpty(): bool
    {
        return !$this->pairs;
    }

    public function hasKey(string $key): bool
    {
        return isset($this->pairs[$key]);
    }

    public function getKeysConst(): array
    {
        return array_keys($this->pairs);
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $pairs = [];
        foreach ($this->pairs as $pair) {
            $pairs[] = $pair->getKey()->getPhpCode($scope) . '=>' . $pair->getValue()->getPhpCode($scope);
        }
        return '[' . join(',', $pairs) . ']';
    }

    public function isConstant(): bool
    {
        return $this->isConst;
    }

    public function getConstValue(): array
    {
        if (!$this->isConst) {
            throw new \RuntimeException('Not a constant');
        }

        $result = [];
        foreach ($this->pairs as $pair) {
            $result[$pair->getKey()->getConstValue()] = $pair->getValue()->getConstValue();
        }
        return $result;
    }
}
