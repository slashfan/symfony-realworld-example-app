#!/usr/bin/env bash
set -x

APIURL=${APIURL:-http://nginx/api}
USERNAME=${USERNAME:-u$(date +%s)}
EMAIL=${EMAIL:-$USERNAME@mail.com}
PASSWORD=${PASSWORD:-password}

npm_config_yes=true npx newman run spec/conduit.postman_collection.json \
  --delay-request 50 \
  --global-var "APIURL=$APIURL" \
  --global-var "USERNAME=$USERNAME" \
  --global-var "EMAIL=$EMAIL" \
  --global-var "PASSWORD=$PASSWORD"
