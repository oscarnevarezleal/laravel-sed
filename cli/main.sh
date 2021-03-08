#!/bin/bash

readonly CLI_DIR="${CLI_BIN_DIR}/cli"
: ${LARAVEL_APP_DIR:=${CLI_BIN_DIR}/app}

echo "[Php]                         -------> $(php -v)"
echo "[Args]                        -------> $@"
echo "[InstallationPath]            -------> $CLI_BIN_DIR"
echo "[LaravelAppDir]               -------> $LARAVEL_APP_DIR"


if [ ! -d "$CLI_BIN_DIR" ]; then
    echo "$CLI_BIN_DIR doesnt exists"
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


ls -ltah $LARAVEL_APP_DIR
ls -ltah $CLI_BIN_DIR/cli/php

cd $CLI_DIR && \
    php7 -derror_reporting=E_ALL \
    $CLI_BIN_DIR/cli/php/index.php \
    -d "$LARAVEL_APP_DIR" $@

cd $LARAVEL_APP_DIR & \
    php7 /usr/local/bin/php-cs-fixer fix \
    --config=$CLI_BIN_DIR/cli/php/.php_cs.dist \
    --dry-run \
    --stop-on-violation \
    --using-cache=no
