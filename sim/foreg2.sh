#!/bin/bash
# Loop through a factorial

factorial=1                                                     # init factorial var

for (( i=1; i<=$1; i++  ))                                      # loop init, test, iteration

do
    factorial=$[ $factorial * $i ]                                
    echo "The factorial for iteration $i is $factorial."

    if [ $i -gt 20 ]
    then
    echo "Iteration can't exceed 20 and is currently $i"
    exit
    fi

done
exit
