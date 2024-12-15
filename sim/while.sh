#!/bin/bash
# Return json formatted styles from css file.

cssfile="/var/www/html/liberanalytics/site/css/la.css"

while IFS=": " read -r prop value
do

    echo "property => $prop , value: $value"

done < "$cssfile"
