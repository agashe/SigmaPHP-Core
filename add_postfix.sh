#!/bin/bash

# Add Interface word to multiple files.

for file in $1/*.php;
do
    NEW=${file%.php}Interface.php;
    mv ${file} "${NEW}";
done 