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
'.($runtime->createComponent('Step',[],['
    b
    ',($runtime->createComponent('Step',[],['
        c
        ',($runtime->createComponent('Step')),'
        d
        ',($runtime->createComponent('Step')),'
        e
    '])),'
    f
    ',($runtime->createComponent('Step')),'
    g
    ',($runtime->createComponent('Step',[],['
        h
        ',($runtime->createComponent('Step')),'
        i
        ',($runtime->createComponent('Step')),'
        j
    '])),'
    k
    ',($runtime->createComponent('Step')),'
    l
'])).'
m
'.($runtime->createComponent('Step')).'
o
'.($runtime->createComponent('Step',[],['
    p
    ',($runtime->createComponent('Step')),'
    q
'])).'
r')
---- RESULT ----
a
<!-- step: 9 -->
    b
    <!-- step: 3 -->
        c
        <!-- step: 1 /-->
        d
        <!-- step: 2 /-->
        e
    <!-- /step: 3 -->
    f
    <!-- step: 4 /-->
    g
    <!-- step: 7 -->
        h
        <!-- step: 5 /-->
        i
        <!-- step: 6 /-->
        j
    <!-- /step: 7 -->
    k
    <!-- step: 8 /-->
    l
<!-- /step: 9 -->
m
<!-- step: 10 /-->
o
<!-- step: 12 -->
    p
    <!-- step: 11 /-->
    q
<!-- /step: 12 -->
r
