<?php
  if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
    print "Environment: ";
    print $_ENV['PANTHEON_ENVIRONMENT'];
  } else {
    print "PANTHEON_ENVIRONMENT is not set.";
  }
?>