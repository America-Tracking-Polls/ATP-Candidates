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

# Copy page overrides if they exist
if [ -d "$SITE_DIR/page-overrides" ] && [ "$(ls -A "$SITE_DIR/page-overrides" 2>/dev/null)" ]; then
  mkdir -p "$DIST_DIR/page-overrides"
  cp "$SITE_DIR/page-overrides"/* "$DIST_DIR/page-overrides"/
fi

# Update plugin header with client name
CLIENT_NAME=$(python3 -c "import json;print(json.load(open('$SITE_DIR/site-config.json'))['client_name'])")
sed -i "s/Plugin Name:.*/Plugin Name: ATP Campaign Site — $CLIENT_NAME/" "$DIST_DIR/atp-demo-plugin.php"

echo "Build complete: $DIST_DIR"
echo "Contents:"
ls -la "$DIST_DIR"
