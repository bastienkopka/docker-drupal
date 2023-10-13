<?php

declare(strict_types = 1);

// phpcs:ignoreFile

// Database settings.
$databases = [];
$databases['default']['default'] = [
  'database' => $_ENV['MYSQL_DATABASE'],
  'username' => $_ENV['MYSQL_USER'],
  'password' => $_ENV['MYSQL_PASSWORD'],
  'prefix' => '',
  'host' => $_ENV['MYSQL_HOST'],
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => $_ENV['MYSQL_DRIVER'],
  'init_commands' => [
    'isolation_level' => 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED',
  ],
];

// Hash salt.
// drush php-eval 'echo \Drupal\Component\Utility\Crypt::randomBytesBase64(55) . "\n";'
// var_dump(Drupal\Component\Utility\Crypt::randomBytesBase64(55));
$settings['hash_salt'] = $_ENV['DRUPAL_HASH_SALT'];

// Access control for update.php script.
$settings['update_free_access'] = FALSE;
// Allows users to download code into site.
$settings['allow_authorize_operations'] = FALSE;

// Location of configuration files.
$settings['config_sync_directory'] = $app_root . '/../conf/drupal/default/sync';
$config['config_split.config_split.dev']['status'] = TRUE;

// Prevent deletion of orphan files.
// @todo Remove this line when the following issues will be fixed:
// - https://www.drupal.org/node/2801777
// - https://www.drupal.org/node/2708411
// - https://www.drupal.org/node/1239558
// - https://www.drupal.org/node/2666700
// - https://www.drupal.org/node/2810355
$config['system.file']['temporary_maximum_age'] = 0;

// Errors.
$config['system.logging']['error_level'] = 'hide';

// Trusted host patterns.
$settings['trusted_host_patterns'] = [
  '^localhost$',
  '^127\.0\.0\.1$',
  '^web$',
  '^' .  $_ENV['DRUPAL_DOMAIN'] . '$',
];

// Translations.
$config['locale.settings']['translation']['path'] = 'translations/contrib';
$config['locale.settings']['translation']['use_source'] = 'local';

// Redis.
if ($_ENV['DRUPAL_HAS_REDIS'] === 'true') {
  $settings['redis.connection']['interface'] = $_ENV['DRUPAL_REDIS_INTERFACE'];
  $settings['redis.connection']['host'] = $_ENV['DRUPAL_REDIS_HOST'];
  $settings['redis.connection']['port'] = $_ENV['DRUPAL_REDIS_PORT'];
  $settings['redis.connection']['base'] = $_ENV['DRUPAL_REDIS_BASE'];
  $settings['redis_compress_length'] = $_ENV['DRUPAL_REDIS_COMPRESS_LENGTH'];
  $settings['redis_compress_level'] = $_ENV['DRUPAL_REDIS_COMPRESS_LEVEL'];

  $settings['cache_prefix'] = $_ENV['DRUPAL_CACHE_PREFIX'];
  $settings['cache']['default'] = $_ENV['DRUPAL_CACHE_NAME'];

  $settings['container_yamls'][] = $app_root . '/modules/contrib/redis/exemple.services.yml';
  $settings['container_yamls'][] = $app_root . '/modules/contrib/redis/redis.services.yml';
}

// Load services.
if ($_ENV['DRUPAL_ENVIRONMENT'] === 'dev') {
  $settings['container_yamls'][] = $app_root . '/../conf/drupal/default/development.services.yml';
  $config['system.logging']['error_level'] = 'verbose';

  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;

  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
  $settings['cache']['bins']['page'] = 'cache.backend.null';
  $settings['cache']['bins']['render'] = 'cache.backend.null';

  $settings['skip_permissions_hardening'] = TRUE;
  $settings['rebuild_access'] = TRUE;
  $settings['extension_discovery_scan_tests'] = TRUE;
}
else {
  $settings['container_yamls'][] = $app_root . '/../conf/drupal/default/services.yml';
  $config['system.logging']['error_level'] = 'hide';
}
