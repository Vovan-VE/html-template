<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

use VovanVE\HtmlTemplate\compile\CompileScope;

class PhpList extends BaseListOperation
{
    public function isEmpty(): bool
    {
        return (bool)$this->values;
    }

    public function append(PhpValue ...$values): self
    {
        $copy = clone $this;

        foreach ($values as $value) {
            $copy->values[] = $value;
            if ($copy->isConst && !$value->isConstant()) {
                $copy->isConst = false;
            }
        }

        return $copy;
    }

    /**
     * @return array
     * @since 0.4.0
     */
    public function getDataType(): array
    {
        return [];
    }

    public function getPhpCode(CompileScope $scope): string
    {
        $result = [];
        foreach ($this->values as $value) {
            if ($value->isConstant() && DataTypes::T_STRING === ($value->getDataType()[0] ?? null)) {
                $value = new PhpStringConst(
                    $value->getConstValue(),
                    $value->getDataType()[1] ?? null
                );
            }
            $result[] = $value->getPhpCode($scope);
        }

        return '[' . join(',', $result) . ']';
    }

    public function getConstValue(): array
    {
        if (!$this->isConst) {
            throw new \RuntimeException('Not a constant');
        }

        $result = [];
        foreach ($this->values as $value) {
            $result[] = $value->getConstValue();
        }
        return $result;
    }
}
