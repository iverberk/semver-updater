#!/bin/bash -e

VERSION=$(bin/semver get composer.json)

git tag -a $VERSION -m $VERSION
git push --tags

bin/semver increase composer.json

git add composer.json
git commit -m "Increase version"
git push origin master

