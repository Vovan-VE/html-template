<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class PhpArrayPair
{
    /** @var PhpValue */
    private $key;
    /** @var PhpValue */
    private $value;

    public function __construct(PhpValue $key, PhpValue $value)
    {
        if (!$key->isConstant()) {
            throw new \RuntimeException('Currently only constant keys are expected');
        }
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): PhpValue
    {
        return $this->key;
    }

    public function getValue(): PhpValue
    {
        return $this->value;
    }
}
