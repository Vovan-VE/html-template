<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * @since 0.4.0
 */
class PhpTempVarAccess
{
    /** @var PhpTempVar */
    private $var;

    public function __construct(PhpTempVar $var)
    {
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
