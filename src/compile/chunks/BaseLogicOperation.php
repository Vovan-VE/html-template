<?php
namespace VovanVE\HtmlTemplate\compile\chunks;

/**
 * @since 0.4.0
 */
abstract class BaseLogicOperation extends BaseListOperation
{
    public function getDataType(): array
    {
        $type = null;
        foreach ($this->values as $value) {
            $t = $value->getDataType();
            if (!$t) {
                // untyped
                return [];
            }

            if (null === $type) {
                $type = $t;
            } else {
                if ($type[0] !== $t[0]) {
                    // different type
                    return [];
                }
                // same type
                if (($type[1] ?? null) !== ($t[1] ?? null)) {
                    // different subtype
                    return [$type[0]];
                }
                // same subtype
                // continue
            }
        }

        return $type ?? [];
    }
}
