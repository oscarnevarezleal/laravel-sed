#!/usr/bin/env bash

# A best practices Bash script template with many useful functions. This file
# sources in the bulk of the functions from the source.sh file which it expects
# to be in the same directory. Only those functions which are likely to need
# modification are present in this file. This is a great combination if you're
# writing several scripts! By pulling in the common functions you'll minimise
# code duplication, as well as ease any potential updates to shared functions.


BLACK="\033[30m"
RED="\033[31m"
GREEN="\033[32m"
YELLOW="\033[33m"
BLUE="\033[34m"
PINK="\033[35m"
CYAN="\033[36m"
WHITE="\033[37m"
NORMAL="\033[0;39m"

EXPORT=''
COMPOSER_BIN=`which composer`
S3_SNAPSHOT_KEY="s3://scripts.laraboot.io/snapshots/laravel-shift-blueprint"


# The following are configurable variables.
# Using default values when necesary.

: ${PROJECT_ID:=}
: ${BUILD_ID:=}
: ${ARTIFACT_URL:=}
: ${WORKIG_DIR:=$(pwd)}
: ${BUCKET_NAME:="builds.laraboot.io"}
: ${MOUNT_BUILDS:="${WORKIG_DIR}/mount/builds"}
: ${MOUNT_BLUEPRINT:="${WORKIG_DIR}/mount/blueprints/laravel-shift-blueprint"}
: ${LOGFILE:="$WORKIG_DIR/build.log"}
: ${BLUEPRINT_HYGEN_DIR:=${WORKIG_DIR}/app/builder}
: ${PHP_FLAGS:="-derror_reporting=E_ALL -ddisplay_errors=On -ddisplay_startup_errors=On -dlog_errors=On -derror_log=$LOGFILE"}
: ${COMPOSER_FLAGS:="--ignore-platform-reqs"}

# https://builds.laraboot.io.s3.amazonaws.com/cc8a95abf5db-f2ba39ba3e99/1c0968d1-e2f9-4a45-85ac-3a5484f0e71b.log
S3_LOG_FILE="s3://${BUCKET_NAME}/$PROJECT_ID/$BUILD_ID.log"

# Enable xtrace if the DEBUG environment variable is set
if [[ ${DEBUG-} =~ ^1|yes|true$ ]]; then
    set -o xtrace       # Trace the execution of the script (debug)
fi

# A better class of script...
set -o errexit          # Exit on most errors (see the manual)
set -o errtrace         # Make sure any error trap is inherited
set -o nounset          # Disallow expansion of unset variables
set -o pipefail         # Use last non-zero exit code in a pipeline

# DESC: Usage help
# ARGS: None
# OUTS: None
function script_usage() {
    cat << EOF
Usage:
     -h|--help                  Displays this help
     -v|--verbose               Displays verbose output
    -nc|--no-colour             Disables colour output
    -cr|--cron                  Run silently unless we encounter an error
EOF
}

# DESC: Parameter parser
# ARGS: $@ (optional): Arguments provided to the script
# OUTS: Variables indicating command-line parameters and options
function parse_params() {
    local param
    while [[ $# -gt 0 ]]; do
        param="$1"
        shift
        case $param in
            -h | --help)
                script_usage
                exit 0
                ;;
            -v | --verbose)
                verbose=true
                ;;
            -nc | --no-colour)
                no_colour=true
                ;;
            -cr | --cron)
                cron=true
                ;;
            --hostname)
                hostname=true
                ;;
            *)
                script_exit "Invalid parameter was provided: $param" 1
                ;;
        esac
    done
}

BLACK="\033[30m"
RED="\033[31m"
GREEN="\033[32m"
YELLOW="\033[33m"
BLUE="\033[34m"
PINK="\033[35m"
CYAN="\033[36m"
WHITE="\033[37m"
NORMAL="\033[0;39m"


function updateStatus() {
  time=$(date)
  echo -e $WHITE $time [INFO]: $CYAN $1 $NORMAL
  echo "$WHITE $time [INFO]: $CYAN $1 $NORMAL" >>$LOGFILE
}

function log() {
  time=$(date)
  echo -e $WHITE $time [INFO]: $NORMAL $1 $NORMAL
  echo "$WHITE $time [INFO]: $NORMAL $1 $NORMAL" >> $LOGFILE
}

function logCommand() {
  time=$(date)
  echo -e $WHITE $time [INFO]: $PINK $1 $NORMAL
  echo "$WHITE $time [INFO]: $PINK $1 $NORMAL" >> $LOGFILE
}

function logAndEval() {
  logCommand "$ $1"
  eval $1 | output_reader_2log
}

function flushLogChunk() {
  aws s3 cp --acl public-read --quiet --no-progress $LOGFILE $S3_LOG_FILE
}

