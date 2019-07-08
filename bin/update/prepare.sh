#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)
source ${current}/../variables.sh

echo ""
echo ">> Run composer install"
composer install --no-dev --working-dir=${TRAVIS_BUILD_DIR}
