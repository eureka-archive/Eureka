#!/usr/bin/env bash

function display {
    printf "$1\n";
}

function display_header {
    if [ -t 1 ] # is stdout a terminal ?
    then
        printf "\e[37m\e[100m  \e[104m $1 \e[0m\n";
    else
        printf "### $1\n";
    fi
}

function display_success {
    if [ -t 1 ] # is stdout a terminal ?
    then
        printf "\e[92m$1\e[0m\n\n";
    else
        printf "$1\n\n";
    fi
}

function display_done {
    if [ -t 1 ] # is stdout a terminal ?
    then
        printf "\e[30m\e[100m  \e[42m $1 \e[0m\n\n";
    else
        printf "$1\n\n";
    fi
}

function display_warning {
    if [ -t 1 ] # is stdout a terminal ?
    then
        printf "\e[93m$1\e[0m\n";
    else
        printf "/!\\ $1 /!\\\n";
    fi
}

function display_error {
    if [ -t 1 ] # is stdout a terminal ?
    then
        printf "\e[30m \e[101m$1\e[0m\n";
    else
        printf "/!\\/!\\  $1  /!\\/!\\\n";
    fi
}

function check {
    local LAST_ERROR=$?
    if [ ${LAST_ERROR} -ne 0 ]; then
        display_last_error "${LAST_ERROR}" "${1}"
        exit ${LAST_ERROR}
    fi
}

function check_silently {
    local LAST_ERROR=$?
    if [ ${LAST_ERROR} -ne 0 ]; then
        display_last_error "${LAST_ERROR}" "${1}"
    fi
}

function display_last_error {
    display_error " Error in last command [${1}]"
    if [ "$2" != "" ]; then
        display_error " ${2}"
    fi
}
