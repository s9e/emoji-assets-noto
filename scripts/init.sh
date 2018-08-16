#/bin/bash

cd "$(dirname $(dirname $0))"

git submodule update --init --recursive --remote
npm install --prefix third_party svgo
