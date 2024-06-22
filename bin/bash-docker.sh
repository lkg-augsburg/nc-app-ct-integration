#!/bin/sh

CURRENT_DIR=$(dirname $(readlink -fn "$0"))
source $CURRENT_DIR/env.sh

docker exec -ti -e "TERM=xterm-256color" $RUN_CONTAINER_NAME /bin/bash
