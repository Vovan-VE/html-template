<foo
    id="bar"
    title="Lorem ipsum
dolor sit amet
    consectepture
        adipisicing"
/>
---- CODE ----
($runtime::createElement('foo',['id'=>'bar','title'=>'Lorem ipsum
dolor sit amet
    consectepture
        adipisicing']))
---- RESULT ----
<foo id="bar" title="Lorem ipsum
dolor sit amet
    consectepture
        adipisicing"/>
