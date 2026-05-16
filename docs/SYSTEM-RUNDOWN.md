# ATP System Rundown — End-to-End Reference

**Audience:** another LLM or a new engineer who needs the full picture
of how the ATP intake-to-site pipeline works, in one document.

**Status:** current as of plugin version 3.6.4. Pair this with
`HANDOFF.md` (status of every component) and
`docs/candidate-site-flow.md` (the 7-phase provisioning narrative).

---

## 1. What this system does, in one sentence

A WordPress plugin that takes a political candidate's intake-form
submission, turns the structured answers into a V3 JSON document,
generates HTML page content from that JSON via an LLM, and produces
a candidate campaign website with 9 standard pages — all from one
codebase that runs both the intake host (americatrackingpolls.com)
and every individual candidate site.

---

## 2. The big-picture flow

```
┌──────────────────────────────────────────────────────────────────┐
│  INTAKE HOST  (americatrackingpolls.com)                         │
│                                                                  │
│  1. Candidate / ATP staff fills out the 16-step intake form      │
│     (the [atp_intake] shortcode, rendered by                     │
│     packages/atp-plugin-core/includes/intake/                    │
│     atp-candidate-intake.php)                                    │
│                                                                  │
│  2. Form submits → AJAX handler creates an `atp_candidate` post  │
│     with the structured answers stored as V3 JSON in the         │
│     `_v3_json` post meta. File uploads (headshot, logo, photos)  │
│     go to:                                                       │
│        a) WordPress media library (always)                       │
│        b) Google Drive subfolder (if Drive is connected)         │
│                                                                  │
│  3. Email notification goes to MF + ATP stakeholders.            │
│                                                                  │
│  4. Engineer downloads a Bundle ZIP from wp-admin → ATP          │
│     Candidates → click submission → Download Bundle.             │
│     Bundle contains:                                             │
│        - <slug>-v3.json      — the V3 JSON                       │
│        - <slug>-PROMPT.md    — the AI prompt template with V3    │
│                                JSON inlined; paste into any LLM  │
│        - REFERENCE.md        — links, asset URLs, instructions   │
└──────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌──────────────────────────────────────────────────────────────────┐
│  AI GENERATION  (Claude, ChatGPT, or any LLM)                    │
│                                                                  │
│  5. Paste <slug>-PROMPT.md into the LLM. The prompt instructs    │
│     it to produce a Page JSON object: one key per shortcode      │
│     section (atp_cand_hero, atp_cand_about, etc.), each value    │
│     is the rendered HTML for that section.                       │
│                                                                  │
│  6. Save the LLM's output as <slug>-page.json.                   │
└──────────────────────────────────────────────────────────────────┘
                                │
                                ▼
┌──────────────────────────────────────────────────────────────────┐
│  CANDIDATE SITE  (the candidate's own WP install / domain)       │
│                                                                  │
│  7. Provision a fresh WordPress install (SiteGround in           │
│     production; WordPress Playground for testing).               │
│                                                                  │
│  8. Install the same ATP Campaign Site plugin built via          │
│     `./scripts/build-plugin-zip.sh` → upload                     │
│     `atp-plugin-core-<version>.zip` → activate.                  │
│                                                                  │
│  9. wp-admin → ATP Demo → Candidate Page → paste the Page JSON   │
│     from step 6. This writes one `atp_sc_<tag>` override per     │
│     key into `wp_options`.                                       │
│                                                                  │
│ 10. wp-admin → ATP Demo → Import Pages → click "Import" on each  │
│     of the 9 standard page cards. This creates Home, Issues,     │
│     About, Donate, Contact, Sign Up, Privacy, Cookie & TCPA,     │
│     and Brand Guide, each laid out with the right shortcodes.    │
│                                                                  │
│ 11. Visit the front-end. Each page renders the candidate's       │
│     content because shortcodes resolve their override from       │
│     `wp_options.atp_sc_<tag>` or fall back to a dynamic/tokenized │
│     shortcode where appropriate. Token replacement substitutes    │
│     V3 JSON values into `{{display_name}}`-style placeholders.   │
└──────────────────────────────────────────────────────────────────┘
```

