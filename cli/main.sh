#!/bin/bash

echo "[Running] $@"

php -v
git --version
php7 -derror_reporting=E_ALL ${CLI_BIN_DIR}/cli/php/main.php $@

cd ${CLI_BIN_DIR}/app & \
 php7 /usr/local/bin/php-cs-fixer fix --config=${CLI_BIN_DIR}/cli/php/.php_cs.dist --dry-run --stop-on-violation --using-cache=no
