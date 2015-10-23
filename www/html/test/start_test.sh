#!/bin/sh

cd /var/www/html/

vendor/phpunit/phpunit/phpunit \
  --configuration test/phpunit.xml \
  --bootstrap bootstrap.php \
  --color=always --verbose --testdox \
  models/

vendor/phpunit/phpunit/phpunit \
  --configuration test/phpunit.xml \
  --bootstrap bootstrap.php \
  --color=always --verbose --testdox \
  core/

#vendor/phpunit/phpunit/phpunit \
#  --configuration test/phpunit.xml \
#  --bootstrap bootstrap.php \
#  --color=always --verbose \
#  controllers/

# --testdox
# --bootstrap

# UserRepositoryTest.php
