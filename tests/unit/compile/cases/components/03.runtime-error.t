<Step>
    <Step>
        <TestComponent>
            <Failure message="Lorem ipsum dolor"/>
        </TestComponent>
    </Step>
</Step>
---- CODE ----
($runtime->createComponent('Step',[],function($runtime){return [($runtime->createComponent('Step',[],function($runtime){return [($runtime->createComponent('TestComponent',[],function($runtime){return [($runtime->createComponent('Failure',['message'=>'Lorem ipsum dolor']))];}))];}))];}))
---- THROW ----
An error occurred while executing template
-- prev --
An error from component `Step` > `Step` > `TestComponent` > `Failure`
-- prev --
Component runtime error
-- prev --
Lorem ipsum dolor
