<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

class NodesList extends PhpList
{
    private const NODE_TYPE = [DataTypes::T_STRING, DataTypes::STR_HTML];

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
            if (self::NODE_TYPE !== $value->getDataType()) {
                throw new \LogicException('Unexpected string subtype');
            }

            if (null !== $last && $last->isConstant() && $value->isConstant()) {
                array_pop($new);
                $last = new PhpStringConst(
                    $last->getConstValue() . $value->getConstValue(),
                    self::NODE_TYPE[1]
                );
            } else {
                $last = $value;
            }
            $new[] = $last;
        }
        $this->values = $new;
    }
}
