# ATP Campaign Site Platform — Handoff Guide

**Last updated:** 2026-05-13
**Plugin version on `main`:** `3.6.1`
**Working branch this session:** `claude/activate-drive-upload-P3yOj`
**Audience:** the next Mirror Factory engineer or AI agent picking this up.

This is the single document to read first if you've never touched this
repo before. It links to everything else.

> Companion docs:
> - `README.md` — product overview + quick-link table
> - `AGENTS.md` — operating rules for AI agents (which references this file)
> - `EDIT_LOG.md` — running history of every change
> - `packages/atp-plugin-core/ARCHITECTURE.md` — system diagrams
> - `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` — per-site customization
> - `docs/candidate-site-flow.md` — 7-phase candidate-site provisioning
> - `docs/google-drive-setup.md` — Drive OAuth walkthrough
> - `.claude/skills/atp-site-edit/SKILL.md` — Claude Code skill for editing live sites

---

## 1. What this platform is, in one screen

**One WordPress plugin (`ATP Campaign Site`, slug `atp-demo`) that powers
two kinds of installs from the same codebase:**

```
        ┌────────────────────────────────────────────────────────┐
        │  ATP intake host (americatrackingpolls.com)            │
        │  Role: collects candidate intakes, mirrors files to    │
        │        Google Drive, notifies Mirror Factory by email  │
        │  Shortcodes used: [atp_intake], [atp_logo],            │
        │        marketing shortcodes (atp_mkt_*)                │
        └────────────────────────────────────────────────────────┘
                              │
                              ▼  (Mirror Factory provisions)
        ┌────────────────────────────────────────────────────────┐
        │  Candidate campaign site (one per candidate)           │
        │  Role: renders the candidate's 9-page campaign site    │
        │  Shortcodes used: atp_cand_* family + atp_logo         │
        │  Driven by: V3 JSON stored on the atp_candidate post   │
        └────────────────────────────────────────────────────────┘
```

Same plugin, same auto-updater, same release pipeline. What differs is
the data the plugin finds and which pages have been imported.

**The contract** between the two is `packages/atp-plugin-core/v3-schema.json`
— the V3 JSON. Every candidate site is just a rendering of one V3 JSON
document plus optional per-shortcode overrides.

---

## 2. Status of every component

Legend: ✅ shipped & tested · 🟡 shipped, needs live-site verification · ❌ blocked / pending

### Plugin core (`packages/atp-plugin-core/`)

| Component | File | Status | Notes |
|---|---|---|---|
| Plugin bootstrap | `atp-demo-plugin.php` (v3.6.0) | ✅ | Declares `Requires Plugins: vibe-ai` |
| Shortcode registry | `includes/registry.php` | ✅ | 14+ candidate shortcodes, marketing shortcodes, AI-context shortcode |
| Generic shortcode renderer + override system | `includes/shortcodes.php` | ✅ | `source="core\|override"` preview attrs, disable toggle, data patches |
| Token replacement | `includes/candidate-page.php` | ✅ | Patch-aware (`atp_cand_replace_tokens($html, $patch)`) |
| Admin UI (Edit Shortcodes) | `includes/admin.php` | ✅ | Disable toggle, data-patch textarea, preview chips, status badges |
| Page importer (9 pages) | `includes/importer.php` | ✅ | Honors `status` field; includes `ai-start-here` private page |
| Auto-updater (GitHub) | `includes/updater.php` | ✅ | Pulls releases from `America-Tracking-Polls/ATP-Candidates` |
| Setup wizard | `includes/setup-wizard.php` | ✅ | First-run admin walkthrough |
| Whitelabel / brand settings | `includes/whitelabel.php` | 🟡 | Drive OAuth fields included; needs live OAuth round-trip test |
| Per-client config loader | `includes/site-config.php` | ✅ | |
| Site-context / AI infrastructure | `includes/ai-context.php` | 🟡 | New in 3.6.0 — needs live `GET /wp-json/atp/v1/site-context` verification |
| Marketing shortcodes | `includes/marketing-shortcodes.php` | ✅ | Same override system mirrored for `atp_mkt_*` |
| Signup form (TCPA) | `includes/signup.php` | ✅ | `[atp_cand_signup]` + `atp_subscriber` CPT + email notify |
| Intake form (16-step) | `includes/intake/atp-candidate-intake.php` | 🟡 | Working in admin; live image upload still blocked on SG (see §6) |
| Drive API client | `includes/drive-client.php` | 🟡 | OAuth flow rewritten in 3.2.0 — connect/disconnect/browse all coded; not yet round-tripped on the live host |
| File upload routing | `includes/file-upload.php` | 🟡 | WP media first, Drive mirror second; blocked by SG WAF on the live site |

