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
use VovanVE\parser\actions\ActionsMadeMap;
use VovanVE\parser\grammar\Grammar;
use VovanVE\parser\lexer\Lexer;
use VovanVE\parser\Parser;

class Compiler implements CompilerInterface
{
    private const A_BUBBLE = Parser::ACTION_BUBBLE_THE_ONLY;

    public $charset = 'UTF-8';

    /** @var Parser */
    private $parser;
    /** @var ActionsMadeMap */
    private $actions;

    /**
     * @param TemplateInterface $template
     * @return CompiledEntryInterface
     * @throws CompileException
     */
    public function compile($template): CompiledEntryInterface
    {
        $parser = $this->getParser();
        $actions = $this->getActionsMap();

        $source = $template->getContent();

        /** @var string $result */
        try {
            $result = $parser->parse($source, $actions)->made();
        } catch (\VovanVE\parser\SyntaxException $e) {
            throw CompilerHelper::buildSyntaxException($source, $e);
        }
        return new CompiledEntry($result);
    }

    /**
     * @param TemplateInterface $template
     * @return ReportInterface
     */
    public function syntaxCheck($template): ReportInterface
    {
        $parser = $this->getParser();

        $source = $template->getContent();

        $report = new Report();

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
        $htmlEncodeNow = function ($content) {
            return RuntimeHelper::htmlEncode($content, $this->charset);
        };
        //$htmlEncodeAtRuntime = function ($expr) use ($charset_at_runtime) {
        //    /** @uses RuntimeHelper::htmlEncode() */
        //    return '($runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . '))';
        //};
        $echoHtmlEncodeAtRuntime = function ($expr) use ($charset_at_runtime) {
            /** @uses RuntimeHelper::htmlEncode() */
            return '<' . '?= $runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . ') ?' . '>';
        };
        $concatTwoFragments = function ($a, $b) {
            return $a . $b;
        };
        $emptyStringAtRuntime = function () {
            return '""';
        };

        $surroundWithQuotes = function ($code) {
            return '"' . $code . '"';
        };
        $emptyCode = function () {
            return '';
        };
        return new ActionsMadeMap([
            'Content(next)' => $concatTwoFragments,
            'Content(first)' => self::A_BUBBLE,

            'Node' => self::A_BUBBLE,

            'Element' => function ($code) {
                return "<$code";
            },
            'ElementCode(begin)' => $concatTwoFragments,
            'ElementCode(end)' => function ($name) {
                return "/$name>";
            },
            'ElementEndMark(slash)' => function () {
                return '/>';
            },
            'ElementEndMark' => function () {
                return '>';
            },
            'ElementBeginContent(attr)' => $concatTwoFragments,
            'ElementBeginContent' => self::A_BUBBLE,
            'ElementNameWS' => self::A_BUBBLE,

            'HtmlAttributes(list)' => $concatTwoFragments,
            'HtmlAttributes(first)' => self::A_BUBBLE,
            'HtmlAttributes(init)' => $emptyCode,
            'HtmlAttributeWS' => function ($attr) {
                return " $attr";
            },
            'HtmlAttribute(Value)' => $concatTwoFragments,
            'HtmlAttribute(Bool)' => self::A_BUBBLE,
            'HtmlAttributeEqValue' => function ($value) {
                return "=$value";
            },
            'HtmlAttributeValue(Expr)' => function ($expr) use ($charset_at_runtime) {
                return '"<' . '?= $runtime::htmlEncode(' . $expr . ', ' . $charset_at_runtime . ') ?' . '>"';
            },
            'HtmlAttributeValue' => self::A_BUBBLE,
            'HtmlAttributeValue(Plain)' => $surroundWithQuotes,

            'HtmlName(ns)' => function ($a, $b) {
                return "$a:$b";
            },
            'HtmlName' => self::A_BUBBLE,
            'HtmlSimpleName(d)' => $concatTwoFragments,
            'HtmlSimpleName' => self::A_BUBBLE,
            'HtmlNameWord' => $contentAsIs,

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
            'InlineTextWithEolWs' => self::A_BUBBLE,
            'InlineText' => $contentAsIs,

            'Tag' => self::A_BUBBLE,
            'TagExpression' => self::A_BUBBLE,
            'TagContinue(comment)' => $emptyCode,
            'TagContinue' => self::A_BUBBLE,
            'WsTagContinue' => self::A_BUBBLE,
            'TagNormalContinue(Expr)' => function ($expr) {
                return '<' . '?= ' . $expr . ' ?' . '>';
            },
            'TagNormalContinue(St)' => function ($st) {
                return '<' . '?php ' . $st . ' ?' . '>';
            },
            'WsTagExpressionContinue' => self::A_BUBBLE,
            'TagExpressionContinue' => self::A_BUBBLE,
            'StatementContinue(I)' => self::A_BUBBLE,
            'InlineStatementContinue' => self::A_BUBBLE,

            'InlineStatement' => function (/*$name*/) {
                throw new CompileException('Instructions is not implemented yet');
            },

            'Expression' => self::A_BUBBLE,
            'Variable' => function ($name) {
                /** @uses RuntimeHelperInterface::param() */
                return '($runtime->param(' . var_export($name, true) . '))';
            },
            'name' => $contentAsIs,
        ]);
    }
}
