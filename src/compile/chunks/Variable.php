<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class Variable extends PhpValue
{
    /** * @var string */
    private $name;

    public function __construct(string $name)
    {
        parent::__construct();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getPhpCode(CompileScope $scope): string
    {
        /** @uses RuntimeHelperInterface::param() */
        return '($runtime->param(' . var_export($this->name, true) . '))';
    }
}
