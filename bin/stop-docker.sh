#!/bin/sh
CURRENT_DIR=$(dirname $(readlink -fn "$0"))
source $CURRENT_DIR/env.sh

docker stop $RUN_CONTAINER_NAME