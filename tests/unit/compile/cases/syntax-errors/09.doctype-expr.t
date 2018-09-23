<!DOCTYPE foo "bar { $var } lol"> text
---- THROW% ----
Unexpected "{"; expected: '"', <HtmlFlowText> or <HtmlQQTextPartSpec> near `{ $var } lol"> text` in `%s` at line 1
