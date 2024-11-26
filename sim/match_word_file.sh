#!/bin/bash

# Read check for word match in a file.

user=$(whoami)
file=/etc/passwd

    if grep -q "${user}" "${file}"
    then
        echo "${user} exists in ${file} file."
    fi

exit 0