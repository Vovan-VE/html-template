<?php
namespace VovanVE\HtmlTemplate\caching\memory;

use VovanVE\HtmlTemplate\caching\CacheEntry;

class CacheStringEntry extends CacheEntry
{
    /** @var string|null */
    protected $meta;

    /**
     * @param string $className
     * @param string $content
     * @param string|null $meta
     */
    public function __construct(string $className, string $content, ?string $meta)
    {
        parent::__construct($className);
        $this->content = $content;
        $this->meta = $meta;
    }

    /**
     * @return void
     */
    protected function declareClass(): void
    {
        $code = '';

        $className = $this->getClassName();

        $pos = strrpos($className, '\\');
        if (false !== $pos) {
            $ns = substr($className, 0, $pos);
            $code .= "namespace $ns;\n\n";
            $name = substr($className, $pos + 1);
        } else {
            $name = $className;
        }

        /** @uses RuntimeEntryDummyInterface::run() */
        $code .= "class $name {\n"
            . "    public static function run(\$runtime): string\n"
            . "    {\n"
            . "        return " . $this->getContent() . ";\n"
            . "    }\n"
            . "}\n";

        eval($code);
    }

    /**
     * @return string|null
     */
    public function getMeta(): ?string
    {
        return $this->meta;
    }

    /**
     * @return string
     */
    protected function fetchContent(): string
    {
        throw new \LogicException('Unused');
    }
}
