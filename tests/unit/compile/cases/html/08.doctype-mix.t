<!DOCTYPE html "foo &amp; ' &quot; bar" 'lorem &amp; &#039; " bar'>
---- CODE ----
'<!DOCTYPE html "foo &amp; &#039; &quot; bar" "lorem &amp; &#039; &quot; bar">'
---- RESULT ----
<!DOCTYPE html "foo &amp; &#039; &quot; bar" "lorem &amp; &#039; &quot; bar">
