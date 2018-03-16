#!/usr/bin/env bash

COMPOSER_BIN="composer"

function clean_composer {
    display_header "Clean previous composer install"

    if [ -d "${1}/vendor" ]; then
        rm -rf "${1}/vendor"
    fi

    check "Could not clean 'vendor/' directory"

    if [ -f "${1}/composer.lock" ]; then
        rm "${1}/composer.lock"
    fi

    check "Could not clean 'composer.lock' file"
}

## First parameter is the folder where to run the commands
function install_composer_dependencies {
    display_header "Installing composer dependencies in \"${ENV_MODE}\" mode"

    pushd "${1}" > /dev/null

    local composer_command

    if [ "${ENV_MODE}" == "prod" ]; then
        composer_command="${COMPOSER_BIN} --no-interaction --optimize-autoloader install --no-dev --no-progress"
    else
        composer_command="${COMPOSER_BIN} --no-interaction install"
    fi

    ${composer_command}
    check "Could not install composer dependencies"
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
