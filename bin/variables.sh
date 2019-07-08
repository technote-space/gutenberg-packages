#!/usr/bin/env bash

set -e

if [[ -z "${TRAVIS_BUILD_DIR}" ]]; then
	echo "<TRAVIS_BUILD_DIR> is required"
	exit 1
fi

TAG_MESSAGE="Auto tag by Travis CI"
if [[ -n "${TRAVIS_BUILD_NUMBER}" ]]; then
	COMMIT_MESSAGE="feat: Update version data (Travis build: ${TRAVIS_BUILD_WEB_URL})"
else
	COMMIT_MESSAGE="feat: Update version data"
fi
