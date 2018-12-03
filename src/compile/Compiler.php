<?php
namespace VovanVE\HtmlTemplate\compile;

use VovanVE\HtmlTemplate\compile\chunks\ComponentElement;
use VovanVE\HtmlTemplate\compile\chunks\DataTypes;
use VovanVE\HtmlTemplate\compile\chunks\DoctypeElement;
use VovanVE\HtmlTemplate\compile\chunks\HtmlElement;
use VovanVE\HtmlTemplate\compile\chunks\HtmlEncodeFilter;
use VovanVE\HtmlTemplate\compile\chunks\HtmlQuotedString;
use VovanVE\HtmlTemplate\compile\chunks\NodesList;
use VovanVE\HtmlTemplate\compile\chunks\PhpArray;
use VovanVE\HtmlTemplate\compile\chunks\PhpArrayPair;
use VovanVE\HtmlTemplate\compile\chunks\PhpBoolConst;
use VovanVE\HtmlTemplate\compile\chunks\PhpConcatenation;
use VovanVE\HtmlTemplate\compile\chunks\PhpList;
use VovanVE\HtmlTemplate\compile\chunks\PhpLogicAnd;
use VovanVE\HtmlTemplate\compile\chunks\PhpLogicOr;
use VovanVE\HtmlTemplate\compile\chunks\PhpNot;
use VovanVE\HtmlTemplate\compile\chunks\PhpNullConst;
use VovanVE\HtmlTemplate\compile\chunks\PhpStringConst;
use VovanVE\HtmlTemplate\compile\chunks\PhpTernary;
use VovanVE\HtmlTemplate\compile\chunks\PhpValue;
use VovanVE\HtmlTemplate\compile\chunks\ToStringFilter;
use VovanVE\HtmlTemplate\compile\chunks\ToStringTextFilter;
use VovanVE\HtmlTemplate\compile\chunks\Variable;
use VovanVE\HtmlTemplate\helpers\CompilerHelper;
use VovanVE\HtmlTemplate\report\Message;
use VovanVE\HtmlTemplate\report\MessageInterface;
use VovanVE\HtmlTemplate\report\Report;
use VovanVE\HtmlTemplate\report\ReportInterface;
use VovanVE\HtmlTemplate\source\TemplateInterface;
use VovanVE\parser\actions\AbortNodeException;
use VovanVE\parser\actions\ActionsMadeMap;
use VovanVE\parser\grammar\Grammar;
use VovanVE\parser\grammar\loaders\ArrayLoader;
use VovanVE\parser\Parser;

class Compiler implements CompilerInterface
{
    private const A_BUBBLE = Parser::ACTION_BUBBLE_THE_ONLY;

    private const VERSION = '0.2.2';

