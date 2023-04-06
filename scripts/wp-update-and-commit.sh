#!/usr/bin/env bash

set -euo pipefail

: "${KIND:=${1:-"plugin"}}"
KIND_PLURAL="${KIND}s"

wp() {
    docker compose exec --no-TTY wordpress wp --url=https://localhost "${@}"
}

for item in $(wp "${KIND}" list --update=available --fields=name,title,version,update_version --format=json | jq -r '.[] | @base64') ; do
    _jq() {
        echo "${item}" | base64 --decode | jq -r "${1}"
    }

    name="$(_jq '.name')"
    title="$(_jq '.title')"
    version="$(_jq '.version')"
    update_version="$(_jq '.update_version')"

    if [ "${name}" = "name" ]; then
        continue
    fi

    wp "${KIND}" update "${name}" &&
    git add -A "wp-content/${KIND_PLURAL}/${name}" &&
    git commit -m "Update \`${title}\` ${KIND} from ${version} to ${update_version}"
done
