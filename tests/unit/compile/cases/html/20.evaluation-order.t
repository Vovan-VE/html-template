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
('a
'.($runtime->createComponent('Step',[],function($runtime){return ('
    b
    '.($runtime->createComponent('Step',[],function($runtime){return ('
        c
        '.($runtime->createComponent('Step')).'
        d
        '.($runtime->createComponent('Step')).'
        e
    ');})).'
    f
    '.($runtime->createComponent('Step')).'
    g
    '.($runtime->createComponent('Step',[],function($runtime){return ('
        h
        '.($runtime->createComponent('Step')).'
        i
        '.($runtime->createComponent('Step')).'
        j
    ');})).'
    k
    '.($runtime->createComponent('Step')).'
    l
');})).'
m
'.($runtime->createComponent('Step')).'
o
'.($runtime->createComponent('Step',[],function($runtime){return ('
    p
    '.($runtime->createComponent('Step')).'
    q
');})).'
r')
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
