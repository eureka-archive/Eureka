#!/usr/bin/env bash

CLEAN_DIRECTORIES=(
    "bash"
)

CLEAN_FILES=(
    "scripts/Install.php"
    "bin/gitcheck"
    "bin/install"
)

function clean_files {
    display_header "Clean installation files"

    for file in ${CLEAN_FILES[@]}
    do
        filePath="${1}/${file}"

        if [ -f "${filePath}" ]; then
            rm "${filePath}"
        fi
    done

    check "Could not clean files"
}

function clean_dirs {
    display_header "Clean installation directories"

    for directory in ${CLEAN_DIRECTORIES[@]}
    do
        path="${1}/${directory}"

        if [ -d "${path}" ]; then
            rm -r "${path}"
        fi
    done

    check "Could not clean directories"
}
