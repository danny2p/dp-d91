<?php
header('Content-Type: application/json; charset=utf-8');

/**
 * Get binding data
 */
if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  if (function_exists('pantheon_curl')) {
    $url = 'https://api.live.getpantheon.com/sites/self/bindings?type=appserver';
    $req = pantheon_curl($url, NULL, 8443);
    $bindings = json_decode($req['body'], TRUE);
    print $req['body'];
  }
}
