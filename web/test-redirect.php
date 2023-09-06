<?php 

if (isset($_ENV['PANTHEON_ENVIRONMENT']) && php_sapi_name() != 'cli') {
  if ($_ENV['PANTHEON_ENVIRONMENT'] === 'dev') {
    $primary_domain = 'waf-protected-domain.com';
  }
  else {
    $primary_domain = $_SERVER['HTTP_HOST'];
  }

  $requires_redirect = false;
  
  // Ensure the site is being served from the primary domain.
  if ($_SERVER['HTTP_HOST'] != $primary_domain) {
    $requires_redirect = true;
  }

  if ($requires_redirect === true) {
    echo "WILL REDIRECT";
  } else {
    echo "WILL NOT REDIRECT";
  }
}

?>