Lorem { "ipsum \$ \" \' \\ \${ $\{ ,\n\x21\n\u0021 \u{21}\n\u00B0 \U00b0 \u{B0} \U{0B0}\n.\u{1F600}." } ipsum
---- CODE ----
('Lorem ' . ($runtime::htmlEncode(('ipsum $ " \' \\ ${ ' . '$' . '{ ,
!
! !
° ° ° °
.😀.'))) . ' ipsum')
---- RESULT ----
Lorem ipsum $ &quot; &#039; \ ${ ${ ,
!
! !
° ° ° °
.😀. ipsum
