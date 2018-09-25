Lorem { "ipsum ${ dolor } sit ${ "amet" } consectepture ${ "adipisicing ${ elit } sed" } do" } eiusmod
---- CODE ----
('Lorem ' . ($runtime::htmlEncode(('ipsum ' . ($runtime->param('dolor')) . ' sit ' . 'amet' . ' consectepture ' . ('adipisicing ' . ($runtime->param('elit')) . ' sed') . ' do'))) . ' eiusmod')
---- RESULT ----
Lorem ipsum [value of &amp;dolor] sit amet consectepture adipisicing [value of &amp;elit] sed do eiusmod
