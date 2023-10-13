#!/bin/bash

# Install dependencies.
composer install

# Init settings file with permissions.
cp conf/drupal/default/settings.php app/sites/default/settings.php
chmod 775 app/sites/
chmod 775 app/sites/default
chmod 666 app/sites/default/settings.php
chmod 777 -R app/sites/default/files

# Install drupal.
if [ "${DRUPAL_HAS_EXPORTED_CONFIG}" = "true" ]; then
  ./vendor/bin/drush site:install \
    --site-name="${DRUPAL_SITE_NAME}" \
    --site-mail="${DRUPAL_SITE_EMAIL}" \
    --account-mail="${DRUPAL_SITE_EMAIL}" \
    --account-name="${DRUPAL_SITE_USER}" \
		--account-pass="${DRUPAL_SITE_PASSWORD}" \
		--locale="${DRUPAL_SITE_LANGUAGE}" \
		--existing-config \
		-y
else
  ./vendor/bin/drush site:install minimal \
    --site-name="${DRUPAL_SITE_NAME}" \
    --site-mail="${DRUPAL_SITE_EMAIL}" \
    --account-mail="${DRUPAL_SITE_EMAIL}" \
    --account-name="${DRUPAL_SITE_USER}" \
		--account-pass="${DRUPAL_SITE_PASSWORD}" \
		--locale="${DRUPAL_SITE_LANGUAGE}" \
		-y
fi

# Change permissions to access to files folder.
chmod 777 -R app/sites/default/files