---

## 3. The four data layers (this is the mental model)

Every byte of content on a rendered candidate site comes from one
of four sources, resolved at render time:

| Layer | Where stored | Who writes it | When it wins |
|---|---|---|---|
| **V3 JSON** (the source of truth) | `_v3_json` post meta on the `atp_candidate` post | The intake form, on submit | Always — every other layer is a fork from this |
| **Page JSON / shortcode override** | `wp_options.atp_sc_<tag>` (one row per shortcode) | The LLM via `PROMPT-TEMPLATE.md`, pasted into the Candidate Page admin | Whenever it exists and the per-shortcode `_disabled` flag is false |
| **Per-shortcode data patch** | `wp_options.atp_sc_<tag>_data` (JSON) | An admin tweaking a single field via wp-admin → ATP Demo → Edit Shortcodes | Merged on top of V3 JSON for token substitution into the active template (override OR default) |
| **Registry default** | `packages/atp-plugin-core/includes/registry.php` (heredoc strings) | Whoever wrote the plugin | Only when (a) no override is stored or (b) `atp_sc_<tag>_disabled = 1` |

**Critical implication:** registry defaults are SAFETY NETS, not
intended runtime content for a real candidate. The defaults still
contain example John-Stacy-for-Rockwall-County content, but the
Page JSON importer now only accepts the known candidate shortcode
keys, clears missing expected keys, reports unknown keys, and allows
intentional blank overrides for optional sections. The prompt in
`PROMPT-TEMPLATE.md` is responsible for making sure the LLM generates
content for every standard shortcode that needs a candidate-specific
override: all home-page sections plus the full Issues, Donate,
Contact, Privacy, and Cookie/TCPA subpage sections.

For PHP-rendered shortcodes that depend on V3 arrays
(`atp_cand_issues`, `atp_cand_endorsements`, `atp_cand_social`),
v3.6.4 changed their no-data behavior from "fall back to registry
default" (which was the John Stacy heredoc) to "render empty
string." This means if V3 has no endorsements, the endorsements
section disappears instead of showing the example candidate's
endorsements.

---

## 4. The V3 JSON contract

Defined formally in `packages/atp-plugin-core/v3-schema.json`. Top-level
sections, in order:

| Section | Key fields |
|---|---|
| `meta` | form_version, candidate_id, submitted_at, status |
| `source_check` | submitter_name/email/phone/role, filing_url, ballotpedia_url, existing_website |
| `identity` | organization_type, legal_name, display_name, ballot_name, office_sought, district, seat_number, state, party, election_year/date/type, position |
| `contacts` | candidate, manager, treasurer (each with name/email/phone/role/address) |
| `bio` | ballotpedia_status, homepage_intro, bio_full, why_running, tagline, differentiator, key_messages[], policy_passions[], endorsements_about |
| `platform` | issue_categories[], issue_positions[{name, position}], opponents_missing_issue, changed_position |
| `background` | profession, current_role, previous_experience, education[], military_branch, military_years |
| `visual_branding` | headshot_link, logo_link, additional_photos[], color_primary/secondary/accent, visual_style, design_notes |
| `social` | facebook, twitter_x, instagram, youtube, tiktok, linkedin, social_other |
| `video` | main_video_url, other_video_url |
| `survey_page` | survey_page_wanted, primary_focus, label, intro_text, existing_survey_link, display |
| `legal_compliance` | committee_name, paidfor_text, jurisdiction, committee_id/address, campaign_phone/email, privacy_contact_*, uses_cookies, will_send_texts, sms_*, donations_by_text, third_party_analytics, shares_data, service_providers |
| `fundraising` | donation_needed, donation_platform, fundraising_platform_status, donation_url, donation_embed_code, donation_button_label, donation_button_custom, accepts_text_donations, text_donate_processor/accreditation |
| `domain_setup` | domain_status, domain_preferred, domain_primary, domain_redirects, domain_registrar, hosting_provider, domain_credentials, campaign_email_needed |
| `approval_timeline` | approver_same, approver_name/email, copy_help, launch_timeline, launch_date, comm_pref, referral_source, open_notes |
| `additional_services` | services_interest[], tier2_pages_interest[], additional_surveys_interest[] |
| `summary` | scope_acknowledgment, compliance_acknowledgment |

