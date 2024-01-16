#!/bin/bash

: ${LARASED_HOME:=/var/laravel-sed}
: ${LARAVEL_APP_DIR:=/var/app}

readonly LARASED="${LARASED_HOME}/index.php"

echo "[Args]           =  $@"
echo "[LarasedHome]    =  $LARASED_HOME"
echo "[LarasedMain]    =  $LARASED"
echo "[LaravelAppDir]  =  $LARAVEL_APP_DIR \n"

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

php -derror_reporting=E_ALL "$LARASED" larased:$@ -d $LARAVEL_APP_DIR

if [ "$#" -gt 1 ]; then
    php /usr/local/bin/php-cs-fixer fix \
    --config=$LARASED_HOME/.php-cs-fixer.dist.php
    if [ ! -f "$LARAVEL_APP_DIR/ecs.php" ]; then
        echo "Creating ecs file"
        php vendor/bin/ecs init
    fi
    php vendor/bin/ecs --fix
    php vendor/bin/ecs list-checkers
    pushd $LARAVEL_APP_DIR
    php /usr/local/bin/styleci analyze
    popd
fi