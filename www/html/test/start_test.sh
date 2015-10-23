#!/bin/sh

cd /var/www/html/

vendor/phpunit/phpunit/phpunit \
  --configuration test/phpunit.xml \
  --bootstrap bootstrap.php \
  --color=always  \
  models/

vendor/phpunit/phpunit/phpunit \
  --configuration test/phpunit.xml \
  --bootstrap bootstrap.php \
  --color=always --verbose \
  core/

# --testdox
# --bootstrap

# UserRepositoryTest.php
