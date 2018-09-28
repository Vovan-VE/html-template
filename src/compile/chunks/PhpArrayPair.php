<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class PhpArrayPair
{
    /** @var PhpValueInterface */
    private $key;
    /** @var PhpValueInterface */
    private $value;

    public function __construct(PhpValueInterface $key, PhpValueInterface $value)
    {
        if (!$key->isConstant()) {
            throw new \RuntimeException('Currently only constant keys are expected');
        }
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): PhpValueInterface
    {
        return $this->key;
    }

    public function getValue(): PhpValueInterface
    {
        return $this->value;
    }
}
