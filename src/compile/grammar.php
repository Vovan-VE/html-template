<?php
return [
    'rules' => [
        [
            'name' => 'Goal',
            'eof' => true,
            'definition' => ['RootContent'],
        ],
        [
            'name' => 'RootContent',
            'definition' => ['Content'],
        ],
        [
            'name' => 'Content',
            'tag' => 'next',
            'definition' => ['Content', 'Node'],
        ],
        [
            'name' => 'Content',
            'tag' => 'first',
            'definition' => ['Node'],
        ],
        [
            'name' => 'Node',
            'definition' => ['Element'],
        ],
        [
            'name' => 'Node',
            'definition' => ['Tag'],
        ],
        [
            'name' => 'Node',
            'definition' => ['Text'],
        ],
        [
            'name' => 'Element',
            'definition' => ['<', 'ElementCode'],
        ],
        [
            'name' => 'ElementCode',
            'tag' => 'begin',
            'definition' => ['ElementBeginContent', 'ElementEnd'],
        ],
        [
            'name' => 'ElementCode',
            'tag' => 'doctype',
            'definition' => [
                '!',
                'DOCTYPE',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'DoctypeContent',
                '>',
            ],
        ],
        [
            'name' => 'ElementEnd',
            'tag' => 'single',
            'definition' => ['/>'],
        ],
        [
            'name' => 'ElementEnd',
            'tag' => 'block',
            'definition' => ['>', 'BlockElementContinue'],
        ],
        [
            'name' => 'BlockElementContinue',
            'definition' => ['Content', 'BlockElementClose'],
        ],
        [
            'name' => 'BlockElementContinue',
            'tag' => 'empty',
            'definition' => ['BlockElementClose'],
        ],
        [
            'name' => 'BlockElementClose',
            'definition' => ['</', 'ElementNameWS', '>'],
        ],
        [
            'name' => 'ElementBeginContent',
            'tag' => 'attr',
            'definition' => ['ElementName', 'HtmlAttributes'],
        ],
        [
            'name' => 'ElementBeginContent',
            'definition' => ['ElementName'],
        ],
        [
            'name' => 'ElementNameWS',
            'definition' => [
                'ElementName',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'ElementNameWS',
            'definition' => ['ElementName'],
        ],
        [
            'name' => 'ElementName',
            'definition' => ['HtmlName'],
        ],
        [
            'name' => 'DoctypeContent',
            'tag' => 'list',
            'definition' => ['DoctypeContent', 'DoctypeContentItemWs'],
        ],
        [
            'name' => 'DoctypeContent',
            'tag' => 'first',
            'definition' => ['DoctypeContentItemWs'],
        ],
        [
            'name' => 'DoctypeContentItemWs',
            'definition' => [
                'DoctypeContentItem',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'DoctypeContentItemWs',
            'definition' => ['DoctypeContentItem'],
        ],
        [
            'name' => 'DoctypeContentItem',
            'tag' => 'name',
            'definition' => ['HtmlName'],
        ],
        [
            'name' => 'DoctypeContentItem',
            'definition' => ['HtmlQuotedConst'],
        ],
        [
            'name' => 'HtmlAttributes',
            'tag' => 'list',
            'definition' => ['HtmlAttributes', 'HtmlAttributeWS'],
        ],
        [
            'name' => 'HtmlAttributes',
            'tag' => 'init',
            'definition' => [
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'HtmlAttributeWS',
            'definition' => [
                'HtmlAttribute',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'HtmlAttributeWS',
            'definition' => ['HtmlAttribute'],
        ],
        [
            'name' => 'HtmlAttribute',
            'tag' => 'Value',
            'definition' => ['HtmlName', 'HtmlAttributeEqValue'],
        ],
        [
            'name' => 'HtmlAttribute',
            'tag' => 'Bool',
            'definition' => ['HtmlName'],
        ],
        [
            'name' => 'HtmlAttributeEqValue',
            'definition' => ['=', 'HtmlAttributeValue'],
        ],
        [
            'name' => 'HtmlAttributeValue',
            'tag' => 'Expr',
            'definition' => ['TagExpression'],
        ],
        [
            'name' => 'HtmlAttributeValue',
            'tag' => 'String',
            'definition' => ['HtmlQuotedConst'],
        ],
        [
            'name' => 'HtmlAttributeValue',
            'tag' => 'Plain',
            'definition' => ['HtmlPlainValue'],
        ],
        [
            'name' => 'HtmlName',
            'tag' => 'ns',
            'definition' => ['HtmlSimpleName', ':', 'HtmlSimpleName'],
        ],
        [
            'name' => 'HtmlName',
            'definition' => ['HtmlSimpleName'],
        ],
        [
            'name' => 'HtmlSimpleName',
            'tag' => 'd',
            'definition' => ['HtmlSimpleName', '-', 'HtmlNameWord'],
        ],
        [
            'name' => 'HtmlSimpleName',
            'definition' => ['HtmlNameWord'],
        ],
        [
            'name' => 'HtmlQuotedConst',
            'definition' => ['HtmlQQConst'],
        ],
        [
            'name' => 'HtmlQuotedConst',
            'definition' => ['HtmlQConst'],
        ],
        [
            'name' => 'HtmlQQConst',
            'definition' => ['"', 'HtmlQQText', '"'],
        ],
        [
            'name' => 'HtmlQQConst',
            'tag' => 'empty',
            'definition' => ['"', '"'],
        ],
        [
            'name' => 'HtmlQQText',
            'tag' => 'loop',
            'definition' => ['HtmlQQText', 'HtmlQQTextPart'],
        ],
        [
            'name' => 'HtmlQQText',
            'tag' => 'first',
            'definition' => ['HtmlQQTextPart'],
        ],
        [
            'name' => 'HtmlQQTextPart',
            'tag' => 'flow',
            'definition' => ['HtmlQuotedContentSafe'],
        ],
        [
            'name' => 'HtmlQQTextPart',
            'definition' => ['HtmlQQTextPartSpec'],
        ],
        [
            'name' => 'HtmlQConst',
            'definition' => ['\'', 'HtmlQText', '\''],
        ],
        [
            'name' => 'HtmlQConst',
            'tag' => 'empty',
            'definition' => ['\'', '\''],
        ],
        [
            'name' => 'HtmlQText',
            'tag' => 'loop',
            'definition' => ['HtmlQText', 'HtmlQTextPart'],
        ],
        [
            'name' => 'HtmlQText',
            'tag' => 'first',
            'definition' => ['HtmlQTextPart'],
        ],
        [
            'name' => 'HtmlQTextPart',
            'tag' => 'flow',
            'definition' => ['HtmlQuotedContentSafe'],
        ],
        [
            'name' => 'HtmlQTextPart',
            'definition' => ['HtmlQTextPartSpec'],
        ],
        [
            'name' => 'Text',
            'tag' => 'empty',
            'definition' => [
                [
                    'name' => 'EolWithWs',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'Text',
            'definition' => [
                [
                    'name' => 'EolWithWs',
                    'hidden' => true,
                ],
                'InlineTextWithEolWs',
            ],
        ],
        [
            'name' => 'Text',
            'definition' => ['InlineTextWithEolWs'],
        ],
        [
            'name' => 'InlineTextWithEolWs',
            'definition' => [
                'InlineText',
                [
                    'name' => 'EolWithWs',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'InlineTextWithEolWs',
            'definition' => ['InlineText'],
        ],
        [
            'name' => 'Tag',
            'definition' => ['{', 'TagContinueAny'],
        ],
        [
            'name' => 'TagExpression',
            'definition' => ['{', 'WsTagExpressionContinue'],
        ],
        [
            'name' => 'TagContinueAny',
            'tag' => 'comment',
            'definition' => [
                [
                    'name' => 'CommentTagContinue',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'TagContinueAny',
            'definition' => ['WsTagContinue'],
        ],
        [
            'name' => 'CommentTagContinue',
            'definition' => [
                '#',
                [
                    'name' => 'CommentTagContent',
                    'hidden' => true,
                ],
                '#}',
            ],
        ],
        [
            'name' => 'CommentTagContinue',
            'definition' => ['#', '#}'],
        ],
        [
            'name' => 'WsTagContinue',
            'definition' => [
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'TagContinue',
            ],
        ],
        [
            'name' => 'WsTagContinue',
            'definition' => ['TagContinue'],
        ],
        [
            'name' => 'TagContinue',
            'tag' => 'Empty',
            'definition' => ['}'],
        ],
        [
            'name' => 'TagContinue',
            'tag' => 'Expr',
            'definition' => ['TagExpressionContinue'],
        ],
        [
            'name' => 'TagContinue',
            'tag' => 'St',
            'definition' => ['%', 'StatementContinue'],
        ],
        [
            'name' => 'WsTagExpressionContinue',
            'definition' => [
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'TagExpressionContinue',
            ],
        ],
        [
            'name' => 'WsTagExpressionContinue',
            'definition' => ['TagExpressionContinue'],
        ],
        [
            'name' => 'TagExpressionContinue',
            'definition' => [
                'ExpressionWs',
                [
                    'name' => 'TagEnd',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'StatementContinue',
            'tag' => 'I',
            'definition' => ['InlineStatementContinue'],
        ],
        [
            'name' => 'InlineStatementContinue',
            'definition' => [
                'InlineStatement',
                [
                    'name' => 'WsTagEnd',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'WsTagEnd',
            'definition' => [
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                [
                    'name' => 'TagEnd',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'WsTagEnd',
            'definition' => [
                [
                    'name' => 'TagEnd',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'TagEnd',
            'definition' => ['}'],
        ],
        [
            'name' => 'InlineStatement',
            'tag' => 'unknown',
            'definition' => ['name'],
        ],
        [
            'name' => 'WsExpression',
            'definition' => [
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'ExpressionWs',
            ],
        ],
        [
            'name' => 'WsExpression',
            'definition' => ['ExpressionWs'],
        ],
        [
            'name' => 'ExpressionWs',
            'definition' => ['Ternary'],
        ],
        [
            'name' => 'Ternary',
            'tag' => 'then',
            'definition' => ['LogicOr', 'TernaryThen'],
        ],
        [
            'name' => 'Ternary',
            'tag' => 'empty',
            'definition' => ['LogicOr'],
        ],
        [
            'name' => 'TernaryThen',
            'definition' => [
                '?',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'TernaryThenElse',
            ],
        ],
        [
            'name' => 'TernaryThen',
            'definition' => ['?', 'TernaryThenElse'],
        ],
        [
            'name' => 'TernaryThenElse',
            'definition' => [
                'Ternary',
                ':',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'Ternary',
            ],
        ],
        [
            'name' => 'TernaryThenElse',
            'definition' => ['Ternary', ':', 'Ternary'],
        ],
        [
            'name' => 'LogicOr',
            'tag' => 'next',
            'definition' => [
                'LogicOr',
                '||',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'LogicAnd',
            ],
        ],
        [
            'name' => 'LogicOr',
            'tag' => 'next',
            'definition' => ['LogicOr', '||', 'LogicAnd'],
        ],
        [
            'name' => 'LogicOr',
            'tag' => 'first',
            'definition' => ['LogicAnd'],
        ],
        [
            'name' => 'LogicAnd',
            'tag' => 'next',
            'definition' => [
                'LogicAnd',
                '&&',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'ValueWs',
            ],
        ],
        [
            'name' => 'LogicAnd',
            'tag' => 'next',
            'definition' => ['LogicAnd', '&&', 'ValueWs'],
        ],
        [
            'name' => 'LogicAnd',
            'tag' => 'first',
            'definition' => ['ValueWs'],
        ],
        [
            'name' => 'ValueWs',
            'definition' => [
                'Value',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
            ],
        ],
        [
            'name' => 'ValueWs',
            'definition' => ['Value'],
        ],
        [
            'name' => 'Value',
            'definition' => ['Variable'],
        ],
        [
            'name' => 'Value',
            'definition' => ['StringLiteral'],
        ],
        [
            'name' => 'Value',
            'definition' => ['(', 'WsExpression', ')'],
        ],
        [
            'name' => 'Value',
            'tag' => 'not',
            'definition' => [
                '!',
                [
                    'name' => 'ws',
                    'hidden' => true,
                ],
                'Value',
            ],
        ],
        [
            'name' => 'Value',
            'tag' => 'not',
            'definition' => ['!', 'Value'],
        ],
        [
            'name' => 'Variable',
            'definition' => ['name'],
        ],
        [
            'name' => 'StringLiteral',
            'definition' => ['StringLiteralQQ'],
        ],
        [
            'name' => 'StringLiteral',
            'definition' => ['StringLiteralQ'],
        ],
        [
            'name' => 'StringLiteralQQ',
            'definition' => ['"', 'StringLiteralQQContent', '"'],
        ],
        [
            'name' => 'StringLiteralQQ',
            'tag' => 'empty',
            'definition' => ['"', '"'],
        ],
        [
            'name' => 'StringLiteralQQContent',
            'tag' => 'list',
            'definition' => ['StringLiteralQQContent', 'StringLiteralQQPart'],
        ],
        [
            'name' => 'StringLiteralQQContent',
            'tag' => 'first',
            'definition' => ['StringLiteralQQPart'],
        ],
        [
            'name' => 'StringLiteralQQPart',
            'tag' => 'text',
            'definition' => ['StringLiteralQQPartText'],
        ],
        [
            'name' => 'StringLiteralQQPart',
            'tag' => 'expr',
            'definition' => ['${', 'WsTagExpressionContinue'],
        ],
        [
            'name' => 'StringLiteralQQPart',
            'tag' => 'dollar',
            'definition' => ['$'],
        ],
        [
            'name' => 'StringLiteralQQPartText',
            'tag' => 'list',
            'definition' => [
                'StringLiteralQQPartText',
                'StringLiteralQQPartTextChunk',
            ],
        ],
        [
            'name' => 'StringLiteralQQPartText',
            'tag' => 'first',
            'definition' => ['StringLiteralQQPartTextChunk'],
        ],
        [
            'name' => 'StringLiteralQQPartTextChunk',
            'definition' => ['StringLiteralTextSafe'],
        ],
        [
            'name' => 'StringLiteralQQPartTextChunk',
            'definition' => ['StringLiteralEscape'],
        ],
        [
            'name' => 'StringLiteralQQPartTextChunk',
            'tag' => 'q',
            'definition' => ['\''],
        ],
        [
            'name' => 'StringLiteralQ',
            'definition' => ['\'', 'StringLiteralQContent', '\''],
        ],
        [
            'name' => 'StringLiteralQ',
            'tag' => 'empty',
            'definition' => ['\'', '\''],
        ],
        [
            'name' => 'StringLiteralQContent',
            'tag' => 'list',
            'definition' => ['StringLiteralQContent', 'StringLiteralQPart'],
        ],
        [
            'name' => 'StringLiteralQContent',
            'tag' => 'first',
            'definition' => ['StringLiteralQPart'],
        ],
        [
            'name' => 'StringLiteralQPart',
            'tag' => 'text',
            'definition' => ['StringLiteralQPartText'],
        ],
        [
            'name' => 'StringLiteralQPart',
            'tag' => 'expr',
            'definition' => ['${', 'WsTagExpressionContinue'],
        ],
        [
            'name' => 'StringLiteralQPart',
            'tag' => 'dollar',
            'definition' => ['$'],
        ],
        [
            'name' => 'StringLiteralQPartText',
            'tag' => 'list',
            'definition' => ['StringLiteralQPartText', 'StringLiteralQPartTextChunk'],
        ],
        [
            'name' => 'StringLiteralQPartText',
            'tag' => 'first',
            'definition' => ['StringLiteralQPartTextChunk'],
        ],
        [
            'name' => 'StringLiteralQPartTextChunk',
            'definition' => ['StringLiteralTextSafe'],
        ],
        [
            'name' => 'StringLiteralQPartTextChunk',
            'definition' => ['StringLiteralEscape'],
        ],
        [
            'name' => 'StringLiteralQPartTextChunk',
            'tag' => 'qq',
            'definition' => ['"'],
        ],
        [
            'name' => 'StringLiteralEscape',
            'definition' => ['\\', 'StringLiteralEscapeCode'],
        ],
        [
            'name' => 'StringLiteralEscapeCode',
            'definition' => ['EscapeCodeX'],
        ],
        [
            'name' => 'StringLiteralEscapeCode',
            'definition' => ['EscapeCodeU'],
        ],
        [
            'name' => 'StringLiteralEscapeCode',
            'definition' => ['EscapeCodeSingleLetter'],
        ],
        [
            'name' => 'StringLiteralEscapeCode',
            'definition' => ['EscapeCodePunctuation'],
        ],
        [
            'name' => 'EscapeCodeX',
            'definition' => ['x', 'EscapeCodeHex2'],
        ],
        [
            'name' => 'EscapeCodeX',
            'definition' => ['X', 'EscapeCodeHex2'],
        ],
        [
            'name' => 'EscapeCodeU',
            'definition' => ['u', 'EscapeCodeUCode'],
        ],
        [
            'name' => 'EscapeCodeU',
            'definition' => ['U', 'EscapeCodeUCode'],
        ],
        [
            'name' => 'EscapeCodeUCode',
            'definition' => ['EscapeCodeHex4'],
        ],
        [
            'name' => 'EscapeCodeUCode',
            'definition' => ['{', 'EscapeCodeHex', '}'],
        ],
    ],
    'terminals' => [
        '!',
        '"',
        '#',
        '#}',
        '$',
        '${',
        '%',
        '&&',
        '\'',
        '(',
        ')',
        '-',
        '/>',
        ':',
        '<',
        '</',
        '=',
        '>',
        '?',
        'DOCTYPE',
        'U',
        'X',
        '\\',
        'u',
        'x',
        '{',
        '||',
        '}',
        [
            'name' => 'HtmlNameWord',
            'match' => '(?i)[a-z][a-z0-9]*+',
        ],
        [
            'name' => 'name',
            'match' => '(?i)[a-z_][a-z_0-9]*+',
        ],
        [
            'name' => 'ws',
            'match' => '\\s++',
        ],
        [
            'name' => 'InlineText',
            'match' => '(?:[^\\s<>{}]++|\\h++(?!\\z|\\R))++',
        ],
        [
            'name' => 'CommentTagContent',
            'match' => '(?:[^#]++|#(?!\\}))++',
        ],
        [
            'name' => 'HtmlQQTextPartSpec',
            'match' => '\'++',
        ],
        [
            'name' => 'HtmlQTextPartSpec',
            'match' => '"++',
        ],
        [
            'name' => 'HtmlQuotedContentSafe',
            'match' => '[^\'"]++',
        ],
        [
            'name' => 'EolWithWs',
            'match' => '\\h*+(?:\\R\\h*+)++',
        ],
        [
            'name' => 'HtmlPlainValue',
            'match' => '[^<>\'"={}\\s\\/]++|\\/(?!>)',
        ],
        [
            'name' => 'EscapeCodeHex2',
            'match' => '(?i)[a-f0-9]{2}+',
        ],
        [
            'name' => 'EscapeCodeHex4',
            'match' => '(?i)[a-f0-9]{4}+',
        ],
        [
            'name' => 'EscapeCodeHex',
            'match' => '(?i)[a-f0-9]++',
        ],
        [
            'name' => 'EscapeCodeSingleLetter',
            'match' => '(?i)(?![xu])[a-z]',
        ],
        [
            'name' => 'EscapeCodePunctuation',
            'match' => '[-!"#$%&\'()*+,.\\/:;<=>?@\\[\\]\\\\^_`{|}~]',
        ],
        [
            'name' => 'StringLiteralTextSafe',
            'match' => '[^\'"$\\\\]++',
        ],
    ],
];
