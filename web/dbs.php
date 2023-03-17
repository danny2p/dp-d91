<?php
header('Content-Type: application/json; charset=utf-8');

/**
 * Retrieve DB Credentials
 */
function getDatabaseCredentials()
{
  if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
    if (function_exists('pantheon_curl')) {
      $url = 'https://api.live.getpantheon.com/sites/self/bindings?type=dbserver';
      $req = pantheon_curl($url, NULL, 8443);
      $bindings = json_decode($req['body'], TRUE);
      print $req['body'];
     /*
      print "<pre>";
      print_r($bindings);
      print "</pre>";
    */
      $db_found = FALSE;
      $db_array = [];

      foreach ($bindings as $binding) {
        if (!empty($binding['environment'])) {
          //  Extract DB credentials
          $db_array[$binding['environment']] = [
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
            $db_found = TRUE;
        }
      }

      // No database credentials found.
      if (!$db_found) {
        die("No database credentials found.");
      }

    }
    #print json_encode($db_array);
  } else {
      // local DB creds here
      die("This script will not run outside of the Pantheon Platform");
  }
}



getDatabaseCredentials();

?>