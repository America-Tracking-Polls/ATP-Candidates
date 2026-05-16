#!/bin/bash
# ATP Site Builder — assembles a deployable plugin for a specific client
# Usage: ./scripts/build-site.sh john-stacy
# Output: dist/john-stacy/atp-campaign-site/ (ready to zip and deploy)

set -e

CLIENT="${1:?Usage: build-site.sh <client-slug>}"
SITE_DIR="sites/$CLIENT"
CORE_DIR="packages/atp-plugin-core"
DIST_DIR="dist/$CLIENT/atp-campaign-site"

if [ ! -d "$SITE_DIR" ]; then
  echo "Error: Site directory '$SITE_DIR' not found."
  echo "Available sites:"
  ls sites/
  exit 1
fi

if [ ! -f "$SITE_DIR/site-config.json" ]; then
  echo "Error: site-config.json not found in $SITE_DIR"
  exit 1
fi

echo "Building plugin for: $CLIENT"

# Clean previous build
rm -rf "$DIST_DIR"
mkdir -p "$DIST_DIR"

# Copy core plugin
cp -r "$CORE_DIR"/* "$DIST_DIR"/

# Copy site config into the plugin
cp "$SITE_DIR/site-config.json" "$DIST_DIR/site-config.json"

# Copy intake JSON if it exists
if [ -f "$SITE_DIR/intake-v3.json" ]; then
  cp "$SITE_DIR/intake-v3.json" "$DIST_DIR/intake-v3.json"
fi

# Copy Page JSON and expand it into page-overrides if it exists.
# The runtime loader reads page-overrides/{shortcode_tag}.html on init,
# so this makes AI-generated Page JSON deploy automatically on activation.
if [ -f "$SITE_DIR/page-json.json" ]; then
  cp "$SITE_DIR/page-json.json" "$DIST_DIR/page-json.json"
  mkdir -p "$DIST_DIR/page-overrides"
  python3 - "$SITE_DIR/page-json.json" "$DIST_DIR/page-overrides" <<'PY'
import json
import pathlib
import sys

source = pathlib.Path(sys.argv[1])
target = pathlib.Path(sys.argv[2])
data = json.loads(source.read_text())

for key, value in data.items():
    if key.startswith("_") or key == "atp_cand_styles" or not isinstance(value, str):
        continue
    (target / f"{key}.html").write_text(value)
PY
fi

# Copy page overrides if they exist
if [ -d "$SITE_DIR/page-overrides" ] && [ "$(ls -A "$SITE_DIR/page-overrides" 2>/dev/null)" ]; then
  mkdir -p "$DIST_DIR/page-overrides"
  cp "$SITE_DIR/page-overrides"/* "$DIST_DIR/page-overrides"/
fi

# Update plugin header with client name
CLIENT_NAME=$(python3 -c "import json;print(json.load(open('$SITE_DIR/site-config.json'))['client_name'])")
python3 - "$DIST_DIR/atp-demo-plugin.php" "$CLIENT_NAME" <<'PY'
import pathlib
import sys

plugin = pathlib.Path(sys.argv[1])
client_name = sys.argv[2]
text = plugin.read_text()
lines = []
for line in text.splitlines():
    if line.startswith(" * Plugin Name:"):
        lines.append(f" * Plugin Name: ATP Campaign Site — {client_name}")
    else:
        lines.append(line)
plugin.write_text("\n".join(lines) + "\n")
PY

echo "Build complete: $DIST_DIR"
echo "Contents:"
ls -la "$DIST_DIR"

if command -v zip >/dev/null 2>&1; then
  ZIP_PATH="dist/atp-campaign-site-$CLIENT.zip"
  rm -f "$ZIP_PATH"
  (cd "dist/$CLIENT" && zip -qr "../atp-campaign-site-$CLIENT.zip" atp-campaign-site/)
  echo "ZIP complete: $ZIP_PATH"
fi
