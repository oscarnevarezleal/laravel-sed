#!/bin/bash

: ${LARASED_HOME:=/var/laravel-sed}
: ${LARAVEL_APP_DIR:=/var/app}

readonly LARASED="${LARASED_HOME}/cli/php/index.php"

echo "[Args]           =  $@"
echo "[LarasedHome]    =  $LARASED_HOME"
echo "[LarasedMain]    =  $LARASED"
echo "[LaravelAppDir]  =  $LARAVEL_APP_DIR"

if [ ! -d "$LARASED_HOME" ]; then
    echo "$LARASED_HOME doesnt exists"
    exit 125;
fi

if [ ! -d "$LARAVEL_APP_DIR" ]; then
    echo "$LARAVEL_APP_DIR doesnt exists"
    exit 125;
fi

if [ "$#" -lt 1 ]; then
    echo "Illegal number of parameters"
    exit 126;
fi

#ls -ltah $LARAVEL_APP_DIR
#ls -ltah $LARASED_HOME/cli/php

php7 -derror_reporting=E_ALL "$LARASED" $@ -d $LARAVEL_APP_DIR

if [ "$#" -gt 1 ]; then
    php7 /usr/local/bin/php-cs-fixer fix \
    --config=$LARASED_HOME/cli/php/.php_cs.dist \
    --dry-run \
    --stop-on-violation \
    --using-cache=no
fi
