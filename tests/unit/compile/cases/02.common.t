<a href={ link } title={ "Foo bar: ${ title }" }>
    <span id=foobar class='it parses html'>
        { description }
    </span>
    <span>
        {# comment #}
    </span>
</a>
---- CODE ----
($runtime::createElement('a',['href'=>($runtime->param('link')),'title'=>('Foo bar: '.($runtime::toString(($runtime->param('title')))))],[($runtime::createElement('span',['id'=>'foobar','class'=>'it parses html'],[($runtime::htmlEncode(($runtime::toString(($runtime->param('description'))))))])),'<span></span>']))
---- RESULT ----
<a href="[value of &amp;link]" title="Foo bar: [value of &amp;title]"><span id="foobar" class="it parses html">[value of &amp;description]</span><span></span></a>
