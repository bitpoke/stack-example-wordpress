#!/usr/bin/env bash

set -eo pipefail

: "${GOOGLE_CLOUD_SDK_VERSION:=395.0.0}"
INSTALL_DIR="${1:-/opt/google-cloud-sdk}"

test -d "${INSTALL_DIR}" || mkdir -p "${INSTALL_DIR}"

curl -sL https://dl.google.com/dl/cloudsdk/channels/rapid/downloads/google-cloud-cli-${GOOGLE_CLOUD_SDK_VERSION}-linux-x86_64.tar.gz | tar -zx -C "${INSTALL_DIR}" --strip-components=1
"${INSTALL_DIR}/install.sh" -q --rc-path=/etc/bash.bashrc
