---
name: atp-site-edit
description: Use whenever the user asks you to edit a WordPress site that is running the ATP Campaign Site plugin (americatrackingpolls.com or any candidate site). Loads site context first, follows the override system, never edits page content directly when shortcodes are present.
---

# ATP Site Edit — operating instructions for AI assistants

You are connected (via Vibe AI / WordPress MCP / WP REST API) to a
WordPress install running the **ATP Campaign Site** plugin. Editing this
kind of site has rules that don't apply to a vanilla WP install. Read
this skill in full before making any change.

## Step 1 — Always load site context first

Before any edit, fetch the structured site context. Two equivalent
sources of the same data:

- **HTTP**: `GET /wp-json/atp/v1/site-context` (use the user's Application Password / OAuth token for auth)
- **Page**: read the page slugged `ai-start-here` (created by the importer; private status, only readable by authenticated admins)

The response includes:

- Plugin version + site role (intake-host / candidate / unconfigured)
- Active candidate identity (display name, office, party, committee, colors, headshot, logo)
- Every registered shortcode + its current override state on this site
- Every page on the site + which shortcodes it contains
- A decision tree of edit patterns (see Step 3)

Pin this response in your working memory for the duration of the
session.

## Step 2 — Identify what kind of edit the user wants

Map every user request to one of these five categories. Always
confirm which category before you act if it's ambiguous.

| User asks | Category | Where to edit |
|---|---|---|
| "Change Sarah's name / bio / colors / any field that appears in multiple sections" | **A. Content edit** | V3 JSON (the `atp_candidate` post's `_v3_json` meta) |
| "Change just the hero copy on Sarah's site, nothing else" | **B. Per-shortcode data patch** | `wp_options.atp_sc_<tag>_data` |
| "Sarah wants a different hero layout, keep the rest of the content as-is" | **C. Per-shortcode template override** | `wp_options.atp_sc_<tag>` |
| "Try the new core version of the hero on Sarah's site without losing her customization" | **D. Toggle override** | `wp_options.atp_sc_<tag>_disabled` set to 1 |
| "Add the standard 9 pages to a new candidate's site" | **E. Importer flow** | WP Admin → ATP Demo → Import Pages (use the REST or admin route) |

## Step 3 — Hard rules (don't break these)

- **NEVER** edit a page's `post_content` if it contains `[atp_*]` shortcode markup. Pages are composed of shortcodes; the markup pulls from V3 JSON or overrides. Editing the page content directly destroys that binding.
- **NEVER** delete an override to "test the new core version." Set `atp_sc_<tag>_disabled = 1` instead. The override stays stored.
- **NEVER** invent V3 JSON fields. If a field doesn't exist in `v3-schema.json`, ask the user for the right schema location before adding it.
- **NEVER** edit `wp-config.php`, `.htaccess`, theme files, or other plugins. ATP edits stay within the ATP plugin's storage (post meta + wp_options).
- **ALWAYS** verify the candidate exists (V3 JSON loaded) before doing a content edit. If site context shows `candidate: null`, ask the user which candidate this site is for.
- **ALWAYS** report which storage key you wrote to ("Updated `wp_options.atp_sc_atp_cand_hero`") so the user can audit.

## Step 4 — Common flows

### New candidate site (initial setup)

1. Confirm with the user: "I'm about to delete all existing pages on this WP install and create the standard ATP candidate page set. OK to proceed?"
2. List existing pages (`GET /wp-json/wp/v2/pages?per_page=100`).
3. For each non-ATP page (anything not in slug list `home, issues, donate, contact, about, privacy-policy, cookie-policy, sign-up, brand-guide, ai-start-here`), confirm and trash via `DELETE /wp-json/wp/v2/pages/<id>?force=true`.
4. Run the importer: hit each page set via the admin route (or the underlying `atp_importer_handle_import` flow). The importer creates each page with the correct shortcode markup.
5. Load the candidate's `page-json.json` if available (Mirror Factory engineer should have it ready from the AI-generation step) and apply each shortcode override via `POST /wp-json/wp/v2/settings` or by updating `atp_sc_<tag>` options.
6. Verify each page renders by fetching the URL.

### Copy edit (single field)

1. Confirm whether the change should affect ONE section or EVERYWHERE the field appears.
2. Site-wide: update V3 JSON. Edit `wp_post_meta` `_v3_json` on the `atp_candidate` post.
3. Single section: write a data patch. `update_option('atp_sc_<tag>_data', json_encode([key=>value]))`.
4. Report which path you took.

### Layout customization

1. Read the current template: `get_option('atp_sc_<tag>')` if it exists, else the registry default (from `atp_demo_get_default()`).
2. Modify the HTML, keep `{{token}}` placeholders intact for content that comes from V3 JSON.
3. Save: `update_option('atp_sc_<tag>', new_html)`.
4. Tell the user: "Override saved. Toggle off any time via the Edit Shortcodes admin page."

### Preview core vs override

Drop these on a test page:

```
[atp_cand_<tag>]                    ← whichever is active per the toggle
[atp_cand_<tag> source="core"]      ← force registry default
[atp_cand_<tag> source="override"]  ← force stored override
```

## Step 5 — When in doubt, stop and ask

- Schema additions
- Anything that touches multiple sites
- Bulk operations on more than 5 items
- Anything irreversible
- User asks for something the patterns above don't cover

A 30-second clarification beats a 30-minute restore.

## References

- `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` — full override system writeup
- `packages/atp-plugin-core/ARCHITECTURE.md` — system architecture
- `MASTER-PLAN.md` — architecture diagrams
- `docs/candidate-site-flow.md` — end-to-end provisioning flow (ATP → MF → live site)
- `v3-schema.json` — V3 JSON contract

## How a user installs this skill

Drop this folder into `~/.claude/skills/atp-site-edit/` (or the
equivalent skills location for your Claude / Cursor / IDE setup).
Claude Code auto-discovers skills in that directory and will invoke
this one when the user mentions "ATP," "America Tracking Polls," or
performs operations on a connected WP site via MCP.
