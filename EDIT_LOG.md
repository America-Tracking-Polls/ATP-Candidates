# Edit Log

> **Convention:** Whenever edits are made to this repository — code,
> content, copy, assets, configuration, anything — **always add an
> entry to this edit log**, dated, with the affected files and a brief
> description. New entries go at the **top** (most recent first). This
> applies to humans, AI assistants, and automation alike.
>
> Format for each entry:
>
> ```
> ## YYYY-MM-DD — Short title
>
> **Branch:** `branch-name` &nbsp; **Commits:** `abc1234`, `def5678`
>
> Optional 1–2 sentence summary.
>
> ### Done
> - bullet list
>
> ### In progress / blocked
> - bullet list with reason
> ```

---

## 2026-05-05 — Intake handoff: default emails, richer notification, bundle export

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_
**Files:** `packages/atp-plugin-core/includes/intake/atp-candidate-intake.php`
(mirrored to `atp-demo-plugin/includes/intake/`)

User asked for the manual intake → site-build handoff to be much
faster. Three concrete changes that compress the engineer's "steps
1–5" into a one-click download.

### Done

- **Default notify emails** in `atp_get_settings()` — `alfonso@mirrorfactory.com`, `gary@americatrackingpolls.com`, `dan@americatrackingpolls.com`. Still editable in WP Admin → ATP → Intake Settings.
- **Richer notification email** in `atp_send_notifications()`:
  - Adds the suggested slug (`atp_intake_suggested_slug` — e.g. `sarah-chen-2026`) as a row in the summary table
  - Three CTA buttons in the body: **View Submission**, **Download Bundle (zip)**, **Open Drive Folder** (when Drive is configured)
  - Inline note explaining what's in the bundle
- **New bundle export** at `wp_ajax_atp_export_bundle`:
  - Generates a zip with **3 files**:
    - `REFERENCE.md` — links + step-by-step engineer instructions
    - `<slug>-v3.json` — the V3 JSON, ready to paste
    - `<slug>-PROMPT.md` — `PROMPT-TEMPLATE.md` from the plugin with the V3 JSON inlined; ready to paste into Claude/ChatGPT
  - Falls back to a plain-text concatenation if `ZipArchive` isn't available on the host
- **Download button** on the candidate admin detail view: **⬇ Download Intake Bundle (zip)** is now the primary action; the old "Download JSON only" stays as a secondary button

### What this changes about the manual flow
Previous workflow steps 1–5 were: read email → manually navigate to WP admin → export JSON → invent slug → run `new-site.sh` → paste JSON → paste prompt template → save output. That's ~20 minutes.

New workflow: **click the bundle link in the email → unzip → follow the REFERENCE.md instructions** (which also contain the suggested slug). The bundle includes the prompt with the V3 already inlined, so you paste one file into Claude. ~5 minutes.

