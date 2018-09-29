<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\helpers\CompilerHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;

class TagPrintText implements PhpValueInterface
{
    /** @var PhpValueInterface */
    private $value;

    public function __construct(PhpValueInterface $value)
    {
        $this->value = $value;
    }

    /**
     * @return PhpValueInterface
     */
    public function getValue(): PhpValueInterface
    {
        return $this->value;
    }

    public function getPhpCode(): string
    {
        if ($this->isConstant() && $this->getConstValue() === $this->value->getConstValue()) {
            return $this->value->getPhpCode();
        }

        if ($this->value instanceof PhpConcatenation) {
            // html(concat(const,const,var))
            // =>
            // concat(html(const),html(const),html(var))
            $values = [];
            foreach ($this->value->getValues() as $item) {
                $values[] = new static($item);
            }
            return (new PhpConcatenation(...$values))->getPhpCode();
        }

        /** @uses RuntimeHelperInterface::htmlEncode() */
        return "(\$runtime::htmlEncode({$this->value->getPhpCode()}))";
    }

    public function isConstant(): bool
    {
        return $this->value->isConstant();
    }

    public function getConstValue()
    {
        return CompilerHelper::htmlEncode($this->value->getConstValue());
    }
}
