#!/bin/sh
CURRENT_DIR=$(dirname $(readlink -fn "$0"))
source $CURRENT_DIR/env.sh

DATA_MOUNT_SOURCE=$ROOT_DIR"/tmp/data"
DATA_MOUNT_TARGET="/var/www/html/data"
NC_APP_ID="churchtoolsintegration"
ERROR_FILE=$ROOT_DIR"/tmp/error"


kill_instance(){

  docker rm -f $RUN_CONTAINER_NAME
}

function prepare(){

  kill_instance

  rm -rf $DATA_MOUNT_SOURCE

  while true; do
    if [ ! -d "$DATA_MOUNT_SOURCE" ]; then
      break
    fi
    sleep 1
  done

  mkdir -p $DATA_MOUNT_SOURCE
}

creat_instance(){

  docker run \
  --rm \
  -d \
  --name $RUN_CONTAINER_NAME \
  -p $APP_PORT_WEB \
  --volume $APP_MOUNT_SOURCE:$APP_MOUNT_TARGET \
  --volume $DATA_MOUNT_SOURCE:$DATA_MOUNT_TARGET \
  $NC_CONTAINER
}

wait_for_installed(){

  NOT_INSTALLED_COUNTER=0
  while true; do
    STATUS_JSON=$(docker exec $RUN_CONTAINER_NAME occ status --output=json_pretty 2> $ERROR_FILE)
    ERROR=$(cat $ERROR_FILE)

    # Warnings and errors at the beginning of Nextcloud startup are expected and therefore ignored
    if [[ -n "$ERROR" ]]; then
      if [[ $ERROR = "Nextcloud is not installed"* ]]; then
        ((NOT_INSTALLED_COUNTER=NOT_INSTALLED_COUNTER+1))
      elif [[ "$ERROR" != "sudo: /var/www/html/occ: command not found"* ]]; then
        echo
        echo "###### Error ######"
        echo $ERROR
        echo "###################"
        echo 
      fi

      if [ -n "$ERROR" ]; then rm -rf $ERROR_FILE; fi

      if [[ $NOT_INSTALLED_COUNTER -eq 10 ]]; then echo "Waiting another 20 seconds for Nextcloud initialization."; fi
      if [[ $NOT_INSTALLED_COUNTER -eq 20 ]]; then echo "Waiting another 10 seconds for Nextcloud initialization."; fi
      if [[ $NOT_INSTALLED_COUNTER -gt 30 ]]; then
        echo "Initializion of Nextcloud Docker container failed. Creating new instance."
        echo "Don't worry. This happens sometimes. Just trying to turn off and on again ;)"
        kill_instance
        sleep 2
        NOT_INSTALLED_COUNTER=0
        creat_instance
      fi

      sleep 1

      continue
    fi

    CURRENT_INSTALLED=$(echo $STATUS_JSON | jq '.installed' 2> $ERROR_FILE)
    ERROR=$(cat $ERROR_FILE)

    if [[ "$CURRENT_INSTALLED" == "true" ]]; then
        echo "Nextcloud installed, initialized and ready to use."
        break;
    elif [[ -n "$ERROR" && "$ERROR" != "jq: parse error:"* ]]; then
        echo
        echo "###### Error ######"
        echo $ERROR
        echo "###################"
        echo 
    fi

    sleep 1
  done
}

activate_app(){

  docker exec $RUN_CONTAINER_NAME occ app:enable --force $NC_APP_ID
  docker exec $RUN_CONTAINER_NAME occ app:enable --force user_ldap
}

prepare

creat_instance

wait_for_installed

activate_app