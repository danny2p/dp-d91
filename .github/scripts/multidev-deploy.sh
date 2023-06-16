#!/bin/bash

function multidev_exists() {
    LIST=$1
    DELIMITER=$2
    VALUE=$3
    echo $LIST | tr "$DELIMITER" '\n' | grep -F -q -x "$VALUE"
}
MULTIDEVS=$(terminus multidev:list dp-d91 --field Name --format list)

if multidev_exists "$MULTIDEVS" " " $CI_BRANCH; 
then
 echo "$CI_BRANCH multidev already exists"
else
  terminus multidev:create -- $SITE.dev $CI_BRANCH &
fi