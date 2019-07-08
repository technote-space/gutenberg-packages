#!/usr/bin/env bash

set -e

if [[ -z "${TRAVIS_BUILD_WEB_URL}" ]]; then
	echo "<TRAVIS_BUILD_DIR> is required"
	exit 1
fi

export RELEASE_BODY="Auto updated (Travis build: ${TRAVIS_BUILD_WEB_URL})"
