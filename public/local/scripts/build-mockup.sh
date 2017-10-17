#!/usr/bin/env sh

pushd mockup &&
gulp build &&
mkdir -p ../templates/main/build/assets &&
rsync -av --exclude='*.html' build/* ../templates/main/build/assets &&
rsync -av src/styles/css/* ../templates/main/build/assets/css &&
popd