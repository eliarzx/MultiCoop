#!/usr/bin/env sh
set -eu
cd "$(dirname "$0")/.."
find public includes config -type f -name '*.php' -exec php -l {} \; > "${TMPDIR:-/tmp}/multicoop-lint-$$.log" 2>&1
log="${TMPDIR:-/tmp}/multicoop-lint-$$.log"
if grep -v '^No syntax errors detected' "$log"; then
    rm -f "$log"
    exit 1
fi
rm -f "$log"
echo "PHP syntax checks passed."
