#!/usr/bin/env sh

# dev helper

pushd mockup &&
gulp js &&
cp build/js/main.js ../templates/main/build/assets/js/main.js
popd