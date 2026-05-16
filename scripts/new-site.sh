#!/bin/bash
# ATP New Site Scaffold — creates a new client site directory
# Usage: ./scripts/new-site.sh client-slug "Client Name" "Client Tagline"

set -e

SLUG="${1:?Usage: new-site.sh <slug> <name> <tagline>}"
NAME="${2:?Provide client name as second argument}"
TAGLINE="${3:-Campaign Website}"

SITE_DIR="sites/$SLUG"

if [ -d "$SITE_DIR" ]; then
  echo "Error: Site '$SLUG' already exists at $SITE_DIR"
  exit 1
fi

echo "Creating new site: $SLUG"
mkdir -p "$SITE_DIR/page-overrides"

# Generate site-config.json from template
python3 -c "
import json
config = {
    'client_slug': '$SLUG',
    'client_name': '$NAME',
    'client_tagline': '$TAGLINE',
    'whitelabel': {
        'logo_url': '',
        'logo_width': '200px',
        'color_primary': '#0B1C33',
        'color_accent': '#E60000',
        'login_bg_image': '',
        'admin_footer': 'Powered by <strong>America Tracking Polls</strong> &bull; Mirror Factory',
        'dashboard_msg': 'Welcome to your campaign website dashboard.'
    },
    'domain': {
        'preferred': '',
        'primary': ''
    },
    'pages': [
        {'title': 'Home', 'slug': 'home', 'shortcodes': ['atp_cand_styles','atp_cand_nav','atp_cand_hero','atp_cand_stats','atp_cand_about','atp_cand_messages','atp_cand_issues','atp_cand_endorsements','atp_cand_video','atp_cand_volunteer','atp_cand_survey','atp_cand_donate','atp_cand_social','atp_cand_footer'], 'is_front_page': True},
        {'title': 'Issues', 'slug': 'issues', 'shortcodes': ['atp_cand_styles','atp_cand_nav','atp_cand_issues_page','atp_cand_footer']},
        {'title': 'Donate', 'slug': 'donate', 'shortcodes': ['atp_cand_styles','atp_cand_nav','atp_cand_donate_page','atp_cand_footer']},
        {'title': 'Contact', 'slug': 'contact', 'shortcodes': ['atp_cand_styles','atp_cand_nav','atp_cand_contact','atp_cand_footer']},
        {'title': 'Privacy Policy', 'slug': 'privacy-policy', 'shortcodes': ['atp_cand_styles','atp_cand_nav','atp_cand_privacy','atp_cand_footer']},
        {'title': 'Cookie and TCPA Policy', 'slug': 'cookie-policy', 'shortcodes': ['atp_cand_styles','atp_cand_nav','atp_cand_cookies','atp_cand_footer']},
        {'title': 'Candidate Intake Form', 'slug': 'intake', 'shortcodes': ['atp_intake']}
    ],
    'intake_json': 'intake-v3.json',
    'github_repo': 'America-Tracking-Polls/ATP-Candidates',
    'release_asset': 'atp-campaign-site-$SLUG.zip',
    'deploy_branch': 'main'
}
print(json.dumps(config, indent=2))
" > "$SITE_DIR/site-config.json"

# Create empty intake JSON from schema
cp packages/atp-plugin-core/v3-schema.json "$SITE_DIR/intake-v3.json"

echo ""
echo "Site created: $SITE_DIR/"
echo "  site-config.json  — edit client details, colors, domain"
echo "  intake-v3.json    — empty schema, populate with intake form data"
echo "  page-overrides/   — drop custom shortcode HTML files here"
echo ""
echo "Next steps:"
echo "  1. Edit site-config.json with client details"
echo "  2. Run intake form → save JSON to intake-v3.json"
echo "  3. Run: ./scripts/build-site.sh $SLUG"
