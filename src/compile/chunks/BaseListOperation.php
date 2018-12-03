<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * @since 0.4.0
 */
abstract class BaseListOperation extends PhpValue
{
    /** @var PhpValue[] */
    protected $values;
    /** @var bool */
    protected $isConst;

    public function __construct(PhpValue ...$values)
    {
        parent::__construct();

        $this->values = $values;

        $this->isConst = true;
        foreach ($values as $value) {
            if (!$value->isConstant()) {
                $this->isConst = false;
                break;
            }
        }
    }

    /**
     * @return PhpValue|static
     */
    public function finalize(): PhpValue
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[] = $value->finalize();
        }

        return new static(...$values);
    }

    /**
     * @return PhpValue[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function isConstant(): bool
    {
        return $this->isConst;
    }
}
