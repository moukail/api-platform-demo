#!/usr/bin/env bash

sudo apt-get -y install libnss3-tools

source ./down.sh

#echo "admin" | docker secret create jenkins-user -
#echo "admin" | docker secret create jenkins-pass -
#docker stack deploy
#docker network create datamatch

docker-compose up -d --build

# on ubuntu add CA cert to Chrome https://chromium.googlesource.com/chromium/src/+/refs/heads/lkgr/docs/linux/cert_management.md
sudo certutil -d sql:$HOME/.pki/nssdb -A -t TC -n "Moukafih" -i /var/lib/docker/volumes/mytv_certs/_data/myCA.pem
# on Mac add CA cert to Chrome\Safari https://stackoverflow.com/questions/7580508/getting-chrome-to-accept-self-signed-localhost-certificate/46243968
#sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain ./certs/myCA.pem

# Add a personal certificate and private key for SSL client authentication
#pk12util -d sql:$HOME/.pki/nssdb -i ./certs/keyStore.p12
#certutil -L -d sql:$HOME/.pki/nssdb

#docker-compose run web bash
