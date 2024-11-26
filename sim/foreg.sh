#!/bin/bash
# Loop through a command line parameter.

factorial=1

for ((num=1; num<=$1; num++))
do
    factorial=$[$factorial*$num]
    echo "Factorial = $factorial and num = $num" 
done
exit
