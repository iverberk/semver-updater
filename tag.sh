#!/bin/bash -e

VERSION=$(bin/semver get --no-build version.json)

git tag -a $VERSION -m $VERSION
git push --force origin $VERSION

bin/semver increase version.json

git add version.json
git commit -m "Increase version"
git push origin master