**Schema is a contract.** Adding a new field requires updating all
of: `v3-schema.json`, the intake form, `PROMPT-TEMPLATE.md`, and
`docs/json-schema.md`. See AGENTS.md rule 5.

**Field map:** `packages/atp-plugin-core/v3-field-map.json` maps V3
JSON paths to the `{{token}}` names used in heredoc templates.

---

## 5. The intake form

**Lives at:** `packages/atp-plugin-core/includes/intake/atp-candidate-intake.php`

**Renders via:** `[atp_intake]` shortcode (on any page)

**Steps:** 16 (source_check through summary; see Step 14 = "Grow
Beyond Your Website" = upsell signals only, not consumed by the
generator)

**Submit endpoint:** `wp-admin/admin-ajax.php?action=atp_save`
(nonce: `atp_form`). Handler in the same file.

**File upload endpoint:** `wp-admin/admin-ajax.php?action=atp_upload_file`
(nonce: `atp_form`). Handler in
`packages/atp-plugin-core/includes/file-upload.php`. Always saves
to WP media library first; mirrors to Drive if connected.

**Form architecture (post v3.6.3):**
- All event handlers wired with `addEventListener` from inside an
  IIFE (not inline `onchange=` attributes). The `atpInitUploads()`
  block walks every `[data-atp-zone]` and `[data-atp-file]` element
  at `DOMContentLoaded` and binds click/dragover/dragleave/drop/change
  listeners.
- Each upload zone has a visible status line below it
  (`#status_<fid>`) that reports "Selected → uploading… → Uploaded"
  or a precise error.
- Browser console logs every step under the `[atp-upload]` prefix.

**Submission lifecycle:**
1. JS gathers all field values from the IIFE's `D` (data) object
2. POSTs to `admin-ajax.php` with action `atp_save`
3. Server-side: handler creates an `atp_candidate` CPT post,
   stores V3 JSON in `_v3_json` post meta, fires email
   notification to addresses in `wp_options.atp_notify_emails`,
   returns the new post ID
4. JS clears localStorage and shows the thank-you screen

---

## 6. Google Drive mirror

**Auth:** OAuth 2.0 user flow (NOT service-account). Admin connects
their Google account via wp-admin → ATP Demo → White Label →
Connect Google Drive. The full setup walkthrough is in
`docs/google-drive-setup.md`.

**Scope:** `https://www.googleapis.com/auth/drive` (broad, so the
folder picker can navigate into pre-existing folders like
`Intake_Submissions_Live`).

**Storage of credentials:** `wp_options.atp_drive_oauth` =
[client_id, client_secret, refresh_token, account_email].
`wp_options.atp_drive_config` = [folder_id, folder_name]. None of
these are in the plugin source — they live only in the WP database.

**Folder structure on submit:**
```
Intake_Submissions_Live/                        <- admin picks this folder
└── YYYY-MM-DD_Candidate-Name_Office-Slug/      <- auto-created per submission
    ├── headshot_<original>.jpg
    ├── logo_<original>.png
    └── additional_photos_1_<original>.jpg
```

**Mirror logic:** see
`packages/atp-plugin-core/includes/file-upload.php` →
`atp_handle_file_upload()`. WP media save happens unconditionally;
Drive mirror runs only if `wp_options.atp_upload_storage =
'google_drive'` AND `atp_drive_is_configured()` returns true.

---

## 7. The AI generation step

**Prompt source:** `packages/atp-plugin-core/PROMPT-TEMPLATE.md`

**What the bundle gives the engineer:**
The `<slug>-PROMPT.md` file in the bundle ZIP is the prompt
template with the candidate's V3 JSON already inlined inside a
fenced code block. The engineer pastes the entire file into Claude
or ChatGPT — no manual JSON merging.

