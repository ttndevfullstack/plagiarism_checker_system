#!/bin/bash
# turn on bash's job control
set -m
# Start the "main" PHP process and put it in the background
php-fpm &
# Start the helper crond process
crond
# now we bring the primary process back into the foreground
fg %1
