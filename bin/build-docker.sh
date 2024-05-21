#!/bin/sh
CURRENT_DIR=$(dirname $(readlink -fn "$0"))
source $CURRENT_DIR/env.sh

rm -rf $TMP_DIR

mkdir $TMP_DIR && cd $_

echo "Clone nextcloud dev repository for local build"

git clone $GIT_REPO

cd nextcloud-docker-dev/docker

echo "Start building docker container"

docker build -t $REPOSITORY_BUILD:latest -f php83/Dockerfile .

echo "Start temporary docker container to finish setup"

docker run \
  --rm \
  -d \
  --name "$BUILD_CONTAINER_NAME" \
  $REPOSITORY_BUILD

echo "Waiting for Nextcloud setup finished"

while true; do
    STATUS_JSON=$(docker exec $BUILD_CONTAINER_NAME occ status --output=json_pretty)
    CURRENT_INSTALLED=$(echo $STATUS_JSON | jq '.installed')

    if [ "$CURRENT_INSTALLED" == "true" ]; then
        echo "Nextcloud installed"
        break;
    fi
    
    sleep 5
done

echo "Create new image with initialized nextcloud setup"

docker commit $BUILD_CONTAINER_NAME $REPOSITORY:$TAG

# Erfolgsnachricht ausgeben
echo "Docker container committed to $REPOSITORY:$TAG"

echo "Clean up"

docker stop $BUILD_CONTAINER_NAME

rm -rf $TMP_DIR