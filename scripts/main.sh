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

php7 -derror_reporting=E_ALL "$LARASED" larased:$@ -d $LARAVEL_APP_DIR

if [ "$#" -gt 1 ]; then
    php7 /usr/local/bin/php-cs-fixer fix \
    --config=$LARASED_HOME/.php_cs.dist \
    --dry-run \
    --stop-on-violation \
    --using-cache=no
fi