# Standard function to print an error and exit with a failing return code
function error_exit() {
  time=$(date)
  echo -e $RED $time Error: $WHITE $1 $NORMAL
  echo "Script exit - ${1}" >&2
  log "An error occurred, the build script handled the error : '${1}'"
  if [[ -n "${TASK_TOKEN:=null}" ]]; then
    aws stepfunctions send-task-failure --task-token $TASK_TOKEN --error "Error handled" --cause "$1"
  fi
  exit 1
}

function err_report(){
  log "An error occurred on line $1. Exit code $2" 
  exit
}

function sig_handler() {
    exit_status=$?  # Eg 130 for SIGINT, 128 + (2 == SIGINT)
    log "Doing signal-specific up"
    exit "$exit_status"
}

function finish(){
  log "Finishing"
  flushLogChunk
  exit
}

function output_reader_2log () {
  while read data; do
    printf "%s" "$data"
    echo $data >> $LOGFILE
  done
}

deploy() {

  updateStatus "Deploying"
  updateStatus 'preparing'
  log 'Preparing deployment'

  # -------------------------------------------------------------------------
  # Download the artifact
  # -------------------------------------------------------------------------  
  
  wget $ARTIFACT_URL -O project.zip && unzip project.zip
  cd ${WORKIG_DIR}/${PROJECT_ID}
  
  # php $PHP_FLAGS $COMPOSER_BIN install $COMPOSER_FLAGS --prefer-dist --optimize-autoloader --no-dev -q || error_exit "Composer install failed"
  # aws s3 cp --no-progress --quiet s3://scripts.laraboot.io/serverless-scripts/laravel-deployment/serverless.yml serverless.yml
  # # SERVICE_NAME must have length less than or equal to 64
  SERVICE_NAME="LarabootDeployment"
  sed -i "s/starterpack\-laravel\-base/$SERVICE_NAME/" serverless.yml
  
  # log "ENV PRISTINE"
  # cat .env
  
  # -------------------------------------------------------------------------
  # Inject Resources values into envs
  # -------------------------------------------------------------------------  
  echo "APP_NAME=$PROJECT_ID" >>.env
  echo "APP_DIR=." >>.env
  echo "APP_STORAGE=/tmp/storage" >>.env
  echo "SESSION_DRIVER=array" >>.env
  echo "LOG_CHANNEL=stderr" >>.env
  echo "APP_ENV=dev" >>.env
  echo "APP_DEBUG=true" >>.env
  echo "APP_KEY=base64:akwmXTmiSJ1SArKda1roXoa4qgXHLCz1MpnC5c3BLz4=" >> .env
  # -------------------------------------------------------------------------
  # Database configuration
  # -------------------------------------------------------------------------  
  echo "DB_CONNECTION=mysql" >>.env
  echo "DB_DATABASE=laravel" >>.env
  echo "DB_USERNAME=master" >>.env
  echo "DB_PASSWORD=password" >>.env
  # -------------------------------------------------------------------------
  # Clockwork configuration
  # -------------------------------------------------------------------------  
  echo "CLOCKWORK_ENABLE=true" >>.env
  echo "CLOCKWORK_STORAGE_FILES_PATH=/tmp/storage" >>.env
  

  updateStatus 'deploying'
  mkdir -p .build
  npm i -g serverless
  npm install serverless-stack-output

  updateStatus "Deploying application"
  chmod 755 -R .

  log "ENV MODIFIED"

  mkdir -p php/conf.d
  echo 'extension=pdo_mysql' > php/conf.d/php.ini

  php $PHP_FLAGS artisan config:clear
  # php $PHP_FLAGS artisan vendor:publish

  serverless deploy || error_exit "Serverless couldnt deploy."
  
  ServiceEndpoint=$(cat .build/stack.json | jq -r '.ServiceEndpoint')
  ServerlessDeploymentBucketName=$(cat .build/stack.json | jq -r '.ServerlessDeploymentBucketName')

  log "ServiceEndpoint $ServiceEndpoint"
  log "ServerlessDeploymentBucketName $ServerlessDeploymentBucketName"

  flushLogChunk
}


# DESC: Main control flow
# ARGS: $@ (optional): Arguments provided to the script
# OUTS: None
function main() {
    # shellcheck source=source.sh
    source "$(dirname "${BASH_SOURCE[0]}")/source.sh"

    trap script_trap_err ERR
    trap script_trap_exit EXIT

    script_init "$@"
    parse_params "$@"
    cron_init
    colour_init
    
    if [[ -n ${hostname-} ]]; then
      pretty_print "Hostname is: $(hostname)"
      deploy
    fi

    #lock_init system
}

# Make it rain
main "$@"

# vim: syntax=sh cc=80 tw=79 ts=4 sw=4 sts=4 et sr