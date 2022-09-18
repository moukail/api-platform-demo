#!/usr/bin/env bash

docker-compose down
docker volume rm -f mytv_web_certs
docker volume rm -f mytv_web_database
docker volume rm -f mytv_web_vendor

#docker run -it --rm --name certbot -v "/etc/letsencrypt:/etc/letsencrypt" -v "/var/lib/letsencrypt:/var/lib/letsencrypt" certbot/certbot certonly
