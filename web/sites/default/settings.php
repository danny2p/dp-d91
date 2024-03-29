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

$databases['second']['default'] = array (
  'database' => 'pantheon',
  'username' => 'pantheon',
  'password' => 'UHvFKFoJqxYirw9zy3Pj3AUUHMzDas15',
  'prefix' => '',
  'host' => 'dbserver.dev.a8efb50e-a307-45b2-a204-10f9855867c9.drush.in',
  'port' => '16501',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);


if (defined(
 'PANTHEON_ENVIRONMENT'
) && !\Drupal\Core\Installer\InstallerKernel::installationAttempted(
) && extension_loaded('redis')) {
 // Set Redis as the default backend for any cache bin not otherwise specified.
 $settings['cache']['default'] = 'cache.backend.redis';
 
 //phpredis is built into the Pantheon application container.
 $settings['redis.connection']['interface'] = 'PhpRedis';

 // These are dynamic variables handled by Pantheon.
 $settings['redis.connection']['host'] = $_ENV['CACHE_HOST'];
 $settings['redis.connection']['port'] = $_ENV['CACHE_PORT'];
 $settings['redis.connection']['password'] = $_ENV['CACHE_PASSWORD'];

 $settings['redis_compress_length'] = 100;
 $settings['redis_compress_level'] = 1;

 $settings['cache_prefix']['default'] = 'pantheon-redis';

 $settings['cache']['bins']['form'] = 'cache.backend.database'; // Use the database for forms

 // Apply changes to the container configuration to make better use of Redis.
 // This includes using Redis for the lock and flood control systems, as well
 // as the cache tag checksum. Alternatively, copy the contents of that file
 // to your project-specific services.yml file, modify as appropriate, and
 // remove this line.
 $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';

 // Allow the services to work before the Redis module itself is enabled.
 $settings['container_yamls'][] = 'modules/contrib/redis/redis.services.yml';

 // Manually add the classloader path, this is required for the container
 // cache bin definition below.
 $class_loader->addPsr4('Drupal\\redis\\', 'modules/contrib/redis/src'); 

 // Use redis for container cache.
 // The container cache is used to load the container definition itself, and
 // thus any configuration stored in the container itself is not available
 // yet. These lines force the container cache to use Redis rather than the
 // default SQL cache.
 $settings['bootstrap_container_definition'] = [
   'parameters' => [],
   'services' => [
     'redis.factory' => [
       'class' => 'Drupal\redis\ClientFactory',
     ],
     'cache.backend.redis' => [
       'class' => 'Drupal\redis\Cache\CacheBackendFactory',
       'arguments' => [
         '@redis.factory',
         '@cache_tags_provider.container',
         '@serialization.phpserialize',
       ],
     ],
     'cache.container' => [
       'class' => '\Drupal\redis\Cache\PhpRedis',
       'factory' => ['@cache.backend.redis', 'get'],
       'arguments' => ['container'],
     ],
     'cache_tags_provider.container' => [
       'class' => 'Drupal\redis\Cache\RedisCacheTagsChecksum',
       'arguments' => ['@redis.factory'],
     ],
     'serialization.phpserialize' => [
       'class' => 'Drupal\Component\Serialization\PhpSerialize',
     ],
   ],
 ];
}