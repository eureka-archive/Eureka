#!/usr/bin/env bash

CACHE_DIRECTORIES=(
    ".cache/config"
    ".cache/container"
    "web/static/cache"
)

function fix_cache_directories {
    display_header "Fix cache directories"

    for directory in ${CACHE_DIRECTORIES[@]}
    do
        path="${1}/${directory}"

        if [ ! -d "${path}" ]; then
            mkdir -p "${path}" --mode=0766
        else
            chmod -R 0766 "${path}"
        fi
    done

    check "Could not fix cache directories"
}

