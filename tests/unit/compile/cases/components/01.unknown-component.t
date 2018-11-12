<div>
    lorem ipsum
    <TestComponent>
        <div>
            <TestComponent>
                <Step>
                    foo
                    <Step>
                        <FooBar bark="42"/>
                    </Step>
                </Step>
            </TestComponent>
        </div>
    </TestComponent>
</div>
---- CODE ----
('<div>lorem ipsum'.($runtime->createComponent('TestComponent',[],function($runtime){return [('<div>'.($runtime->createComponent('TestComponent',[],function($runtime){return [($runtime->createComponent('Step',[],function($runtime){return ['foo',($runtime->createComponent('Step',[],function($runtime){return [($runtime->createComponent('FooBar',['bark'=>'42']))];}))];}))];})).'</div>')];})).'</div>')
---- THROW ----
An error occurred while executing template
-- prev --
An error from component `TestComponent` > `TestComponent` > `Step` > `Step` > `FooBar`
-- prev --
Unknown component
