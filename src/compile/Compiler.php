<?php
namespace VovanVE\HtmlTemplate\compile;

use VovanVE\HtmlTemplate\helpers\CompilerHelper;
use VovanVE\HtmlTemplate\report\Message;
use VovanVE\HtmlTemplate\report\MessageInterface;
use VovanVE\HtmlTemplate\report\Report;
use VovanVE\HtmlTemplate\report\ReportInterface;
use VovanVE\HtmlTemplate\runtime\RuntimeHelper;
use VovanVE\HtmlTemplate\runtime\RuntimeHelperInterface;
use VovanVE\HtmlTemplate\source\TemplateInterface;
use VovanVE\parser\actions\ActionAbortException;
use VovanVE\parser\actions\ActionsMadeMap;
use VovanVE\parser\grammar\Grammar;
use VovanVE\parser\lexer\Lexer;
use VovanVE\parser\Parser;

class Compiler implements CompilerInterface
{
    private const A_BUBBLE = Parser::ACTION_BUBBLE_THE_ONLY;

    private const VERSION = '0.1.0-dev.1';

    public $charset = 'UTF-8';

    /** @var Parser */
    private $parser;
    /** @var ActionsMadeMap */
    private $actions;
    /** @var string[] */
    private $disabledElements = [];
    /** @var bool[] */
    private $disabledElementsHash = [];
    /** @var string[][] */
    private $disabledAttributes = [];
    /** @var bool[][] */
    private $disabledAttributesHash = [];

    /**
     * @return string
     * @since 0.1.0
     */
    public function getMeta(): string
    {
        return "Compiler: " . self::VERSION . "\n";
    }


    /**
     * @param TemplateInterface $template
     * @return CompiledEntryInterface
     * @throws SyntaxException
     */
    public function compile(TemplateInterface $template): CompiledEntryInterface
    {
        $parser = $this->getParser();
        $actions = $this->getActionsMap();

        $source = $template->getContent();

        /** @var string $result */
        try {
            $result = $parser->parse($source, $actions)->made();
        } catch (\VovanVE\parser\SyntaxException $e) {
            throw CompilerHelper::buildSyntaxException($template, $e);
        }
        return new CompiledEntry($result);
    }

    /**
     * @param TemplateInterface $template
     * @return ReportInterface
     */
    public function syntaxCheck(TemplateInterface $template): ReportInterface
    {
        $parser = $this->getParser();

        $source = $template->getContent();

        $report = new Report($template->getName());

        try {
            $parser->parse($source);
        } catch (\VovanVE\parser\SyntaxException $e) {
            $report->addMessage(new Message(
                MessageInterface::L_ERROR,
                $e->getMessage(),
                CompilerHelper::calcLineNumber($source, $e->getOffset())
            ));
        }

        return $report;
    }

    /**
     * @return string[]
     */
    public function getDisabledElements(): array
    {
        return $this->disabledElements;
    }