**Expected LLM output:** a single JSON object with keys for every
AI-authored shortcode (18 of them, as of the 3.6.4 prompt), plus
`_sections_order`, `_candidate`, `_generated` metadata. Each value
is HTML using the CSS classes defined in the `atp_cand_styles`
shortcode (see prompt for the class list). Optional sections with no
source material can be an empty string, which intentionally renders
nothing instead of falling back to defaults.

**Failure modes to watch for:**
- LLM returns invalid JSON (missing comma, unescaped quote) →
  Candidate Page admin will reject the paste; ask LLM to fix
- LLM omits a shortcode key → Candidate Page admin clears that
  expected override and reports the missing key. Rerun or fix the
  Page JSON before launch.
- LLM hallucinates a different jurisdiction or candidate's facts
  → rare with current prompt but rerun if it happens; do NOT paste
  hallucinated content into a real candidate's site
- LLM writes "{{display_name}}" literally instead of the actual
  name → instruct it to substitute in plaintext, not leave tokens

**Approval gate:** in current architecture there's no automated
review. The engineer reads the Page JSON before pasting it into
the candidate's WP install. Future automation (see `HANDOFF.md`
§9) could add a draft → review → publish workflow.

---

## 8. The candidate-site admin pages (wp-admin → ATP Demo)

| Submenu | Path | What you do here |
|---|---|---|
| **Dashboard** | `admin.php?page=atp-demo-shortcodes` | Top-level shortcode browser + status |
| **Edit Shortcodes** | same as above, scroll list | Edit individual shortcode templates, save overrides, toggle on/off, add per-shortcode JSON data patches, preview core vs override |
| **Candidate Page** | `admin.php?page=atp-candidate-page` | **Paste the LLM-generated Page JSON here.** This is THE main workflow step on a new candidate site. The plugin parses it and writes one `atp_sc_<tag>` row per key |
| **Import Pages** | `admin.php?page=atp-import-pages` | **Create the standard pages.** Click "Import" on each card to create that WordPress page with the right shortcodes laid out |
| **White Label** | `admin.php?page=atp-whitelabel` | Drive OAuth + brand settings (logo, colors). One-time per site |
| **ATP Candidates** | `edit.php?post_type=atp_candidate` | Submissions list (intake host only). Each row = one form submission. Drill in to view all fields, download bundle, export JSON |
| **Edit Form** | `admin.php?page=atp-intake-questions` | Customize the 16-step intake form fields (intake host only) |

---

## 9. The standard pages (Tier 1)

The Import Pages screen creates these:

| # | Page title | URL slug | Shortcodes laid out (in order) |
|---|---|---|---|
| 1 | Candidate Landing Page | `candidate-landing-page` (or set as front page) | styles, nav, hero, stats, about, messages, issues, endorsements, video, volunteer, survey, donate, social, footer |
| 2 | Issues & Answers | `issues-answers` | styles, nav, issues_page, footer |
| 3 | About | `about` | styles, nav, about, footer |
| 4 | Donate | `donate` | styles, nav, donate_page, footer |
| 5 | Contact | `contact` | styles, nav, contact, footer |
| 6 | Sign Up | `sign-up` | styles, nav, signup, footer |
| 7 | Brand Guide | `brand-guide` | styles, nav, brand_guide, footer |
| 8 | Privacy Policy | `privacy-policy` | styles, nav, privacy, footer |
| 9 | Cookie & TCPA Policy | `cookie-tcpa-policy` | styles, nav, cookies, footer |
| 10 | Candidate Intake Form | `candidate-intake-form` | intake |
| 11 | AI Start Here | `ai-start-here` | ai_context (private) |

**Tier 2 pages** (Media Kit, Endorsements page, Events Calendar,
Press/Blog, Polling Locator, FAQ) — not built into the importer.
Step 14 of the intake form captures candidate interest in these,
but they're upsell signals for the ATP sales team, not auto-
generated. Building them would require new templates and new
importer cards.

---

## 10. The override system (per-shortcode customization)

