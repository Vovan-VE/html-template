Lorem { "ipsum'dolor\"sit\\amet\n&<consectepture>\x21adipisicing$elit\$sed\$" } do
---- CODE ----
('Lorem ' . ($runtime::htmlEncode(('ipsum\'dolor"sit\\amet
&<consectepture>!adipisicing' . '$' . 'elit$sed$'))) . ' do')
---- RESULT ----
Lorem ipsum&#039;dolor&quot;sit\amet
&amp;&lt;consectepture&gt;!adipisicing$elit$sed$ do
