#!/usr/bin/env bash

set -euo pipefail

TAG="$(curl -sL 'https://registry.hub.docker.com/v2/repositories/bitpoke/wordpress-runtime/tags?page_size=1024' | jq -r '."results"[]["name"]' | grep -v bedrock | grep -v php | sort -r -n | head -n 1)"

sed "s/^ARG TAG=.*\$/ARG TAG=${TAG}/g" Dockerfile | tee Dockerfile.new
mv Dockerfile.new Dockerfile

git add Dockerfile

git commit -m "Update to WordPress to ${TAG}"
