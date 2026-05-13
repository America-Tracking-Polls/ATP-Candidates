#!/bin/bash
# ATP Plugin ZIP Builder
#
# Produces a clean, installable WordPress plugin ZIP from
# packages/atp-plugin-core/. The ZIP's root folder is
# `atp-plugin-core/` so WP installs into the correct path on disk
# (wp-content/plugins/atp-plugin-core/) and the auto-updater can
# find subsequent releases.
#
# Usage:
#   ./scripts/build-plugin-zip.sh
#
# Output (at repo root):
#   atp-plugin-core-<version>.zip
#
# Then upload that ZIP via wp-admin -> Plugins -> Add New -> Upload.
#
# DO NOT upload the GitHub repo ZIP itself. It contains a root-level
# `legacy/` folder and other non-plugin files; the plugin folder
# would end up nested too deep for WP to find. See `legacy/README.md`
# for context.

set -e

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SRC="$ROOT/packages/atp-plugin-core"
BOOTSTRAP="$SRC/atp-demo-plugin.php"

if [ ! -d "$SRC" ]; then
  echo "ERROR: $SRC not found" >&2
  exit 1
fi
if [ ! -f "$BOOTSTRAP" ]; then
  echo "ERROR: plugin bootstrap not found at $BOOTSTRAP" >&2
  exit 1
fi

# Pull the version from the plugin header line "Version: x.y.z"
VERSION="$(grep -E '^\s*\*\s*Version:' "$BOOTSTRAP" | head -1 | sed -E 's/.*Version:\s*//' | tr -d '[:space:]')"
if [ -z "$VERSION" ]; then
  echo "ERROR: could not parse Version from $BOOTSTRAP" >&2
  exit 1
fi

OUT="$ROOT/atp-plugin-core-${VERSION}.zip"

# Build in a temp dir so the zip's root entry is `atp-plugin-core/`
TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT
cp -R "$SRC" "$TMP/atp-plugin-core"

# Strip noise that shouldn't ship in the production plugin
find "$TMP/atp-plugin-core" -name '.DS_Store' -delete
find "$TMP/atp-plugin-core" -name '*.swp' -delete
find "$TMP/atp-plugin-core" -name '.git*' -prune -exec rm -rf {} +

rm -f "$OUT"
(cd "$TMP" && zip -r "$OUT" atp-plugin-core >/dev/null)

echo "Built: $OUT"
echo "Version: $VERSION"
echo
echo "Install on a WP site:"
echo "  wp-admin -> Plugins -> Add New -> Upload Plugin -> pick the ZIP -> Activate"
