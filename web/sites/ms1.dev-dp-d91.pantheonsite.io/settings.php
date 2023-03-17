<?php

$site_name = "ms1.dev-dp-d91.pantheonsite.io";
$second_db_env = "extradb";
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
include "/code/web/sites/default/settings.pantheon.php";

/**
 * Skipping permissions hardening will make scaffolding
 * work better, but will also raise a warning when you
 * install Drupal.
 *
 * https://www.drupal.org/project/drupal/issues/3091285
 */
// $settings['skip_permissions_hardening'] = TRUE;

// get cacheserver from secondary env

// get db from secondary env

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  if (function_exists('pantheon_curl')) {
    $url = 'https://api.live.getpantheon.com/sites/self/bindings?type=dbserver';
    $req = pantheon_curl($url, NULL, 8443);
    $bindings = json_decode($req['body'], TRUE);
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


$databases['default']['default'] = array (
  'database' => 'pantheon',
  'username' => $db_array[$second_db_env]['DB_INTERNAL_USER'],
  'password' => $db_array[$second_db_env]['DB_PASSWORD'],
  'prefix' => '',
  'host' => $db_array[$second_db_env]['PRIVATE_IP'],
  'port' => $db_array[$second_db_env]['DB_PORT'],
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  $settings['hash_salt'] = $_ENV['DRUPAL_HASH_SALT'];
}

// Configure Redis

if (defined(
  'PANTHEON_ENVIRONMENT'
 ) && !\Drupal\Core\Installer\InstallerKernel::installationAttempted(
 ) && extension_loaded('redis')) {
  // Set Redis as the default backend for any cache bin not otherwise specified.
  $settings['cache']['default'] = 'cache.backend.redis';

  //phpredis is built into the Pantheon application container.
  $settings['redis.connection']['interface'] = 'PhpRedis';
 
  // These are dynamic variables handled by Pantheon.
  $settings['redis.connection']['host'] = "10.73.0.186";
  $settings['redis.connection']['port'] = "12029";
  $settings['redis.connection']['password'] = "f1838f6991db49439f3d39c12f586428";
 
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