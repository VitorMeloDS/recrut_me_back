#!/bin/sh

if [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for Postgres..."

    while ! nc -z $DB_HOST $DB_PORT; do
        sleep 1
    done

    echo "Postgres started"
fi

php artisan migrate && php artisan db:seed

exec "$@"
