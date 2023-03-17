<?php

header('Content-Type: application/json; charset=utf-8');

/**
 * Retrieve DB Credentials
 */
function getCacheServerCredentials()
{
  if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
    if (function_exists('pantheon_curl')) {
      $url = 'https://api.live.getpantheon.com/sites/self/bindings?type=cacheserver';
      $req = pantheon_curl($url, NULL, 8443);
      $bindings = json_decode($req['body'], TRUE);
      print $req['body'];
     /*
      print "<pre>";
      print_r($bindings);
      print "</pre>";
    */
      $cache_found = FALSE;
      $cache_array = [];
/*
      foreach ($bindings as $binding) {
        if (!empty($binding['environment'])) {
          //  Extract DB credentials
          $cache_array[$binding['environment']] = [
            'PRIVATE_IP' => $binding['private_ip'],
            'DB_INTERNAL_USER' => $binding['username'],
            'DB_USER' => 'pantheon',
            'DB_PASSWORD' => $binding['password'],
            'DB_HOST' => "{$binding['type']}.{$binding['environment']}.{$binding['site']}.drush.in",
            'DB_HOST_INTERNAL' => $binding['host'],
            'DB_PORT' => $binding['port'],
            'DB_NAME' => 'pantheon',
            'ENV' => $binding['environment'],
          ];
            $cache_found = TRUE;
        }
      }

      // No database credentials found.
      if (!$db_fcache_foundound) {
        die("No cacheserver credentials found.");
      }
*/
    }


    #print json_encode($db_array);
  } else {
      // local DB creds here
      die("This script will not run outside of the Pantheon Platform");
  }
}



getCacheServerCredentials();

?>