**Why it exists:** every candidate's site uses the same plugin code,
but each site needs its own content per shortcode. The override
system separates template (HTML structure) from data (V3 JSON
values) from disable state, so you can customize ONE section
without forking the whole plugin.

**Storage cheat sheet:**

| Option key | Purpose |
|---|---|
| `wp_options.atp_sc_<tag>` | Override HTML for the shortcode template |
| `wp_options.atp_sc_<tag>_data` | JSON data patch (merged on top of V3 JSON for token substitution) |
| `wp_options.atp_sc_<tag>_disabled` | If truthy, ignore the stored override and use the registry default |

**Render-time resolution** (see `atp_demo_resolve_template()` in
`packages/atp-plugin-core/includes/shortcodes.php`):
1. If shortcode is called with `source="core"` attribute → registry default
2. If shortcode is called with `source="override"` attribute → stored override (or default if none stored)
3. Else if `atp_sc_<tag>_disabled = 1` → registry default
4. Else if `atp_sc_<tag>` exists → stored override
5. Else → registry default

**Why this matters:** the typical candidate-site setup flow stores
overrides for every `atp_cand_*` shortcode via the Candidate Page
admin paste. The disable toggle lets you A/B against the default
without losing the customization. The `source="core|override"`
attribute lets you preview either source on a single page (drop
two copies of the shortcode with different `source=` values).

---

## 11. The Vibe AI / MCP integration

