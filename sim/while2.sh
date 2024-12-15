#!/bin/bash
# Return each character from a file.

cssfile="/var/www/html/liberanalytics/site/css/la.css"

while read ln
do
    echo $ln | wc -m
done < "${cssfile}"