    /**
     * @param string[] $elements
     * @return $this
     */
    public function setDisabledElements(array $elements): self
    {
        $this->freeActionsMap();
        $this->disabledElements = $elements;
        $this->disabledElementsHash = [];
        foreach ($elements as $name) {
            $this->disabledElementsHash[strtolower($name)] = true;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getDisabledAttributes(): array
    {
        return $this->disabledAttributes;
    }

    /**
     * @param string[][] $attributesPerElements
     * @return $this
     */
    public function setDisabledAttributes(array $attributesPerElements): self
    {
        $this->disabledAttributes = $attributesPerElements;
        $this->disabledAttributesHash = [];
        foreach ($attributesPerElements as $element => $attributes) {
            $hash = [];
            foreach ($attributes as $attribute) {
                $hash[strtolower($attribute)] = true;
            }
            $this->disabledAttributesHash[strtolower($element)] = $hash;
        }
        return $this;
    }

    /**
     * @return bool
     */
    protected function hasElementNameRestrictions(): bool
    {
        return [] !== $this->disabledElementsHash;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isElementEnabled(string $name): bool
    {
        return [] === $this->disabledElementsHash
            || $this->isNameEnabledInHash($name, $this->disabledElementsHash);
    }

    /**
     * @param string $element
     * @param string[] $attributes
     * @param string $blocked
     * @return bool
     */
    protected function areElementAttributesEnabled(string $element, array $attributes, &$blocked): bool
    {
        $elements = $this->disabledAttributesHash;
        if ([] === $elements) {
            return true;
        }

        if (!empty($elements['*'])) {
            if (!$this->areNamesEnabledInHash($attributes, $elements['*'], $blocked)) {
                return false;
            }
        }

        $pos = strpos($element, ':');

        if (false !== $pos) {
            if (!empty($elements['*:*'])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements['*:*'], $blocked)) {
                    return false;
                }
            }
            $ns_lc = strtolower(substr($element, 0, $pos));
            $ns_any_lc = "$ns_lc:*";
            if (!empty($elements[$ns_any_lc])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements[$ns_any_lc], $blocked)) {
                    return false;
                }
            }
            $name_lc = strtolower(substr($element, $pos + 1));
            $any_name_lc = "*:$name_lc";
            if (!empty($elements[$any_name_lc])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements[$any_name_lc], $blocked)) {
                    return false;
                }
            }
            $ns_name_lc = "$ns_lc:$name_lc";
            if (!empty($elements[$ns_name_lc])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements[$ns_name_lc], $blocked)) {
                    return false;
                }
            }
        } else {
            if (!empty($elements[':*'])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements[':*'], $blocked)) {
                    return false;
                }
            }
            $name_lc = strtolower($element);
            if (!empty($elements[$name_lc])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements[$name_lc], $blocked)) {
                    return false;
                }
            }
            $colon_name_lc = ":$name_lc";
            if (!empty($elements[$colon_name_lc])) {
                if (!$this->areNamesEnabledInHash($attributes, $elements[$colon_name_lc], $blocked)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return Parser
     */
    protected function getParser(): Parser
    {
        return $this->parser ?? ($this->parser = $this->createParser());
    }

    /**
     * @return Parser
     */
    protected function createParser(): Parser
    {
        $grammar = $this->createParserGrammar();
        $lexer = $this->createParserLexer();

        return new Parser($lexer, $grammar);
    }

    /**
     * @return Grammar
     */
    protected function createParserGrammar(): Grammar
    {
        $grammarSource = file_get_contents(__DIR__ . \DIRECTORY_SEPARATOR . 'grammar.txt');

        return Grammar::create($grammarSource);
    }

    /**
     * @return Lexer
     */
    protected function createParserLexer(): Lexer
    {
        return new Lexer;
    }

    protected function getActionsMap(): ActionsMadeMap
    {
        return $this->actions ?? ($this->actions = $this->createActionsMap());
    }

    protected function createActionsMap(): ActionsMadeMap
    {
        $charset_at_runtime = var_export($this->charset, true);

        $contentAsIs = \Closure::fromCallable('strval');
        $htmlEncodeNow = function (string $content) {
            return RuntimeHelper::htmlEncode($content, $this->charset);
        };
        //$htmlEncodeAtRuntime = function (string $expr) use ($charset_at_runtime) {
        //    /** @uses RuntimeHelper::htmlEncode() */
        //    return '($runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . '))';
        //};
        $echoHtmlEncodeAtRuntime = function (string $expr) use ($charset_at_runtime) {
            /** @uses RuntimeHelper::htmlEncode() */
            return '<' . '?= $runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . ') ?' . '>';
        };
        $concatTwoFragments = function (string $a, string $b) {
            return $a . $b;
        };
        $emptyStringAtRuntime = function () {
            return '""';
        };

        $surroundWithQuotes = function (string $code) {
            return '"' . $code . '"';
        };
        $emptyCode = function () {
            return '';
        };
        $listAppendItem = function (array $list, $item) {
            $new_list = $list;
            $new_list[] = $item;
            return $new_list;
        };

        $map = new ActionsMadeMap([
            'Content(next)' => $concatTwoFragments,
            'Content(first)' => self::A_BUBBLE,

            'Node' => self::A_BUBBLE,

            'Element' => function (string $code) {
                return "<$code";
            },
            'ElementCode(begin)' => function (array $elementData, array $elementEnd) {
                [$elementBegin, $code] = $elementData;
                if ($elementEnd) {
                    [$content, $elementEnd] = $elementEnd;
                    if ($elementBegin !== $elementEnd) {
                        throw new ActionAbortException("Unexpected closing tag `</$elementEnd>` instead of expected `</$elementBegin>`");
                    }
                    return $code . ">$content</$elementEnd>";
                }
                return $code . "/>";
            },
            'ElementCode(doctype)' => function (array $list) {
                return '!DOCTYPE ' . join(' ', $list) . '>';
            },
            'ElementEnd(single)' => function () {
                return [];
            },
            'ElementEnd(block)' => self::A_BUBBLE,
            'ElementBeginContent(attr)' => function (string $element, array $attributes) {
                $result = $element;
                $names = [];
                foreach ($attributes as [$attribute, $code]) {
                    $result .= $code;
                    $names[$attribute] = $attribute;
                }
                if (!$this->areElementAttributesEnabled($element, $names, $blockedAttribute)) {
                    throw new ActionAbortException("HTML attribute `$blockedAttribute` is not allowed in element `<$element>`");
                }
                return [$element, $result];
            },
            'ElementBeginContent' => function (string $element) {
                return [$element, $element];
            },
            'BlockElementContinue' => function (string $content, string $element) {
                return [$content, $element];
            },
            'BlockElementContinue(empty)' => function (string $element) {
                return ['', $element];
            },
            'BlockElementClose' => self::A_BUBBLE,
            'ElementNameWS' => self::A_BUBBLE,
            'ElementName' => ($this->hasElementNameRestrictions())
                ? function (string $name) {
                    if ($this->isElementEnabled($name)) {
                        return $name;
                    }
                    throw new ActionAbortException("HTML Element `<$name>` is not allowed");
                }
                : self::A_BUBBLE,

            'DoctypeContent(list)' => $listAppendItem,
            'DoctypeContent(first)' => function ($item) {
                return [$item];
            },
            'DoctypeContentItemWs' => self::A_BUBBLE,
            'DoctypeContentItem' => self::A_BUBBLE,

            'HtmlAttributes(list)' => $listAppendItem,
            'HtmlAttributes(first)' => function (array $attr) {
                return [$attr];
            },
            'HtmlAttributes(init)' => function () {
                return [];
            },
            'HtmlAttributeWS' => function (array $attrData) {
                [$name, $code] = $attrData;
                return [$name, " $code"];
            },
            'HtmlAttribute(Value)' => function (string $name, string $eqValue) {
                return [$name, $name . $eqValue];
            },
            'HtmlAttribute(Bool)' => function (string $name) {
                return [$name, $name];
            },
            'HtmlAttributeEqValue' => function (string $value) {
                return "=$value";
            },
            'HtmlAttributeValue(Expr)' => function (string $expr) use ($charset_at_runtime) {
                /** @uses RuntimeHelperInterface::htmlEncode() */
                return '"<' . '?= $runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . ') ?' . '>"';
            },
            'HtmlAttributeValue' => self::A_BUBBLE,
            'HtmlAttributeValue(Plain)' => $surroundWithQuotes,

            'HtmlName(ns)' => function (string $a, string $b) {
                return "$a:$b";
            },
            'HtmlName' => self::A_BUBBLE,
            'HtmlSimpleName(d)' => $concatTwoFragments,
            'HtmlSimpleName' => self::A_BUBBLE,
            'HtmlNameWord' => $contentAsIs,

            'HtmlQQConst' => $surroundWithQuotes,
            'HtmlQQConst(empty)' => $emptyStringAtRuntime,
            'HtmlQQString' => $surroundWithQuotes,
            'HtmlQQString(empty)' => $emptyStringAtRuntime,
            'HtmlQQContent(loop)' => $concatTwoFragments,
            'HtmlQQContent(first)' => self::A_BUBBLE,
            'HtmlQQContentPart' => self::A_BUBBLE,
            'HtmlQQContentPart(E)' => $echoHtmlEncodeAtRuntime,
            'HtmlQQText(loop)' => $concatTwoFragments,
            'HtmlQQText(first)' => self::A_BUBBLE,
            'HtmlQQTextPart' => self::A_BUBBLE,
            'HtmlQQTextPartSpec' => $htmlEncodeNow,

            'HtmlQConst' => $surroundWithQuotes,
            'HtmlQConst(empty)' => $emptyStringAtRuntime,
            'HtmlQString' => $surroundWithQuotes,
            'HtmlQString(empty)' => $emptyStringAtRuntime,
            'HtmlQContent(loop)' => $concatTwoFragments,
            'HtmlQContent(first)' => self::A_BUBBLE,
            'HtmlQContentPart' => self::A_BUBBLE,
            'HtmlQContentPart(E)' => $echoHtmlEncodeAtRuntime,
            'HtmlQText(loop)' => $concatTwoFragments,
            'HtmlQText(first)' => self::A_BUBBLE,
            'HtmlQTextPart' => self::A_BUBBLE,
            'HtmlQTextPartSpec' => $htmlEncodeNow,

            'HtmlPlainValue' => $contentAsIs,
            'HtmlFlowText' => $contentAsIs,

            'Text' => self::A_BUBBLE,
            'Text(empty)' => $emptyCode,
            'InlineTextWithEolWs' => self::A_BUBBLE,
            'InlineText' => $contentAsIs,

            'Tag' => self::A_BUBBLE,
            'TagExpression' => self::A_BUBBLE,
            'TagContinueAny(comment)' => $emptyCode,
            'TagContinueAny' => self::A_BUBBLE,
            'WsTagContinue' => self::A_BUBBLE,
            'TagContinue(Empty)' => $emptyCode,
            'TagContinue(Expr)' => function (string $expr) use ($charset_at_runtime) {
                /** @uses RuntimeHelperInterface::htmlEncode() */
                return '<' . '?= $runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . ') ?' . '>';
            },
            'TagContinue(St)' => function (string $st) {
                return '<' . '?php ' . $st . ' ?' . '>';
            },
            'WsTagExpressionContinue' => self::A_BUBBLE,
            'TagExpressionContinue' => self::A_BUBBLE,
            'StatementContinue(I)' => self::A_BUBBLE,
            'InlineStatementContinue' => self::A_BUBBLE,

            'InlineStatement(block)' => function (string $name) {
                /** @uses RuntimeHelperInterface::renderBlock() */
                return '$runtime->renderBlock(' . var_export($name, true) . ')';
            },
            'InlineStatement(unknown)' => function (string $name) {
                throw new ActionAbortException("Unknown instructions `$name`");
            },

            'Expression' => self::A_BUBBLE,
            'Variable' => function (string $name) {
                /** @uses RuntimeHelperInterface::param() */
                return '($runtime->param(' . var_export($name, true) . '))';
            },
            'name' => $contentAsIs,
        ]);

        $map->prune = true;

        return $map;
    }

    protected function freeActionsMap(): void
    {
        $this->actions = null;
    }

    /**
     * @param string[] $names
     * @param bool[] $hash
     * @param string $blocked
     * @return bool
     */
    protected function areNamesEnabledInHash(array $names, array $hash, &$blocked): bool
    {
        foreach ($names as $name) {
            if (!$this->isNameEnabledInHash($name, $hash)) {
                $blocked = $name;
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $name
     * @param bool[] $hash
     * @return bool
     */
    protected function isNameEnabledInHash(string $name, array $hash): bool
    {
        if (isset($hash['*'])) {
            return false;
        }

        $pos = strpos($name, ':');

        if (false !== $pos) {
            if (isset($hash['*:*'])) {
                return false;
            }
            $ns_lc = strtolower(substr($name, 0, $pos));
            if (isset($hash["$ns_lc:*"])) {
                return false;
            }
            $name_lc = strtolower(substr($name, $pos + 1));
            if (isset($hash["*:$name_lc"]) || isset($hash["$ns_lc:$name_lc"])) {
                return false;
            }
        } else {
            if (isset($hash[':*'])) {
                return false;
            }
            $name_lc = strtolower($name);
            if (isset($hash[$name_lc]) || isset($hash[":$name_lc"])) {
                return false;
            }
        }

        return true;
    }
}
