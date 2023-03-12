<?php

drupal_page_is_cacheable(FALSE);
    $conf['page_cache_maximum_age'] = 0;

    
#setting site name variable for local use
#creating a new multisite instance requires you to change this variable
#Be sure to change this to the same identifier that
#you use under ‘services’ in your .lando.yml file.
$site_name = "ms1.dev-dp-d91.pantheonsite.io";

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

$databases['default']['default'] = array (
  'database' => 'pantheon',
  'username' => 'a7c8f12be8cd40ff9e579cf81106129c',
  'password' => 'UHvFKFoJqxYirw9zy3Pj3AUUHMzDas15',
  'prefix' => '',
  'host' => 'dbserver.dev.a8efb50e-a307-45b2-a204-10f9855867c9.drush.in',
  'port' => '16501',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

if (isset($_ENV['PANTHEON_ENVIRONMENT'])) {
  $settings['hash_salt'] = $_ENV['DRUPAL_HASH_SALT'];
}
