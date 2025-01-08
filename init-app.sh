#!/bin/sh

set -e

cd /app

mkdir -p var/cache var/log
chmod -R 777 var/cache var/log

if [ ! -f .env.local ]; then
  echo "DATABASE_URL=mysql://admin:password@mysql:3306/app" >.env.local
fi

composer install

migrationsCount=$(ls ./migrations -1 | wc -l)
if [ -d migrations ] && [ $migrationsCount -gt 0 ]; then
  php bin/console doctrine:migrations:migrate --no-interaction
fi
