#!/bin/bash

# Read contents of file outputting to terminal line by line.

file=/var/www/html/liberanalytics/site/index.php
counter=1


cat "${file}" | while read ln
do

    echo "Line ${counter}: ${ln}."
    counter=$((counter+1))

done
exit 0