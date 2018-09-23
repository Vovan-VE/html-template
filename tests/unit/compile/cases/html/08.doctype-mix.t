<!DOCTYPE html "foo &amp; ' &quot; bar" 'lorem &amp; &#039; " bar' "" name ''>
---- CODE ----
'<!DOCTYPE html "foo &amp; &#039; &quot; bar" "lorem &amp; &#039; &quot; bar" "" name "">'
---- RESULT ----
<!DOCTYPE html "foo &amp; &#039; &quot; bar" "lorem &amp; &#039; &quot; bar" "" name "">
