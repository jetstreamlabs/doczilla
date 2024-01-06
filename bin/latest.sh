#!/bin/bash

DOCS_VERSIONS=(
  2.0
  1.0
)

for v in "${DOCS_VERSIONS[@]}"; do
  if [ -d "resources/docs/$v" ]; then
    echo "Pulling latest documentation updates for $v ..."
    (cd resources/docs/$v && git pull)
  else
    echo "Cloning $v ..."
    git clone --single-branch --branch "$v" https://github.com/jetstreamlabs/doczilla-demo-docs.git "resources/docs/$v"
  fi;
done
