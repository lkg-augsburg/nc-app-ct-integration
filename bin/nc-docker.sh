#!/bin/sh

ROOT_DIR=$(dirname $(dirname $(readlink -fn "$0")))

docker run \
  --rm \
  -d \
  --name docker_local \
  -p 8080:80 \
  -v $ROOT_DIR/:/var/www/html/apps-extra/churchtoolsintegration \
  ghcr.io/juliushaertl/nextcloud-dev-php83:latest