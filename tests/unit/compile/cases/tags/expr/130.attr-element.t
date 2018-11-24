<div title={ <span>Lorem<br/>ipsum " dolor</span> } />{'\n'}
<div title={ <span>{ var }</span> } />
---- CODE ----
('<div title="&lt;span&gt;Lorem&lt;br/&gt;ipsum &quot; dolor&lt;/span&gt;"/>
'.($runtime::createElement('div',['title'=>('<span>'.($runtime::htmlEncode(($runtime::toString(($runtime->param('var')))))).'</span>')])))
---- RESULT ----
<div title="&lt;span&gt;Lorem&lt;br/&gt;ipsum &quot; dolor&lt;/span&gt;"/>
<div title="&lt;span&gt;[value of &amp;amp;var]&lt;/span&gt;"/>