### Documentation

| Doc | Status |
|---|---|
| `README.md` | ✅ Up to date through 3.5.0 — add a 3.6.0 line when convenient |
| `AGENTS.md` | ✅ Updated this session to reference this handoff doc |
| `EDIT_LOG.md` | ✅ Current through `af6a92d` |
| `MASTER-PLAN.md` | ✅ 5 ASCII diagrams |
| `packages/atp-plugin-core/ARCHITECTURE.md` | ✅ |
| `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` | ✅ |
| `packages/atp-plugin-core/CHANGELOG.md` | 🟡 Last entry is 3.2.0 — needs 3.5.0 and 3.6.0 entries |
| `docs/candidate-site-flow.md` | ✅ New this session |
| `docs/google-drive-setup.md` | ✅ Rewritten for OAuth in 3.2.0 |
| `.claude/skills/atp-site-edit/SKILL.md` | ✅ New this session |

### Integrations

| Integration | Status | What's left |
|---|---|---|
| **Vibe AI plugin** ([wordpress.org/plugins/vibe-ai/](https://wordpress.org/plugins/vibe-ai/)) | 🟡 declared as `Requires Plugins` | Connect to a real site, verify the `atp-site-edit` skill triggers and the `/wp-json/atp/v1/site-context` endpoint returns what we expect |
| **Google Drive (OAuth)** | 🟡 code complete | End-to-end test: connect account → pick folder → submit intake → verify file appears in Drive |
| **GitHub auto-updater** | ✅ working | — |
| **SiteGround host** | 🟡 partial | Anti-Bot AI WAF blocks `/wp-json/*` and the upload AJAX endpoint — needs an SG support ticket to whitelist (see §6) |
| **WP Playground blueprint** | ✅ | `playground-blueprint.json` boots a demo |

---

## 3. The Vibe AI integration — what it is and how it works

[Vibe AI](https://wordpress.org/plugins/vibe-ai/) is a third-party
WordPress plugin that exposes a site as an MCP (Model Context Protocol)
server. Once installed and connected, an AI client (Claude Desktop /
Claude Code / ChatGPT with MCP support) can read and edit the
WordPress site through Vibe AI's tools.

**Why we depend on it:** instead of building our own MCP server, we
declare Vibe AI as a required plugin and ride on top of it. Our plugin
just needs to expose the right *structured data* (V3 JSON, shortcode
overrides, page list) so the AI knows what it's looking at.

**Where the wiring lives:**

1. **Dependency declaration** — `atp-demo-plugin.php` line 9:
   `Requires Plugins: vibe-ai`. WordPress will prevent activation if
   Vibe AI is not installed.
2. **Structured site context** — `includes/ai-context.php`:
   - `atp_get_site_context()` returns plugin version, site role,
     candidate identity, V3 JSON snapshot, shortcode list with
     override state, page list with shortcode usage, and a decision
     tree of edit patterns.
   - REST endpoint `GET /wp-json/atp/v1/site-context` (auth:
     `current_user_can('edit_posts')`) returns the same data as JSON.
   - `[atp_cand_ai_context]` shortcode renders the same info as an
     HTML overview page (rendered into `/ai-start-here`, status
     `private` — only logged-in admins can read it).
3. **AI operating instructions** — `.claude/skills/atp-site-edit/SKILL.md`:
   a Claude Code skill that loads automatically when an AI is talking
   to an ATP site. It tells the AI to fetch site context first, map
   user requests to one of five edit categories, and follow hard
   rules (never edit page content containing shortcodes, never delete
   overrides to test core, never invent V3 fields, etc.).

**Trust model:** Vibe AI uses the connected user's WP capabilities.
Anything our REST endpoint or shortcodes do is bound by that user's
caps. The site-context endpoint requires `edit_posts` (Contributor+).
Writing overrides requires `manage_options` (Admin).

**What's verified vs. not verified:**

- ✅ Code is in place, lints clean, security-reviewed (no findings)
- ❌ Not yet exercised end-to-end against a live WP install with Vibe
  AI installed. Pending §5 smoke test.

---

## 4. The Google Drive integration — current state

**Auth model:** OAuth 2.0 user flow (switched from service-account in
3.2.0). The site admin connects their own Google account; we store the
client ID, client secret, refresh token, connected-account email, and
picked folder ID/name in WP options. No JSON key file. No shell access.
No Drive folder sharing required.

**Flow on the live site (intake host):**

1. Admin pastes OAuth Client ID + Secret from Google Cloud Console
2. Clicks **Connect Google Drive** → Google consent → callback returns
   a refresh token → stored in WP options
3. Clicks **Browse my Drive…** → modal opens, navigates folders
   (including shared-with-me at root), picks a destination folder
4. Submitting the intake form mirrors each uploaded file (headshot,
   logo, photos) into a per-submission subfolder of that destination
5. The WordPress media library is always populated first; Drive is a
   secondary mirror

**Status:**

| Step | Status |
|---|---|
| OAuth code path (`drive-client.php`) | ✅ written |
| Token persistence (user_meta state, not transient) | ✅ |
| Scope = full `drive` (was `drive.file`, too narrow) | ✅ |
| Shared-with-me folder listing | ✅ |
| Connect / disconnect / browse / test buttons in admin | ✅ |
| End-to-end on live SG host | ❌ NOT YET TESTED |
| Intake form image upload reaching Drive | ❌ blocked by SG WAF (see §6) |

**Drive test plan (concrete — run in this order):**

The intent on the original setup was the destination folder
**`Intake_Submissions_Live`** (Drive folder ID:
`1AmUatOOqqliQezIJZM2qqO6jt3M_dHZR`). Verifying that the folder
picker is actually pointing at this ID is part of the test.

1. **Cloud Console verification** (do this first — needs the
   `ATP Intake` OAuth app owner's access):
   - **Scope** confirm the app's consent screen has
     `https://www.googleapis.com/auth/drive` listed (not just
     `drive.file`). The plugin already requests the broad scope at
     `drive-client.php:33` — `const ATP_DRIVE_SCOPE = 'https://www.googleapis.com/auth/drive';`.
     If the consent screen still lists only `drive.file`, the upload
     will fail because we navigate folders we didn't create. Update
     the OAuth consent screen's scope list and re-publish.
   - **Authorized redirect URIs** confirm both staging and production
     callback URLs are listed:
     - `https://americatrackingpolls.com/wp-admin/admin.php?page=atp-whitelabel&atp_drive_callback=1`
     - any staging equivalent
   - **Test users** (if the consent screen is still in Testing mode)
     confirm the account that will be connecting is on the list.
2. **Plugin connect** on the intake host:
   - Settings → Whitelabel → Connect Google Drive
   - Complete consent, confirm the connected-account email displays
   - Click **Browse my Drive…**, navigate to and pick
     `Intake_Submissions_Live`
   - Save. Confirm the saved folder ID in
     `get_option('atp_drive_config')['folder_id']` equals
     `1AmUatOOqqliQezIJZM2qqO6jt3M_dHZR`. (Quickest read: from
     wp-admin → Tools → Site Health → Info → look at
     `atp_drive_config`, or from WP CLI:
     `wp option get atp_drive_config --format=json`.)
3. **Dummy submission** under a throwaway candidate so it's easy to
   find and delete:
   - Display name: **`Test Candidate`**
   - Office: **`FL State Senate`**
   - Attach a headshot, logo, and one additional photo
   - Submit
4. **Confirm the result** in Drive:
   - Subfolder named (today)`_Test-Candidate_FL-State-Senate` appears
     inside `Intake_Submissions_Live`
   - All three files are inside the subfolder
   - Filenames follow `<field>_<original-filename>` pattern (e.g.
     `headshot_headshot.jpg`, `logo_logo.png`,
     `additional_photo_1_foo.jpg`)
5. **Confirm in WP media library**: same three files appear (Drive is
   the mirror, WP media is the primary).
6. **Delete the dummy submission** from WP admin and the dummy
   subfolder from Drive when done.

**Subfolder naming pattern** is implemented at `file-upload.php:130-134`:
```
YYYY-MM-DD_Candidate-Name_Office-Slug
```
e.g. `2026-05-13_Test-Candidate_FL-State-Senate`. Both name parts are
run through `sanitize_file_name()` with spaces converted to dashes.

**Likely first failure:** SG Anti-Bot AI WAF blocking either
`/wp-admin/admin-ajax.php` (browser → server) or the OAuth callback
URL. See §6a.

**If the test fails partway through:** the previous live attempt
threw a persistent error around folder selection. The most likely
causes, in order:
1. SG WAF blocking the `atp_drive_browse` AJAX endpoint (§6a) — try
   from an authenticated curl or a non-CloudFlare-fronted environment
   to isolate
2. OAuth consent screen still on `drive.file` scope — the picker can
   only see files/folders the app created, so a pre-existing folder
   like `Intake_Submissions_Live` won't appear
3. The connecting account doesn't have access to the destination
   folder (it's owned by someone else and never shared in to the
   connecting account)

---

## 5. Smoke test checklist for plugin 3.6.0

Run on a staging WordPress install before touching production.

```
[ ] WordPress 6.4+, PHP 8.1+
[ ] Vibe AI plugin installed (latest)
[ ] ATP Campaign Site plugin 3.6.0 installed + activated
[ ] Plugin admin pages load without errors:
    - ATP Demo → Dashboard
    - ATP Demo → Edit Shortcodes
    - ATP Demo → Import Pages
    - Settings → Whitelabel
[ ] Importer creates 9 pages: home, issues, donate, contact, about,
    privacy-policy, cookie-policy, sign-up, brand-guide, ai-start-here
[ ] /ai-start-here is private — anonymous GET returns 404/403,
    admin GET renders the overview HTML
[ ] GET /wp-json/atp/v1/site-context with an Application Password:
    - 401 without auth
    - 403 with a Subscriber-level user
    - 200 with an Editor/Admin user, returning expected JSON shape
[ ] Connect Vibe AI from Claude (or ChatGPT) to the site:
    - The atp-site-edit skill loads or is detected
    - Asking "what kind of site am I looking at?" yields a context-aware
      answer (site role, candidate name, registered shortcodes)
[ ] Edit Shortcodes UI:
    - Saving an override template persists (atp_sc_<tag>)
    - Saving a data patch persists (atp_sc_<tag>_data)
    - Disable toggle flips atp_sc_<tag>_disabled
    - Preview chips render the right source
[ ] Submit signup form on /sign-up:
    - atp_subscriber CPT row created
    - Admin email notification sent
[ ] Submit intake form (separate from candidate site - intake-host only):
    - Files land in WP media
    - V3 JSON saved as atp_candidate post meta
    - Email notification to alfonso/gary/dan
    - Files mirror to Drive (assumes Drive is connected; see §4)
```

---

## 6. Known blockers and how to clear them

### 6a. SiteGround Anti-Bot AI blocking `/wp-json/*` and AJAX upload

**Symptom:** image uploads on the intake form silently fail; REST
endpoints return 403 from CloudFlare/SG edge.

**Status:** open. Needs SG support ticket.

**Action:**

1. Open a ticket with SiteGround support
2. Ask them to whitelist these paths for the WP install:
   - `POST /wp-admin/admin-ajax.php` (action: `atp_intake_submit`, `atp_drive_browse`)
   - `GET|POST /wp-json/atp/v1/*`
   - `GET|POST /wp-json/wp/v2/*` (for Vibe AI / MCP)
3. Confirm Anti-Bot AI is set to "monitor" not "block" for these paths
4. Re-test with DevTools Network tab; capture the failing request +
   response headers if it still fails

### 6b. Drive OAuth not yet round-tripped end-to-end

**Status:** code complete, not exercised.

**Action:** §4 step list above. The most likely point of failure is
either §6a or a forgotten step in `docs/google-drive-setup.md`. If a
specific Google error appears, that doc has a troubleshooting table
covering `redirect_uri_mismatch`, `invalid_grant`, missing refresh
token, etc.

### 6c. Pushing `main` from the AI session

**Status:** the push proxy from any AI session blocks direct pushes to
`main`. AI agents push to `claude/*` task branches; a human ff-merges
locally.

**Action when an AI branch is ready:**
```
git fetch origin
git checkout main
git merge --ff-only origin/claude/<branch-name>
git push origin main
```

### 6d. CHANGELOG.md is stale

**Status:** last entry is 3.2.0; we're on 3.6.0.

**Action:** add entries for 3.5.0 (override system v2, brand guide,
signup, Vibe AI dependency declaration) and 3.6.0 (AI context
infrastructure, Claude skill, candidate-site-flow doc).

---

## 7. Next actions, ordered

These are the things to do, in the order they should be done, by
whoever picks this up next.

1. **From your local clone:** `git pull` and ff-merge the latest task
   branch into `main` per §6c.
2. **Build a clean plugin ZIP** — this is the one supported install
   path. From the repo root:
   ```
   ./scripts/build-plugin-zip.sh
   ```
   produces `atp-plugin-core-<version>.zip` whose root folder is
   `atp-plugin-core/`. **Do not** upload the GitHub repo ZIP directly
   — that historically caused an intake-only plugin to install
   instead of the canonical full plugin (see EDIT_LOG 2026-05-13).
3. **Install on a WP site** — wp-admin → Plugins → Add New → Upload
   → pick the ZIP from step 2 → Activate. If an older copy of the
   plugin is already installed, deactivate + delete it first (this
   removes the files but not the DB settings; your Drive OAuth
   config in `wp_options` survives).
4. **Stand up a staging WP install** (Playground works for code path
   verification but not for Drive / live integrations; for those you
   need a real host). Walk the §5 checklist.
5. **Open the SG ticket** (§6a) in parallel with step 4 — turnaround is
   usually a day.
6. **Cloud Console pre-flight** (in parallel with steps 4–5): verify
   the `ATP Intake` OAuth app has the broad Drive scope listed on its
   consent screen and that both redirect URIs (prod + any staging)
   are registered. See §4 step 1.
7. **Once SG paths are whitelisted:** run the Drive test plan in §4
   end-to-end against the live intake host, using a dummy
   `Test Candidate / FL State Senate` submission with a full asset
   payload. Confirm the destination folder is
   `Intake_Submissions_Live` (ID `1AmUatOOqqliQezIJZM2qqO6jt3M_dHZR`)
   and that the subfolder gets created with the
   `YYYY-MM-DD_Candidate-Name_Office-Slug` pattern. Delete the dummy
   submission + dummy subfolder afterward.
8. **Connect Vibe AI from Claude or ChatGPT** to a candidate-site
   staging install. Confirm the atp-site-edit skill is detected and
   the AI can load `/wp-json/atp/v1/site-context`.
9. **First real candidate dry-run:** pick one intake submission and
   walk it through the 7-phase flow in `docs/candidate-site-flow.md`.
   Update the flow doc anywhere it's wrong or thin.
10. **Backfill `CHANGELOG.md`** with 3.5.0, 3.6.0, and 3.6.1 entries
    (§6d).
11. **Cut a GitHub release** at tag `v3.6.1` (attach the ZIP from
    step 2 as the release asset) so the auto-updater can pick it up
    on candidate sites that are already running an earlier 3.x.

---

## 8. Where to look when you don't know where to look

| Question | Open |
|---|---|
| What did the last AI agent do? | `EDIT_LOG.md` |
| What are the rules for an AI agent in this repo? | `AGENTS.md` |
| Why does anything exist? | `README.md` → quick-link table |
| What's the data contract between intake and site? | `packages/atp-plugin-core/v3-schema.json` |
| How does the override system work? | `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` |
| How is the system actually wired? | `packages/atp-plugin-core/ARCHITECTURE.md` + `MASTER-PLAN.md` |
| How do I provision a new candidate site? | `docs/candidate-site-flow.md` |
| How do I edit a live site from Claude / Cursor? | `.claude/skills/atp-site-edit/SKILL.md` |
| Drive setup? | `docs/google-drive-setup.md` |
| What does the plugin currently ship? | `packages/atp-plugin-core/CHANGELOG.md` (stale — see §6d) |
| What's the version on `main` and what does it include? | `atp-demo-plugin.php` line 6 (`Version:`) + EDIT_LOG.md |

---

## 9. Open questions / decisions worth raising

These didn't block shipping but the next engineer should know about
them:

- **Vibe AI vs. direct WP 7.0 Abilities API + MCP Adapter.** WordPress
  core is shipping a native MCP server in 7.0. We're on Vibe AI for
  now; revisit when 7.0 stabilizes. The structured context we expose
  (`/wp-json/atp/v1/site-context`) is provider-agnostic.
- **Marketing shortcodes vs. candidate shortcodes overlap.** Both
  registries share the override system but live in separate option
  key namespaces (`atp_sc_*` vs `atp_mkt_sc_*`). Worth a future audit
  to make sure no tag collides.
- **The two demo sites** (`campaign-site/` Sarah Chen and `personal-site/`
  Michael Torres) are reference templates, not throwaway demos. If
  you're touching candidate-site markup, those should still render
  cleanly from real V3 JSON. Don't delete them.
- **Intake host vs. candidate site detection** is heuristic
  (`atp_detect_site_role()` in `ai-context.php`). If the heuristic
  ever lies, the AI context will mislead. Worth replacing with an
  explicit setting if it bites us.

---

*If you've read this far, you have everything you need. Anything not
covered here is either (a) in one of the linked docs or (b) a decision
that hasn't been made yet — raise it.*
