Lorem { <img src={ url } /> } ipsum
---- CODE ----
('Lorem '.($runtime::createElement('img',['src'=>($runtime->param('url'))])).' ipsum')
---- RESULT ----
Lorem <img src="[value of &amp;url]"/> ipsum
