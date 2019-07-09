#!/usr/bin/env bash

set -e

cp ${TRAVIS_BUILD_DIR}/composer.json ${PACKAGE_DIR}/ 2> /dev/null || :
rm -rdf ${PACKAGE_DIR}/assets
