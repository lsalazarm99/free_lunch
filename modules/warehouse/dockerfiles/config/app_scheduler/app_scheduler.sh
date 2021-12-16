#!/bin/bash

while true
do
  php /srv/artisan schedule:run --verbose --no-interaction &
  sleep 60
done
