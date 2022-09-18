#!/usr/bin/env bash

docker run --rm -v $(pwd):/app -w /app php:8.1-cli-alpine3.16 php index.php