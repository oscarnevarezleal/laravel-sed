#!/bin/bash

echo "[Running] $@"
echo "[InstallationPath] $CLI_BIN_DIR"

CLI_DIR="${CLI_BIN_DIR}/cli"
LARAVEL_APP_DIR="${CLI_BIN_DIR}/app"

php -v
git --version

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
