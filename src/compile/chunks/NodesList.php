<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class NodesList extends PhpList
{
    public function __construct(PhpValueInterface ...$values)
    {
        parent::__construct(...$values);

        $this->compact();
    }

    public function append(PhpValueInterface ...$values): PhpList
    {
        /** @var static $copy */
        $copy = parent::append(...$values);
        $copy->compact();
        return $copy;
    }

    protected function compact(): void
    {
        $new = [];
        /** @var PhpValueInterface $last */
        $last = null;
        foreach ($this->values as $value) {
            if (null !== $last && $last->isConstant() && $value->isConstant()) {
                array_pop($new);
                $last = new PhpStringConst($last->getConstValue() . $value->getConstValue());
            } else {
                $last = $value;
            }
            $new[] = $last;
        }
        $this->values = $new;
    }
}
