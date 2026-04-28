# Post-Launch Editing Guide

How to make changes to a live client site after launch.

## How Content Is Stored

Every piece of content on the site is a shortcode. The HTML for each shortcode comes from one of three sources, checked in this order:

1. **`page-json.json`** (in the client's `sites/{slug}/` folder) — the primary source, version-controlled in the repo
2. **WordPress database** (edits made in the admin Shortcode Editor) — quick fixes, overridden by page-json.json on rebuild
3. **Registry defaults** (`registry.php` in the core plugin) — the fallback template if nothing else exists

`page-json.json` is where all content lives. It's the file the AI reads and writes.

## Making Edits via the Repo (Primary Method)

All edits go through the repo. The AI reads and writes `sites/{client}/page-json.json`.

### Change text in a section

Tell the AI:
```
Edit sites/john-stacy/page-json.json — in atp_cand_hero, change the 
tagline from "The Choice for the People" to "Four More Years of Results"
```

The AI edits the JSON value, commits, pushes. Rebuild the plugin. Deploy.

### Add a new endorsement

Tell the AI:
```
Edit sites/john-stacy/page-json.json — in atp_cand_endorsements, add 
a new endorsement card from Senator Jane Roberts: "John is the leader 
Rockwall County needs." Title: State Senator, District 14.
```

### Swap a photo

Tell the AI:
```
Edit sites/john-stacy/page-json.json — in atp_cand_hero, replace the 
headshot image URL with https://new-photo-url.com/headshot.jpg
```

### Update legal pages

Tell the AI:
```
Edit sites/john-stacy/page-json.json — in atp_cand_privacy, update 
the committee mailing address to "456 Oak Street, Rockwall, TX 75087"
```

### Add a new issue card

Tell the AI:
```
Edit sites/john-stacy/page-json.json — in atp_cand_issues, add a new 
issue card for "Water Infrastructure" with tag "Public Utilities" and 
this position text: "..."
```

## Adding a New Section

To add a section that doesn't exist in the standard template:

1. Tell the AI to add a new key to `page-json.json`:
```
Add a new shortcode atp_cand_community to sites/mary-fay/page-json.json 
with a "Community Partners" section showing 4 organization logos.
```

2. Tell the AI to add it to the page in `site-config.json`:
```
In sites/mary-fay/site-config.json, add atp_cand_community to the Home 
page shortcodes list, between atp_cand_about and atp_cand_issues.
```

3. Build and deploy. The section appears only on Mary's site.

## Removing a Section

Tell the AI:
```
In sites/john-stacy/site-config.json, remove atp_cand_volunteer from 
the Home page shortcodes list.
```

The HTML stays in `page-json.json` (harmless) but the shortcode tag is no longer on the page, so it doesn't render.

## Reordering Sections

Tell the AI:
```
In sites/john-stacy/site-config.json, move atp_cand_video above 
atp_cand_issues in the Home page shortcodes list.
```

## Full Page Regeneration

If major changes are needed (new candidate info, complete redesign):

1. Update `sites/{client}/intake-v3.json` with new data
2. Tell the AI to regenerate `page-json.json` using the prompt template
3. Build and deploy

## Testing Changes

Before deploying to the live site, test via WordPress Playground:

```bash
# Build the client's plugin
./scripts/build-site.sh john-stacy

# Test in Playground (update the blueprint to point to the client's build)
```

Or deploy to a staging URL on SiteGround first.

## Scope Tracking

### Included in Standard package
- Text changes (typos, wording, bio edits)
- Photo swaps (new headshot, campaign photos)
- Adding/removing issue cards (within 5-card limit)
- Updating endorsements
- Color, button label changes
- Legal page contact info updates
- New campaign video embed

### Additional work (quoted separately)
- New pages beyond the 7 Standard pages
- New custom sections not in the template
- Custom functionality (event calendar, press blog, polling locator)
- Design changes to the page structure/layout
- Ongoing content management (monthly updates)
- New integrations (email marketing, CRM, analytics)
