<foo true={true} false={false} null={null}/>
<true>{true}</true>
<false>{false}</false>
<null>{null}</null>
---- CODE ----
'<foo true/><true></true><false></false><null></null>'
---- RESULT ----
<foo true/><true></true><false></false><null></null>
