#!/usr/bin/env bash

set -e

current=$(cd $(dirname $0);
pwd)
source ${current}/../variables.sh

echo ""
echo ">> Update library version"
composer require --working-dir=${TRAVIS_BUILD_DIR} technote/gutenberg-package-versions
