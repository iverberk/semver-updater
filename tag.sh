#!/bin/bash -e

VERSION=$(bin/semver get version.json)

git tag -a $VERSION -m $VERSION
git push --tags

bin/semver increase version.json

git add version.json
git commit -m "Increase version"
git push origin master

