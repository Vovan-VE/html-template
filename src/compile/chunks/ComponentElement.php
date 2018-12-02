<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class ComponentElement extends Element
{
    public function getPhpCode(CompileScope $scope): string
    {
        $arguments = $this->getArgumentsCode($scope);
        if (isset($arguments[2])) {
            $arguments[2] = "function(\$runtime){return {$arguments[2]};}";
        }

        $arguments = join(',', $arguments);
        /** @uses RuntimeHelperInterface::createComponent() */
        return "(\$runtime->createComponent($arguments))";
    }
}
