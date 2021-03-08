#!/bin/bash

php -v

echo "[Running] $@"
echo "[InstallationPath] $CLI_BIN_DIR"

CLI_DIR="${CLI_BIN_DIR}/cli"
LARAVEL_APP_DIR="${CLI_BIN_DIR}/app"

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

cd $CLI_DIR && \
    php7 -derror_reporting=E_ALL \
    $CLI_BIN_DIR/cli/php/main.php \
    -d $LARAVEL_APP_DIR $@

cd $LARAVEL_APP_DIR & \
    php7 /usr/local/bin/php-cs-fixer fix \
    --config=$CLI_BIN_DIR/cli/php/.php_cs.dist \
    --dry-run \
    --stop-on-violation \
    --using-cache=no