### Skipped — needs decision
- The Drive subfolder URL in the email currently links to the parent folder (we don't yet persist per-submission subfolder IDs at upload time). Follow-up: store `_atp_drive_folder_id` post meta when the first file uploads, and link directly to that subfolder.
- Vibe AI plugin (https://wordpress.org/plugins/vibe-ai/) for MCP integration on both sites. User identified this as the chosen MCP plugin. Not yet bundled / wired — addressed in next commit.

---

## 2026-05-05 — Master plan diagrams

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_
**File:** `MASTER-PLAN.md` (new, repo root)

Captured the architecture decisions from this session's interview
into five ASCII diagrams + commentary. Lives at repo root alongside
`AGENTS.md` and `EDIT_LOG.md` so it's discoverable without spelunking.

### Diagrams

1. **System topology** — clarifies that there is no "one plugin
   containing all candidates"; each candidate has their own WP, and
   the MCP server is what unifies the team's view.
2. **Edit lifecycle** — request → triage → MCP edit or MF code
   change → Playground preview → push live → audit log.
3. **Release channels + election freeze** — `stable` / `frozen` /
   `beta`; auto-freeze 30 days before election day; force-deploy
   escape hatch for security fixes.
4. **Customization decision tree** — three lanes (content edit,
   core feature, one-off override) with a 12-month "would another
   candidate benefit?" test, and the post-release override conflict
   detector.
5. **Site lifecycle** — intake → build → live → freeze → election
   → unlock → archive → choose-your-ending, with ATP sales
   touchpoints called out.

### Done
- New `MASTER-PLAN.md` at repo root
- Five ASCII diagrams + per-diagram commentary
- Closing summary tying the diagrams together for "many
  candidates, one plugin"

---

## 2026-05-05 — Update Playground blueprint to current branch + canonical plugin

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_

`playground-blueprint.json` was pointing at a stale branch
(`claude/tier1-intake-form-uq7Mc`) and the legacy `atp-demo-plugin/`
folder (v2.1.0). Updated to point at the current working branch and
the canonical `packages/atp-plugin-core/` (v3.2.0) so the Playground
link boots the live OAuth Drive integration.

### Done
- Blueprint `ref` → `refs/heads/claude/activate-drive-upload-P3yOj`
- Blueprint `path` → `packages/atp-plugin-core`

### Follow-up
After this branch is merged into `main`, swap `ref` back to
`refs/heads/main` so the Playground link is stable across future
branches.

---

## 2026-05-05 — Drive integration: switched from service account to OAuth user flow

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_

User asked for OAuth user flow + visual folder picker + always-keep-WP-copy
instead of the service-account architecture shipped on 3.1.0. Plugin
bumped to 3.2.0.

### Done

- **Auth method swapped.** `drive-client.php` rewritten:
  - Removed service-account JWT/RS256 signing
  - Added OAuth authorize URL builder, callback handler, refresh-token
    cache, access-token transient (~55 min TTL)
  - New helpers: `atp_drive_oauth_get/set/clear_tokens`,
    `atp_drive_redirect_uri`, `atp_drive_authorize_url`,
    `atp_drive_handle_oauth_callback`, `atp_drive_list_folders`,
    `atp_drive_get_folder_meta`, `atp_drive_ajax_browse`
  - Kept (and reused) `atp_drive_find_or_create_folder` and
    `atp_drive_upload_file` — they didn't depend on auth method
- **Settings UI rebuilt** in `whitelabel.php`:
  - Removed: "Service Account JSON Path" field, manual folder ID field
  - Added: OAuth Client ID + Secret fields, Authorized redirect URI
    display, Connect / Disconnect / Test Connection buttons,
    connected-account email display, "Browse my Drive…" modal that
    calls the new AJAX endpoint to navigate folders and pick one
  - "Drive mirroring" dropdown reframed: "WordPress only" vs
    "WordPress + Google Drive" (was "WordPress Media Library" vs
    "Google Drive")
- **Upload routing changed** in `file-upload.php`:
  - WP media library is now **always** saved (no longer a fallback)
  - Drive is **always** an additional mirror when configured
  - On Drive failure: `error_log()` + WP-only result; submission still succeeds
- **AJAX endpoint** `wp_ajax_atp_drive_browse` registered in
  `drive-client.php`, capability-checked + nonce-protected, returns
  JSON list of subfolders for the in-admin picker
- **Docs rewritten** — `docs/google-drive-setup.md` walks through
  Cloud Console → OAuth client → Connect → Pick folder → Test, plus
  full troubleshooting section
- **Mirrored to legacy** `atp-demo-plugin/` folder; PHP lint clean on
  all 6 files (3 canonical + 3 legacy)
- **Plugin version bumped** 3.1.0 → 3.2.0; CHANGELOG entry includes
  migration notes from 3.1.0

### Architecture decision: server-side folder browser, not Google Picker JS

Google Picker API would require an additional API key + Picker API
enable in Cloud Console. Instead, the picker is a small in-admin
modal that calls the Drive `files.list` API via a WP AJAX endpoint
(reusing the OAuth access token) and lets the user navigate folders
with breadcrumbs. Less polished UX than Google Picker but no extra
Cloud setup and one less credential to manage.

### Skipped — needs input
- ATP staff still needs to create an OAuth Client ID in Google Cloud
  (instructions in `docs/google-drive-setup.md`).
- The previously-uploaded service-account JSON key (from the
  Bitwarden Send link earlier in the project) is no longer used. If
  it was placed on a server, it can be deleted.

---

## 2026-05-05 — Complete the candidate-platform / ATP-marketing split

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_

Finished separating the candidate-platform monorepo from the ATP
marketing-site files. Previously the `atp-website` branch had the
marketing files but they hadn't been removed from this branch — now
they are. Also added the JSON-contract rule to `AGENTS.md` and
introduced an ATP-branded intake landing page as the new
`index.html`.

### Done

- **Removed from this branch** (now exclusive to `atp-website`):
  - `ATP-Homepage-Mockup.html`
  - `brand-guide.html`
  - `ATP-Logo-Blue-White.png`, `ATP-Logo-Red-White.png` (still
    available in `packages/atp-plugin-core/assets/images/`)
  - `css/brand.css`, `js/brand-*.js` (six brand JS files)
- **Kept at repo root**: `ATP-Logo-Standard.png` (referenced by the
  new `index.html`).
- **Replaced `index.html`** with an ATP-branded intake-onboarding
  landing page. It is meant to be the homepage of whichever WP
  install hosts the `[atp_intake]` shortcode — typically ATP's
  candidate-onboarding host. It explains the demo experience, why
  the intake matters, what we'll ask about (6 pillars), and what
  happens after submission (4 steps), with two CTAs pointing at
  `/candidate-intake-form/`. Notes inline that the page can be
  removed or replaced once the candidate's own site goes live.
- **Updated `AGENTS.md`** with the JSON-contract rule (rule #6):
  any edit to candidate-site templates (plugin shortcodes, demo
  mockups in `campaign-site/` and `personal-site/`) MUST stay
  compatible with the V3 JSON contract. Don't rename placeholders,
  don't add required fields without schema bumps, gracefully
  handle missing fields. The two demo sites are positioned as
  **foundational examples**, not throwaway demos.
- **Updated branch description** in AGENTS.md to remove the
  "transitional" framing — the split is now real on this branch.

### Why the JSON-contract rule

The candidate-site plugin's templates are fed by specific paths in
`v3-schema.json` via `v3-field-map.json`. If a template removes a
placeholder, renames a field, or adds a new required input without
extending the schema, intake submissions can produce broken pages.
Both the in-plugin templates and the static demo mockups
(`campaign-site/`, `personal-site/`) need to track the schema so the
demos stay portable back to the plugin.

### Skipped — needs input

(unchanged from prior entries — Hero MP4, Typeform embed, BIO/SLOGAN
section source, WIN BEFORE ELECTION DAY graphic placement, etc.)

---

## 2026-05-05 — AGENTS.md + atp-website branch carve-out

**Branch:** `claude/activate-drive-upload-P3yOj` (this branch),
&nbsp; new branch `atp-website` for marketing-site export
**Commits:** _pending push_

Set up two structural items the user requested:

1. **AGENTS.md** at the repo root — operating rules for AI coding
   agents working in this repo. Codifies the EDIT_LOG.md convention,
   the two-repo split plan, the canonical-vs-legacy plugin folder
   rule, secret-handling rules, and a pre-commit checklist. Any AI
   agent (Claude Code, Cursor, Aider, etc.) starting a session here
   should read this file first.

2. **`atp-website` branch** carved out of the current state of this
   repo. Contains only ATP marketing-site files (the homepage
   mockup, brand guide, top-level demo `index.html`, ATP logos,
   `css/brand.css`, `js/brand-*.js`) so it can be exported as a
   standalone repo. The candidate platform stays here on `main` /
   working branches. Nothing was deleted from this branch.

### Verified

- Full intake form is functional: 1094-line
  `packages/atp-plugin-core/includes/intake/atp-candidate-intake.php`
  with `wp_ajax_atp_save` + `wp_ajax_nopriv_atp_save` handlers, the
  `[atp_intake]` shortcode, file uploads routed through
  `file-upload.php` to either WP media or Drive (via the new
  `drive-client.php`), and email notifications via `wp_mail()` to
  `atp_settings.notify_emails`.

### Done

- New `AGENTS.md` at repo root
- New `atp-website` branch with marketing-site files only
  (cherry-picked, not removed from this branch)
- This entry logged

### Noticed but didn't touch

- The legacy `atp-demo-plugin/` folder (v2.1.0) and the canonical
  `packages/atp-plugin-core/` (v3.1.0) are still both maintained.
  AGENTS.md formalizes that v3 is canonical. Once v3 has rolled out
  to all live client sites, v2 can be removed in a separate cleanup
  commit.

---

## 2026-05-05 — Architecture documentation

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_
**File:** `packages/atp-plugin-core/ARCHITECTURE.md` (new)

Authored an architecture & intake-flow doc for the plugin, placed
inside `packages/atp-plugin-core/` so it travels with every client
deploy.

### Done

- Diagram 1: repository / monorepo layout
- Diagram 2: intake submission lifecycle (form → `wp_ajax_atp_save` →
  post + meta + email + Drive)
- Diagram 3: from intake to live candidate site (`new-site.sh` → AI →
  `build-site.sh` → deploy)
- Two-roles section explaining how the same codebase serves the intake
  host and each candidate site
- Data-lineage diagram (intake → V3 JSON → page-json → dist → live WP)
- FAQ covering: shared install vs per-client, automation level,
  upload destinations, schema overrides, plugin updates

---

## 2026-05-05 — Landing-page revisions + Drive integration status

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** `9756de2`, `00bd00c`, `5b6cc40`, `5537ff1`

Dated snapshot of every requested edit from the 9 review slides
(2 batches: 5 slides + 4 slides = 45 individual requests), plus the
Google Drive upload work that was active in parallel.

### Google Drive upload integration

| # | Task | Status | Commit |
|---|------|--------|--------|
| D1 | Implement service-account JWT auth + token caching | ✅ Done | `9756de2` |
| D2 | Implement folder find-or-create | ✅ Done | `9756de2` |
| D3 | Implement multipart file upload | ✅ Done | `9756de2` |
| D4 | Wire `atp_drive_upload()` to use real Drive client | ✅ Done | `9756de2` |
| D5 | Add WP admin field for service-account JSON path | ✅ Done | `9756de2` |
| D6 | Add "Test Drive Connection" button | ✅ Done | `9756de2` |
| D7 | Mirror changes to legacy `atp-demo-plugin/` folder | ✅ Done | `9756de2` |
| D8 | Update `.gitignore` to block service-account JSON files | ✅ Done | `9756de2` |
| D9 | Bump plugin version + changelog entry | ✅ Done | `9756de2` |
| D10 | Write deployment / setup guide | ✅ Done | `00bd00c` (`docs/google-drive-setup.md`) |
| D11 | Place JSON key on WP server, configure plugin, test | ⏳ Awaiting user (server-side step) | — |

### Landing page — first slide batch (5 slides, items 1–22)

#### Slide 1 — BIO/SLOGAN + AEO + AmeriTrack section

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 1 | Remove the entire first paragraph ("Make sure you optimize your BIO and SLOGAN…") | ❌ Skipped | Content not in `ATP-Homepage-Mockup.html` — lives on the deployed site / different source |
| 2 | Make the next paragraph larger ("Traditional SEO is no longer enough…") | ❌ Skipped | Same — content not in this repo |
| 3 | "Test Drives page" link — leave as-is | ❌ N/A | Link doesn't exist in this file |
| 4 | "Contact Us" link → repoint to top-of-page survey link | ❌ Skipped | Link doesn't exist in this file |
| 5 | Remove the entire "About AmeriTrack Polls" section | ❌ Skipped | Section not in this file |

#### Slide 2 — Video + ATP red-box copy

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 6 | Replace the video with a new one | ⚠️ Placeholder | `<video src="">` element added at line ~325; `src` empty pending MP4 |
| 7 | Add "Schedule your free consult" above the survey | ❌ Misplaced | I retitled the *intake* section heading; you wanted it above the *SMS-survey* simulation |
| 8 | Make the survey view taller | ❌ Missed | iPhone mockup still at original height |
| 9 | Remove the name "America Tracking Polls" from headline | ✅ Done | `5b6cc40` |
| 10 | New headline: "5 coordinated multi-media channels help reach out to 95% of registered voters." | ✅ Done | `5b6cc40` |
| 11 | New body opener: "America Tracking Polls delivers the most powerful integrated solution…" | ✅ Done | `5b6cc40` |
| 12 | Continue with existing "We combine Answer Engine Optimized data…" sentence | ✅ Done | `5b6cc40` |

#### Slide 3 — Required components list

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 13 | Hero MP4 with application below it | ⚠️ Structure done | Markup + CSS in place; MP4 source empty |
| 14 | Typeform Campaign Application | ⚠️ Structure done | Placeholder div in place; embed not pasted |
| 15 | Quick-view Benchmark Survey **combined with** social-media examples | ❌ Skipped | Social-media examples block not located |
| 16 | Sample Typeform Benchmark Survey | ❌ Skipped | Distinct embed/URL needed |

#### Slide 4 — Asset

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 17 | "WIN BEFORE ELECTION DAY" graphic | ❌ Skipped | No placement specified, no image file in repo |

#### Slide 5 — Scrolling header strip

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 18 | Remove standalone "AEO" item | ✅ Done | `5b6cc40` |
| 19 | Keep "5 Strategies — Proven High Engagement Coordinated Voter Outreach" | ✅ Done | `5b6cc40` |
| 20 | Keep "Compliance" | ✅ Done | `5b6cc40` |
| 21 | Keep/add "AI Optimized — Your campaign dominates AI search results (AEO)" | ✅ Done | `5b6cc40` |
| 22 | Keep/add "Data Insights — Persistent real-time reporting, survey results crosstabs" | ✅ Done | `5b6cc40` |

### Landing page — second slide batch (4 slides, items 23–45)

#### Slide 6 — "The rest of the landing page"

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 23 | Add Hero Video | ⚠️ Placeholder | `<video>` markup added; src empty |
| 24 | "POLLING POWERED" → "MULTI-MEDIA CAMPAIGN MARKETING" | ✅ Done | `5b6cc40` |
| 25 | Remove contact form; add the Typeform | ⚠️ Partial | Form removed ✓; Typeform = placeholder div only |
| 26 | Hero text: "WIN YOUR ELECTION BEFORE ELECTION DAY." | ✅ Done | `5b6cc40` |
| 27 | New hero body: "Every channel we deliver — five high-response MMS surveys…" | ✅ Done | `5b6cc40` |
| 28 | Decide if 2 hero CTAs are still needed once Typeform is below the video | ❌ Pending | Currently both CTAs left in place, no decision made |

#### Slide 7 — What You Learn / How It Powers

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 29 | Lead-in: "…giving you instant, real-time toplines and actionable intelligence." | ✅ Done | `5b6cc40` |
| 30 | "What You Learn" — 5-bullet list (exact issues, winning/losing, undecided, vulnerabilities, real intelligence) | ✅ Done | `5b6cc40` |
| 31 | "How It Powers Your Campaign" — 4 paragraphs | ✅ Done | `5b6cc40` |
| 32 | Bold close: "This is how campaigns stop reacting — and start controlling the outcome." | ✅ Done | `5b6cc40` |

#### Slide 8 — Strategic Path / Converting Data Into Action

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 33 | "Your Strategic Path" — no changes | ✅ Left untouched | `5b6cc40` |
| 34 | "Converting Data Into Action" — current text fine | ✅ Left untouched | `5b6cc40` |
| 35 | MAIL → QR-coded Print | ✅ Done | `5b6cc40` (also swapped the icon to a QR-code SVG) |
| 36 | SMS → MMS | ✅ Done | `5b6cc40` |
| 37 | Eliminate small text (unreadable) | ✅ Done | `5b6cc40` (removed `p-branch-desc` divs) |
| 38 | "The Voting Line…" — fine as is | ✅ Left untouched | — |
| 39 | "The ChatGPT Box" — amazing, text perfect | ✅ Left untouched | — |
| 40 | "Compliance" + text boxes — perfect | ✅ Left untouched | — |

#### Slide 9 — Footer / contact / nav

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 41 | Second CTA at the bottom | ✅ Done | `5b6cc40` |
| 42 | Add phone (202) 815-4637 | ✅ Done | `5b6cc40` (intake section + footer) |
| 43 | Add email info@americatrackingpolls.com | ✅ Done | `5b6cc40` |
| 44 | Add "view more survey samples @ ameritrackpolls.com" link | ✅ Done | `5b6cc40` |
| 45 | Add existing compliance pages to navigation | ✅ Done | `5b6cc40` (Compliance / AI Ethics / Privacy nav links + matching IDs on trust cards) |

### Tally

| Status | Count |
|---|---|
| ✅ Done | 30 |
| ⚠️ Placeholder / partial (waiting on assets) | 4 (items 6, 13, 14, 23/25) |
| ❌ Missed or done incorrectly (fixable now) | 3 (items 7, 8, 28) |
| ❌ Skipped — needs input | 8 (items 1, 2, 3, 4, 5, 15, 16, 17) |
| ⏳ Awaiting user action (server-side) | 1 (D11) |

### Files touched in this update

```
ATP-Homepage-Mockup.html
packages/atp-plugin-core/atp-demo-plugin.php
packages/atp-plugin-core/CHANGELOG.md
packages/atp-plugin-core/includes/drive-client.php  (new)
packages/atp-plugin-core/includes/file-upload.php
packages/atp-plugin-core/includes/whitelabel.php
atp-demo-plugin/includes/drive-client.php           (new)
atp-demo-plugin/includes/file-upload.php
atp-demo-plugin/includes/whitelabel.php
docs/google-drive-setup.md                          (new)
docs/landing-page-status-2026-05-04.md              (new — superseded by this log)
.gitignore
```

### What's next

- (No input needed) Fix items 7, 8, and 28 — the three "missed or done wrong" items.
- (Awaiting user) Provide MP4 file for item 6/23, Typeform embed for 14/25, source location for slide-1 BIO/SLOGAN content (items 1–5), placement decision for "WIN BEFORE ELECTION DAY" graphic (17), and clarification on the Benchmark Survey + social-media examples block (15) and standalone Sample Typeform Benchmark Survey (16).
- (Awaiting user) Deploy Drive credentials on the WP server (D11) and run **Test Drive Connection** in WP admin.
