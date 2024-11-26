#!/bin/bash
# Return json formatted styles from css file.

cssfile="/var/www/html/liberanalytics/css/global_styles.css"

while IFS=": " read -r prop value
do

    echo "property => $prop , value: $value"

done < "$cssfile"
