#!/bin/sh

CURRENT_DIR=$(dirname $(readlink -fn "$0"))
ROOT_DIR=$(dirname $CURRENT_DIR)

# BUILD ENV
TMP_DIR=/tmp/docker-build
GIT_REPO=https://github.com/juliushaertl/nextcloud-docker-dev
BUILD_CONTAINER_NAME=docker_local_tmp_$RANDOM$RANDOM
REPOSITORY="docker.local/dev/nextcloud-dev-83"
REPOSITORY_BUILD="docker.local/build/nextcloud-dev-83"
TAG="latest"

#RUN ENV
NC_CONTAINER="$REPOSITORY:latest"
RUN_CONTAINER_NAME="nc_dev_local"
APP_MOUNT_SOURCE="$ROOT_DIR"
APP_MOUNT_TARGET="/var/www/html/apps-extra/churchtoolsintegration"
APP_PORT_WEB="8080:80"