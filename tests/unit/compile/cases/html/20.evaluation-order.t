{}a{'\n'}
{}<Step>{'\n'}
{}    b{'\n'}
{}    <Step>{'\n'}
{}        c{'\n'}
{}        <Step/>{'\n'}
{}        d{'\n'}
{}        <Step/>{'\n'}
{}        e{'\n'}
{}    </Step>{'\n'}
{}    f{'\n'}
{}    <Step/>{'\n'}
{}    g{'\n'}
{}    <Step>{'\n'}
{}        h{'\n'}
{}        <Step/>{'\n'}
{}        i{'\n'}
{}        <Step/>{'\n'}
{}        j{'\n'}
{}    </Step>{'\n'}
{}    k{'\n'}
{}    <Step/>{'\n'}
{}    l{'\n'}
{}</Step>{'\n'}
{}m{'\n'}
{}<Step/>{'\n'}
{}o{'\n'}
{}<Step>{'\n'}
{}    p{'\n'}
{}    <Step/>{'\n'}
{}    q{'\n'}
{}</Step>{'\n'}
{}r
---- CODE ----
('a' . ($runtime::htmlEncode('
')) . ($runtime->createComponent('Step',[],function()use($runtime){return [($runtime::htmlEncode('
')),'    b',($runtime::htmlEncode('
')),'    ',($runtime->createComponent('Step',[],function()use($runtime){return [($runtime::htmlEncode('
')),'        c',($runtime::htmlEncode('
')),'        ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'        d',($runtime::htmlEncode('
')),'        ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'        e',($runtime::htmlEncode('
')),'    '];})),($runtime::htmlEncode('
')),'    f',($runtime::htmlEncode('
')),'    ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'    g',($runtime::htmlEncode('
')),'    ',($runtime->createComponent('Step',[],function()use($runtime){return [($runtime::htmlEncode('
')),'        h',($runtime::htmlEncode('
')),'        ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'        i',($runtime::htmlEncode('
')),'        ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'        j',($runtime::htmlEncode('
')),'    '];})),($runtime::htmlEncode('
')),'    k',($runtime::htmlEncode('
')),'    ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'    l',($runtime::htmlEncode('
'))];})) . ($runtime::htmlEncode('
')) . 'm' . ($runtime::htmlEncode('
')) . ($runtime->createComponent('Step')) . ($runtime::htmlEncode('
')) . 'o' . ($runtime::htmlEncode('
')) . ($runtime->createComponent('Step',[],function()use($runtime){return [($runtime::htmlEncode('
')),'    p',($runtime::htmlEncode('
')),'    ',($runtime->createComponent('Step')),($runtime::htmlEncode('
')),'    q',($runtime::htmlEncode('
'))];})) . ($runtime::htmlEncode('
')) . 'r')
---- RESULT ----
a
<!-- step: 1 -->
    b
    <!-- step: 2 -->
        c
        <!-- step: 3 /-->
        d
        <!-- step: 4 /-->
        e
    <!-- /step: 2 -->
    f
    <!-- step: 5 /-->
    g
    <!-- step: 6 -->
        h
        <!-- step: 7 /-->
        i
        <!-- step: 8 /-->
        j
    <!-- /step: 6 -->
    k
    <!-- step: 9 /-->
    l
<!-- /step: 1 -->
m
<!-- step: 10 /-->
o
<!-- step: 11 -->
    p
    <!-- step: 12 /-->
    q
<!-- /step: 11 -->
r
