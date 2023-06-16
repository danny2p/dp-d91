#!/bin/bash

function multidev_exists() {
    LIST=$1
    DELIMITER=$2
    VALUE=$3
    echo $LIST | tr "$DELIMITER" '\n' | grep -F -q -x "$VALUE"
}
MULTIDEVS=$(terminus multidev:list $PANTHEON_SITE --field Name --format list)

if multidev_exists "$MULTIDEVS" " " $CI_BRANCH; 
then
 echo "$CI_BRANCH multidev already exists"
else
  echo "Creating Multidev for $CI_BRANCH";
  terminus multidev:create -- $PANTHEON_SITE.dev $CI_BRANCH &
fi