<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * @since 0.4.0
 */
abstract class PhpTempVarAccess extends PhpValue
{
    /** @var PhpTempVar */
    private $var;

    public function __construct(PhpTempVar $var)
    {
        parent::__construct();
        $this->var = $var;
    }

    public function __destruct()
    {
    }

    public function getVar(): PhpTempVar
    {
        return $this->var;
    }
}
