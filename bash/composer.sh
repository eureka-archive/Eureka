#!/usr/bin/env bash

COMPOSER_BIN="composer"

## First parameter is the folder where to run the commands
function install_composer_dependencies {
    display_header "Installing composer dependencies in \"${ENV_MODE}\" mode"

    pushd "${1}" > /dev/null

    local composer_command

    if [ -z "${2}" ]; then
        composer_optimize=""
    else
       composer_optimize="--optimize-autoloader"
    fi

    if [ "${ENV_MODE}" == "prod" ]; then
        composer_command="${COMPOSER_BIN} --no-interaction ${composer_optimize} install --no-dev --no-progress"
    else
        composer_command="${COMPOSER_BIN} --no-interaction install"
    fi

    ${composer_command}
    check "Could not install composer dependencies"
    popd > /dev/null
}

function copy_composer_json {
    display_header "Copying composer_${ENV_MODE}.json to composer.json"

    pushd "${1}" > /dev/null

    local composer_env_json

    composer_env_json="composer_${ENV_MODE}.json"

    cp ${composer_env_json} "composer.json"
    check "Could not move '${composer_env_json}' to 'composer.json'"

    popd > /dev/null
}

function copy_database_config {
    display_header "Copying database.yml to config/database.yml"

    pushd "${1}" > /dev/null

    if [ -z "${2}" ]; then
        exit 1
    fi

    cp "${2}/database.yml" "${1}/config/database.yml"
    check "Could not copy ${2}/database.yml to ${1}/config/database.yml"

    popd > /dev/null
}
