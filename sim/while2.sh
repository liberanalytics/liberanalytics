#!/bin/bash
# Return each character from a file.

cssfile="/var/www/html/liberanalytics/site/css/la.css"
jsonfile="/var/www/html/liberanalytics/site/css/la.css.json"
jsontokenbegin="{"
jsontokenend="}"
iter=1
jsonobj=""
jsonobjpos=""
cssselectortoken=" {"

# echo "${jsontokenbegin}"

while read -r ln
do

if [[ $(echo $ln | grep '\{') ]]
then
    
    substring=$(echo $ln | cut -d ' ' -f 1)
    echo $substring

fi
iter=$((iter+1))

done < "${cssfile}"