    private const STRING_ESCAPE_LETTER = [
        'b' => "\x08",
        'e' => "\e",
        'f' => "\f",
        'n' => "\n",
        'r' => "\r",
        't' => "\t",
        'v' => "\v",
    ];

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
            $new_e = CompilerHelper::buildSyntaxException($template, $e);
            $report->addMessage(new Message(
                MessageInterface::L_ERROR,
                $new_e->getMessage(),
                $new_e->getErrorLine()
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

        return new Parser($grammar);
    }

    /**
     * @return Grammar
     */
    protected function createParserGrammar(): Grammar
    {
        return ArrayLoader::createGrammar(require  __DIR__ . \DIRECTORY_SEPARATOR . 'grammar.php');
    }

    protected function getActionsMap(): ActionsMadeMap
    {
        return $this->actions ?? ($this->actions = $this->createActionsMap());
    }

    protected function createActionsMap(): ActionsMadeMap
    {
        $contentAsIs = \Closure::fromCallable('strval');
        $hexToDec = \Closure::fromCallable('hexdec');
        $returnEmptyArray = function () {
            return [];
        };
        $returnEmptyString = function () {
            return '';
        };
        $initArrayOfOne = function ($value) {
            return [$value];
        };
        $listAppendItem = function (array $list, $item) {
            $new_list = $list;
            $new_list[] = $item;
            return $new_list;
        };
        $htmlDecodeNow = function (string $content) {
            return CompilerHelper::htmlDecodeEntity($content);
        };
        $concatTwoFragments = function (string $a, string $b) {
            return $a . $b;
        };

        // ===================

        $makeStringConstText = function (string $text) {
            return new PhpStringConst($text);
        };
        $makeStringConstHtml = function (string $text) {
            return new PhpStringConst($text, DataTypes::STR_HTML);
        };
        $makeCharFromCode = function ($code) {
            if ($code > 0x10FFFF) {
                throw new AbortNodeException("Too big code - max is `10FFFF`", 1);
            }
            return new PhpStringConst(CompilerHelper::utf8CharFromCode($code));
        };
        $makeStringWithApostrophNow = function () {
            return new PhpStringConst("'");
        };
        $makeStringWithQuotNow = function () {
            return new PhpStringConst('"');
        };
        $makeStringWithDollar = function () {
            return new PhpStringConst('$');
        };
        $makeStringConcatText = function (array $values) {
            return new PhpConcatenation(DataTypes::STR_TEXT, ...$values);
        };
        $exprToStringText = function (PhpValue $value) {
            return ToStringTextFilter::create($value);
        };
        $makeStringEmpty = function () {
            return new PhpStringConst('');
        };

        $makeArrayEmpty = function () {
            return new PhpArray();
        };
        $makeArrayOfOne = function (PhpArrayPair $pair) {
            return new PhpArray($pair);
        };
        $makeArrayAppend = function (PhpArray $array, PhpArrayPair $pair) {
            return $array->append($pair);
        };

        $makeListOfOne = function (PhpValue $value) {
            return new PhpList($value);
        };
        $makeListAppend = function (PhpList $list, PhpValue $value) {
            return $list->append($value);
        };

        $makeNodesListOfOneNotNull = function (?PhpValue $value) {
            if (null === $value) {
                return new NodesList();
            }
            return new NodesList($value);
        };
        $makeNodesListAppendNotNull = function (NodesList $list, ?PhpValue $value) {
            return $value ? $list->append($value) : $list;
        };

        $makeNull = function () {
            return null;
        };

        $map = new ActionsMadeMap([
            'RootContent' => function (NodesList $content) {
                $result = new PhpConcatenation(DataTypes::STR_HTML, ...$content->getValues());
                if ($result->isConstant()) {
                    $result = new PhpStringConst($result->getConstValue(), DataTypes::STR_HTML);
                }
                return $result->finalize()->getPhpCode(new CompileScope);
            },
            'Content(next)' => $makeNodesListAppendNotNull,
            'Content(first)' => $makeNodesListOfOneNotNull,

            'Node' => self::A_BUBBLE,

            'Element' => self::A_BUBBLE,
            'ElementCode(begin)' => function (array $elementData, array $elementEnd) {
                /** @var string $elementBegin */
                /** @var PhpArray $attributes */
                [$elementBegin, $attributes] = $elementData;
                /** @var NodesList|null $content */
                if ($elementEnd) {
                    /** @var string $elementEndName */
                    [$content, $elementEndName] = $elementEnd;
                    if ($elementBegin !== $elementEndName) {
                        throw new AbortNodeException(
                            "Unexpected closing tag `</$elementEndName>` instead of expected `</$elementBegin>`",
                            2
                        );
                    }
                } else {
                    $content = null;
                }

                if (CompilerHelper::isComponentName($elementBegin)) {
                    return new ComponentElement($elementBegin, $attributes, $content);
                }
                if (CompilerHelper::isElementName($elementBegin)) {
                    return HtmlElement::create($elementBegin, $attributes, $content);
                }
                throw new AbortNodeException(
                    "Bad name <$elementBegin>"
                    . ", Component name must start with uppercase letter"
                    . " and HTML element name must be lowercase",
                    1
                );
            },
            'ElementCode(doctype)' => function (PhpList $list) {
                return new DoctypeElement(...$list->getValues());
            },
            'ElementEnd(single)' => $returnEmptyArray,
            'ElementEnd(block)' => self::A_BUBBLE,
            'ElementBeginContent(attr)' => function (string $element, PhpArray $attributes) {
                if (!$this->areElementAttributesEnabled($element, $attributes->getKeysConst(), $blockedAttribute)) {
                    throw new AbortNodeException(
                        "HTML attribute `$blockedAttribute` is not allowed in element `<$element>`",
                        1
                    );
                }
                return [$element, $attributes];
            },
            'ElementBeginContent' => function (string $element) {
                return [$element, new PhpArray()];
            },
            'BlockElementContinue' => function (NodesList $content, string $element) {
                return [$content, $element];
            },
            'BlockElementContinue(empty)' => function (string $element) {
                return [new NodesList(), $element];
            },
            'BlockElementClose' => self::A_BUBBLE,
            'ElementNameWS' => self::A_BUBBLE,
            'ElementName' => ($this->hasElementNameRestrictions())
                ? function (string $name) {
                    if ($this->isElementEnabled($name)) {
                        return $name;
                    }
                    throw new AbortNodeException("HTML Element `<$name>` is not allowed", 1);
                }
                : self::A_BUBBLE,

            'DoctypeContent(list)' => $makeListAppend,
            'DoctypeContent(first)' => $makeListOfOne,
            'DoctypeContentItemWs' => self::A_BUBBLE,
            'DoctypeContentItem(name)' => $makeStringConstHtml,
            'DoctypeContentItem' => function (string $value) {
                return new HtmlQuotedString($value);
            },

            'HtmlAttributes(list)' => function (PhpArray $array, PhpArrayPair $pair) use ($makeArrayAppend) {
                $attribute = $pair->getKey()->getConstValue();
                if ($array->hasKey($attribute)) {
                    throw new AbortNodeException(
                        "HTML attribute `$attribute` is duplicated",
                        2
                    );
                }
                return $array->append($pair);
            },
            'HtmlAttributes(first)' => $makeArrayOfOne,
            'HtmlAttributes(init)' => $makeArrayEmpty,
            'HtmlAttributeWS' => self::A_BUBBLE,
            'HtmlAttribute(Value)' => function (string $name, PhpValue $value) {
                return new PhpArrayPair(new PhpStringConst($name), $value);
            },
            'HtmlAttribute(Bool)' => function (string $name) {
                return new PhpArrayPair(new PhpStringConst($name), new PhpBoolConst(true));
            },
            'HtmlAttributeEqValue' => self::A_BUBBLE,
            'HtmlAttributeValue(Expr)' => self::A_BUBBLE,
            'HtmlAttributeValue(String)' => $makeStringConstText,
            'HtmlAttributeValue(Plain)' => $makeStringConstText,

            'HtmlName(ns)' => function (string $a, string $b) {
                return "$a:$b";
            },
            'HtmlName' => self::A_BUBBLE,
            'HtmlSimpleName(d)' => function (string $a, string $b) {
                return "$a-$b";
            },
            'HtmlSimpleName' => self::A_BUBBLE,
            'HtmlNameWord' => $contentAsIs,

            'HtmlQuotedConst' => self::A_BUBBLE,

            'HtmlQQConst' => self::A_BUBBLE,
            'HtmlQQConst(empty)' => $returnEmptyString,
            'HtmlQQText(loop)' => $concatTwoFragments,
            'HtmlQQText(first)' => self::A_BUBBLE,
            'HtmlQQTextPart(flow)' => $htmlDecodeNow,
            'HtmlQQTextPart' => self::A_BUBBLE,
            'HtmlQQTextPartSpec' => $contentAsIs,

            'HtmlQConst' => self::A_BUBBLE,
            'HtmlQConst(empty)' => $returnEmptyString,
            'HtmlQText(loop)' => $concatTwoFragments,
            'HtmlQText(first)' => self::A_BUBBLE,
            'HtmlQTextPart(flow)' => $htmlDecodeNow,
            'HtmlQTextPart' => self::A_BUBBLE,
            'HtmlQTextPartSpec' => $contentAsIs,

            'HtmlPlainValue' => $contentAsIs,
            'HtmlQuotedContentSafe' => $contentAsIs,

            'Text' => $makeStringConstHtml,
            'Text(empty)' => $makeNull,
            'InlineTextWithEolWs' => self::A_BUBBLE,
            'InlineText' => $contentAsIs,

            'Tag' => self::A_BUBBLE,
            'TagExpression' => self::A_BUBBLE,
            'TagContinueAny(comment)' => $makeNull,
            'TagContinueAny' => self::A_BUBBLE,
            'WsTagContinue' => self::A_BUBBLE,
            'TagContinue(Empty)' => $makeNull,
            'TagContinue(Expr)' => function (PhpValue $value) {
                return HtmlEncodeFilter::create(ToStringFilter::create($value));
            },
            'TagContinue(St)' => self::A_BUBBLE,
            'WsTagExpressionContinue' => self::A_BUBBLE,
            'TagExpressionContinue' => self::A_BUBBLE,
            'StatementContinue(I)' => self::A_BUBBLE,
            'InlineStatementContinue' => self::A_BUBBLE,

            'InlineStatement(unknown)' => function (string $name) {
                throw new AbortNodeException("Unknown instructions `$name`", 1);
            },

            'WsExpression' => self::A_BUBBLE,
            'ExpressionWs' => self::A_BUBBLE,

            'Ternary(then)' => function (PhpValue $cond, array $thenElse) {
                /** @var PhpValue $then */
                /** @var PhpValue $else */
                [$then, $else] = $thenElse;
                return PhpTernary::create($cond, $then, $else);
            },
            'Ternary(empty)' => self::A_BUBBLE,
            'TernaryThen' => self::A_BUBBLE,
            'TernaryThenElse' => function (PhpValue $then, PhpValue $else) {
                return [$then, $else];
            },

            'LogicOr(next)' => function (PhpValue $a, PhpValue $b) {
                return PhpLogicOr::create($a, $b);
            },
            'LogicOr(first)' => self::A_BUBBLE,

            'LogicAnd(next)' => function (PhpValue $a, PhpValue $b) {
                return PhpLogicAnd::create($a, $b);
            },
            'LogicAnd(first)' => self::A_BUBBLE,

            'ValueWs' => self::A_BUBBLE,
            'Value' => self::A_BUBBLE,

            'Value(not)' => function (PhpValue $a) {
                return PhpNot::create($a);
            },

            'Variable' => function (string $name) {
                $length = strlen($name);
                if (4 === $length) {
                    if (0 === strcmp('true', $name)) {
                        return new PhpBoolConst(true);
                    }
                    if (0 === strcmp('null', $name)) {
                        return new PhpNullConst();
                    }
                } elseif (5 === $length && 0 === strcmp('false', $name)) {
                    return new PhpBoolConst(false);
                }
                return new Variable($name);
            },

            'StringLiteral' => self::A_BUBBLE,

            'StringLiteralQQ' => $makeStringConcatText,
            'StringLiteralQQ(empty)' => $makeStringEmpty,
            'StringLiteralQQContent(list)' => $listAppendItem,
            'StringLiteralQQContent(first)' => $initArrayOfOne,
            'StringLiteralQQPart(text)' => $makeStringConcatText,
            'StringLiteralQQPart(expr)' => $exprToStringText,
            'StringLiteralQQPart(dollar)' => $makeStringWithDollar,
            'StringLiteralQQPartText(list)' => $listAppendItem,
            'StringLiteralQQPartText(first)' => $initArrayOfOne,
            'StringLiteralQQPartTextChunk' => self::A_BUBBLE,
            'StringLiteralQQPartTextChunk(q)' => $makeStringWithApostrophNow,

            'StringLiteralQ' => $makeStringConcatText,
            'StringLiteralQ(empty)' => $makeStringEmpty,
            'StringLiteralQContent(list)' => $listAppendItem,
            'StringLiteralQContent(first)' => $initArrayOfOne,
            'StringLiteralQPart(text)' => $makeStringConcatText,
            'StringLiteralQPart(expr)' => $exprToStringText,
            'StringLiteralQPart(dollar)' => $makeStringWithDollar,
            'StringLiteralQPartText(list)' => $listAppendItem,
            'StringLiteralQPartText(first)' => $initArrayOfOne,
            'StringLiteralQPartTextChunk' => self::A_BUBBLE,
            'StringLiteralQPartTextChunk(qq)' => $makeStringWithQuotNow,

            'StringLiteralEscape' => self::A_BUBBLE,
            'StringLiteralEscapeCode' => self::A_BUBBLE,

            'EscapeCodeX' => $makeCharFromCode,
            'EscapeCodeU' => $makeCharFromCode,
            'EscapeCodeUCode' => self::A_BUBBLE,
            'EscapeCodeHex2' => $hexToDec,
            'EscapeCodeHex4' => $hexToDec,
            'EscapeCodeHex' => $hexToDec,

            'EscapeCodeSingleLetter' => function ($letter) {
                if (!isset(self::STRING_ESCAPE_LETTER[$letter])) {
                    throw new AbortNodeException("Unknown escape-letter code `\\$letter`", 1);
                }
                return new PhpStringConst(self::STRING_ESCAPE_LETTER[$letter]);
            },
            'StringLiteralTextSafe' => $makeStringConstText,
            'EscapeCodePunctuation' => $makeStringConstText,

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
