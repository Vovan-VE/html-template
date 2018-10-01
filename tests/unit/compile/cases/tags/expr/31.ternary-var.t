<a>{ lorem ? ipsum : dolor }</a>
---- CODE ----
($runtime::createElement('a',[],[($runtime::htmlEncode(((($runtime->param('lorem')))?(($runtime->param('ipsum'))):(($runtime->param('dolor'))))))]))
---- RESULT ----
<a>[value of &amp;ipsum]</a>
