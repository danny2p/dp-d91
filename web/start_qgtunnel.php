<?php
putenv("QUOTAGUARDSTATIC_URL=https://o7b7e0w6ktj2rd:evflea1kz6t363bg8pw288jxme@us-east-shield-04.quotaguard.com:9294");
print "starting qgtunnel \n";
shell_exec("../bin/qgtunnel &>/dev/null &");
shell_exec("disown");
print "qgtunnel started";
?>