**Vibe AI** ([wordpress.org/plugins/vibe-ai](https://wordpress.org/plugins/vibe-ai/))
is a WP plugin that exposes the site as an MCP (Model Context
Protocol) server. The ATP plugin declares it as a required
dependency (`Requires Plugins: vibe-ai` in the plugin header).

**What our plugin exposes for AI clients:**
- REST endpoint `GET /wp-json/atp/v1/site-context` (auth:
  `current_user_can('edit_posts')`)
- Returns: plugin version, site role (intake-host / candidate /
  unconfigured), candidate identity, V3 JSON snapshot, shortcode
  list with current override states, page list with shortcode
  usage, decision tree of edit patterns
- Defined in `packages/atp-plugin-core/includes/ai-context.php`
- Same data is available as HTML at `/ai-start-here` (private
  page, admin-only)

**Claude skill:**
`.claude/skills/atp-site-edit/SKILL.md` — an operating manual that
any AI client should read on first connecting to an ATP site.
Covers the 5 edit categories (content vs. template override vs.
data patch vs. toggle vs. importer) and the hard rules (never edit
page content containing shortcodes, never delete overrides to test
core, never invent V3 fields, etc.).

---

## 12. Testing the whole pipeline end-to-end

**Fast path: Playground**

Two Playground blueprints are kept in the repo:

| File | What it boots |
|---|---|
| `playground-blueprint.json` | Full John Stacy demo candidate site (9 pages, all shortcodes populated) — useful for showing what a finished site looks like |
| `playground-blueprint-intake-test.json` | Minimal intake-form-only install — useful for testing form submission, file upload, console logging |

Open the demo by URL:

```
https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/America-Tracking-Polls/ATP-Candidates/main/playground-blueprint.json
```

For the intake-form-only test variant:

```
https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/America-Tracking-Polls/ATP-Candidates/claude/activate-drive-upload-P3yOj/playground-blueprint-intake-test.json
```

**Production install path:**

1. From a clean clone:
   ```
   git pull origin main
   ./scripts/build-plugin-zip.sh
   ```
   That emits `atp-plugin-core-<version>.zip` at the repo root.
2. wp-admin → Plugins → Add New → Upload → pick the ZIP → Activate.
3. Smoke test per `HANDOFF.md` §5 checklist.

**Diagnostic UI in the intake form:**

Every upload zone now has a status line below it. Watch for:
- "Selected foo.jpg — uploading…" (gray) → submission accepted
  client-side; XHR in flight
- "Uploaded foo.jpg" (green) → server accepted; URL written into
  V3 data; will be in Drive subfolder on submit
- "Upload failed (HTTP 403)..." (red) → SiteGround Anti-Bot AI WAF
  blocking the AJAX endpoint; open a ticket
- "Upload failed: network error..." (red) → WAF, firewall, or
  offline
- Browser console: `[atp-upload]` lines log every step (init,
  change, accepted, xhr.send, xhr.onload status, success or error)

---

## 13. Common operations cheat sheet

**Make a small content edit on a single candidate's site**
→ wp-admin → ATP Demo → Edit Shortcodes → find the shortcode →
edit the template HTML → Save

**Make a content edit that should affect EVERY candidate site**
→ Edit the registry default in
`packages/atp-plugin-core/includes/registry.php` → bump plugin
version → cut a release → auto-updater pushes to every site that
hasn't customized that shortcode

**Push a new candidate site from intake JSON to live**
→ Download bundle → paste PROMPT.md into Claude → save Page JSON
→ provision WP install → install plugin → ATP Demo → Candidate
Page → paste Page JSON → Import Pages → click each card

**Disable an override and see the core template**
→ wp-admin → ATP Demo → Edit Shortcodes → toggle "disabled" on
the shortcode → the stored override stays in the database but
the default renders

**Preview core vs. override side-by-side on one page**
→ Drop both `[atp_cand_hero source="core"]` and
`[atp_cand_hero source="override"]` on a test page

**Test changes without breaking production**
→ Boot a Playground instance with the dev branch URL → make
changes → confirm → merge to main → cut release

---

## 14. Where to look when something breaks

| Symptom | Most likely cause | First file to read |
|---|---|---|
| Upload form does nothing on click | JS scope/wiring issue (pre-3.6.3) — fixed in 3.6.3 with `addEventListener` | `includes/intake/atp-candidate-intake.php` lines ~1090–1210 |
| Upload fails with 403 in console | SiteGround Anti-Bot AI WAF | SG support ticket; whitelist `POST /wp-admin/admin-ajax.php` for `action=atp_upload_file` |
| Site renders other candidate's content | Stale installed plugin, disabled override, or a manually forced `source="core"` shortcode. Current Page JSON imports clear missing keys and PHP renderers honor imported overrides first. | `includes/candidate-page.php`, `includes/shortcodes.php`, `PROMPT-TEMPLATE.md` |
| Drive folder empty after submit | (a) WP didn't receive the upload (see row 1), or (b) Drive mirror not configured | `includes/file-upload.php` + wp-admin → White Label |
| OAuth state mismatch banner persists | Callback query args sticking in URL — fixed in 3.6.3 with transient + redirect | `includes/whitelabel.php` callback handler |
| Plugin install fails with "no valid plugins found" | Wrong ZIP — you uploaded the GitHub repo ZIP instead of the build-script output | Run `./scripts/build-plugin-zip.sh` |
| Fatal error about `Cannot redeclare atp_default_questions` | Two intake plugins active — the canonical full plugin + the legacy root-level file (pre-3.6.2 — file is now at `legacy/atp-candidate-intake.php` with header stripped) | Delete the duplicate plugin folder via SFTP |

---

## 15. Repo map

```
ATP-Demo/
├── packages/atp-plugin-core/          ← canonical plugin (THE source of truth)
│   ├── atp-demo-plugin.php            ← bootstrap; bumped per release
│   ├── includes/
│   │   ├── intake/                    ← intake form (only loads on intake host)
│   │   ├── shortcodes.php             ← generic renderer + override resolution
│   │   ├── candidate-page.php         ← Candidate Page admin + PHP-rendered shortcodes
│   │   ├── importer.php               ← Import Pages admin + page set definitions
│   │   ├── registry.php               ← every shortcode + its default heredoc HTML
│   │   ├── file-upload.php            ← AJAX handler for file uploads + Drive mirror
│   │   ├── drive-client.php           ← Google Drive OAuth + REST client
│   │   ├── whitelabel.php             ← brand + Drive admin
│   │   ├── ai-context.php             ← Vibe AI / MCP site-context endpoint
│   │   ├── signup.php                 ← [atp_cand_signup] subscriber form
│   │   ├── marketing-shortcodes.php   ← [atp_mkt_*] for ATP marketing site
│   │   └── ...
│   ├── PROMPT-TEMPLATE.md             ← the canonical AI prompt
│   ├── v3-schema.json                 ← V3 JSON contract
│   ├── v3-field-map.json              ← V3 JSON path → template token mapping
│   ├── OVERRIDE-SYSTEM.md             ← full override system writeup
│   ├── ARCHITECTURE.md                ← system architecture + diagrams
│   └── CHANGELOG.md                   ← per-version notes
│
├── scripts/
│   ├── build-plugin-zip.sh            ← THE supported way to build the install ZIP
│   ├── new-site.sh                    ← scaffold a new per-client site folder
│   └── build-site.sh                  ← assemble per-client deployable plugin
│
├── sites/<slug>/                      ← per-candidate config (V3 JSON, page-json.json, etc.)
├── campaign-site/                     ← Sarah Chen demo (reference template)
├── personal-site/                     ← Michael Torres demo (reference template)
│
├── docs/
│   ├── SYSTEM-RUNDOWN.md              ← this file
│   ├── candidate-site-flow.md         ← 7-phase end-to-end flow doc
│   ├── google-drive-setup.md          ← Drive OAuth walkthrough
│   ├── json-schema.md                 ← V3 JSON field guide
│   ├── editing.md, deployment.md, etc.
│
├── legacy/                            ← inert reference files; never loaded
├── playground-blueprint.json          ← full demo site blueprint
├── playground-blueprint-intake-test.json ← intake-only test blueprint
│
├── README.md                          ← entry point with quick-link table
├── AGENTS.md                          ← rules for AI agents working in this repo
├── HANDOFF.md                         ← status of every component + next actions
├── MASTER-PLAN.md                     ← 5 architecture diagrams
└── EDIT_LOG.md                        ← running history of every change
```

---

## 16. Versioning and releases

**Semver.** Major = schema break or full pipeline change. Minor =
new shortcode / new admin feature. Patch = bugfix.

**Process:**
1. Bump the version in `packages/atp-plugin-core/atp-demo-plugin.php`
   (both the header `Version:` line and `ATP_DEMO_VERSION` constant)
2. Add a CHANGELOG entry
3. Commit with a clear message
4. Push to main
5. Tag a GitHub release with `v<version>` and push the tag.
   GitHub Actions builds every folder in `sites/` and attaches one
   `atp-campaign-site-<client-slug>.zip` asset per client. Each
   installed client plugin reads its bundled `site-config.json`
   `release_asset` value, so WordPress updates download the correct
   client zip from the shared release.

---

## 17. Glossary

| Term | Meaning |
|---|---|
| **Intake host** | The single WordPress install where candidates submit the intake form. Currently americatrackingpolls.com |
| **Candidate site** | A WordPress install dedicated to one candidate, running the same plugin, on their own domain |
| **V3 JSON** | The structured intake submission. Schema in `v3-schema.json` |
| **Page JSON** | The LLM-generated HTML-per-shortcode object pasted into the Candidate Page admin |
| **Shortcode** | WordPress `[atp_*]` token rendered by the plugin into HTML |
| **Override** | Per-site stored HTML that replaces the registry default for one shortcode |
| **Data patch** | Per-shortcode JSON merged on top of V3 JSON for token substitution |
| **Registry default** | The shipped fallback HTML for a shortcode, used when no override exists |
| **Token** | `{{display_name}}`-style placeholder in template HTML, substituted from V3 JSON at render time |
| **Bundle** | The ZIP downloaded from wp-admin per submission. Contains REFERENCE.md, V3 JSON, PROMPT.md |
| **Tier 1** | The standard 9 pages the importer creates |
| **Tier 2** | Optional add-on pages flagged in intake Step 14 — not auto-built |
| **MF / ATP** | Mirror Factory (vendor) / America Tracking Polls (client) |

---

*If you're an LLM reading this for context: load
`packages/atp-plugin-core/v3-schema.json` and
`packages/atp-plugin-core/PROMPT-TEMPLATE.md` next. Together with
this rundown, those three files contain the full contract needed
to operate the system.*
