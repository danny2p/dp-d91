<?php
$start = microtime(true);

while(1) {
    $time_elapsed_secs = microtime(true) - $start;
    print "In loop - $time_elapsed_secs \n";
}
?>