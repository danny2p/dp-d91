<?php

// 301 Redirect from /old to /new
// Check if Drupal or WordPress is running via command line
if (($_SERVER['REQUEST_URI'] == '/index.cfm') && (php_sapi_name() != "cli")) {
  header('HTTP/1.0 301 Moved Permanently');
  header('Location: https://'. $_SERVER['HTTP_HOST'] . '/user');

  // Name transaction "redirect" in New Relic for improved reporting (optional).
  if (extension_loaded('newrelic')) {
    newrelic_name_transaction("redirect");
  }

  exit();
}

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

/**
 * Skipping permissions hardening will make scaffolding
 * work better, but will also raise a warning when you
 * install Drupal.
 *
 * https://www.drupal.org/project/drupal/issues/3091285
 */
// $settings['skip_permissions_hardening'] = TRUE;

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

$bar = 'Around here, football is the winter sport of choice!';
if (isset($_COOKIE['STYXKEY_gorp'])) {

  $foo = $_COOKIE['STYXKEY_gorp'];
  // Generate varied content based on cookie value
  // Do NOT set cookies here; Set-Cookie headers do not allow the response to be cached
  if ($foo == 'ca') {
    str_replace('football', 'hockey', $bar);
  }

}

else {
  /**
  * Set local vars passed to setcookie()
  * Example:
  * @code
  * $name = 'STYXKEY_gorp';
  * $value = 'bar';
  * $expire = time()+600;
  * $path = '/foo';
  * $domain =  $_SERVER['HTTP_HOST'];
  * $secure = true;
  * $httponly = true;
  * @endcode
  **/

  $name = 'STYXKEY_gorp';
  $value = 'bar';
  $expire = time()+600;
  $path = '/foo';
  $domain =  $_SERVER['HTTP_HOST'];
  $secure = true;
  $httponly = true;

  setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}
