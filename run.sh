#!/bin/bash

set -e

watch -n 5 "curl http://127.0.0.1" > /dev/null 2>&1 &

docker-php-entrypoint apache2-foreground
