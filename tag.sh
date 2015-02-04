#!/bin/bash -e

VERSION=$(bin/semver get --no-build version.json)

git tag --force -a $VERSION -m $VERSION
git push --force origin $VERSION

bin/semver increase --type patch version.json

git add version.json
git commit -m "Increase version"
git push origin master

