# ATP Plugin — Architecture & Intake Flow

**Last reviewed:** 2026-05-05
**Plugin version:** 3.1.0
**Audience:** Mirror Factory engineers, ATP staff onboarding new clients,
and anyone debugging an intake submission or new-site deployment.

This document explains how a single plugin codebase serves both ATP's
marketing site (where candidates submit intake forms) **and** every
candidate's own campaign site, what happens when a form is submitted,
and how a new client site gets provisioned. ASCII diagrams are
included so this renders in GitHub, the WP admin file viewer, or any
plain-text reader.

> **If you're skimming, read the three diagrams in order:** repository
> layout → intake submission lifecycle → new-site deployment. The rest
> is reference.

---

## TL;DR

- **One plugin codebase** lives in `packages/atp-plugin-core/`.
- **One repo, many sites.** Per-client config and content live in
  `sites/<client-slug>/`. The build script combines core + client
  config into a deployable plugin.
- **Two runtime roles** for the same plugin:
  1. **Intake host** — collects 16-step intake submissions from
     candidates. Lives on ATP's marketing site (and any sandbox).
  2. **Candidate site** — renders the candidate's 7-page campaign
     site from the JSON produced by intake. One per client.
- **Intake submissions** go through `wp_ajax_atp_save` →
  `atp_candidate` post type + `_v3_json` post meta + email
  notification + (optional) Google Drive uploads.
- **New site provisioning** is a 3-step shell flow:
  `new-site.sh` (scaffold) → AI generates `page-json.json` →
  `build-site.sh` (bundle) → deploy to client WordPress.

---

## Diagram 1 — Repository layout

```
mirror-factory/ATP-Demo                       ← THIS REPO (monorepo)
│
├── packages/
│   └── atp-plugin-core/                      ← THE SHARED PLUGIN (one codebase)
│       ├── atp-demo-plugin.php               ← bootstrap, defines, requires
│       ├── includes/
│       │   ├── registry.php                  ← shortcode registry (atp_cand_*)
│       │   ├── shortcodes.php                ← shortcode rendering engine
│       │   ├── admin.php                     ← shortcode editor admin UI
│       │   ├── importer.php                  ← one-click page creation
│       │   ├── candidate-page.php            ← Page-JSON → shortcode importer
│       │   ├── whitelabel.php                ← brand/login/admin + Drive settings
│       │   ├── site-config.php               ← reads site-config.json on activate
│       │   ├── file-upload.php               ← upload router (WP media | Drive)
│       │   ├── drive-client.php              ← Google Drive API client
│       │   ├── setup-wizard.php              ← first-run onboarding
│       │   ├── changelog.php                 ← version history viewer
│       │   ├── updater.php                   ← GitHub auto-updater
│       │   └── intake/
│       │       └── atp-candidate-intake.php  ← 16-step intake form + handler
│       ├── v3-schema.json                    ← JSON contract (the source of truth)
│       ├── v3-field-map.json                 ← form field id → JSON path map
│       ├── PROMPT-TEMPLATE.md                ← AI prompt for Page JSON
│       └── ARCHITECTURE.md                   ← this file
│
├── sites/                                    ← PER-CLIENT data (one folder each)
│   └── john-stacy/
│       ├── site-config.json                  ← name, colors, domain, page list
│       ├── intake-v3.json                    ← completed intake (V3 schema)
│       ├── page-json.json                    ← AI-generated section HTML
│       └── page-overrides/                   ← optional manual shortcode overrides
│
├── scripts/
│   ├── new-site.sh        <slug> "Name" "Tagline"   ← scaffold sites/<slug>/
│   └── build-site.sh      <slug>                    ← assemble dist/<slug>/...
│
├── dist/                                     ← BUILD OUTPUT (gitignored)
│   └── john-stacy/
│       └── atp-campaign-site/                ← deployable plugin (zip & ship)
│
├── docs/
│   ├── google-drive-setup.md                 ← Drive credentials + admin steps
│   ├── deployment.md                         ← SiteGround / WP install steps
│   ├── json-schema.md                        ← V3 JSON reference
│   └── ...
│
├── playground-blueprint.json                 ← WordPress Playground demo
├── EDIT_LOG.md                               ← running edit history
└── README.md                                 ← high-level overview

──────────────────────────────────────────────────────────────────────
SEPARATE REPO (not in this monorepo):
   CrazySwami/atp-website
     └── atp-website-plugin/   ← plugin powering americatrackingpolls.com,
                                 reuses the same intake/atp-candidate-intake.php
                                 logic by syncing from this repo.
```

### Why a monorepo?

- **Single source of truth** for the plugin. A bug fix or schema bump
  lands in one place and ships to every client on the next build.
- **Per-client overrides** stay in `sites/<slug>/` — no forking, no
  divergent branches per candidate.
- **Reproducible deploys.** `build-site.sh <slug>` is deterministic:
  same inputs → same plugin zip.

---

## Diagram 2 — Intake submission lifecycle

This is what happens when a candidate (or ATP staffer on their behalf)
submits the 16-step intake form. The form lives wherever
`[atp_intake]` shortcode is placed — typically ATP's marketing WP
site, but it can also be a sandbox or Playground instance.

```
                   ┌──────────────────────────────────────────────┐
                   │  Candidate / ATP staffer fills intake form   │
                   │  (16 steps, V3 schema, on ATP's WP site)     │
                   └───────────────────────┬──────────────────────┘
                                           │ JS submits via fetch()
                                           │ POST /wp-admin/admin-ajax.php
                                           │ action=atp_save
                                           ▼
                  ┌───────────────────────────────────────────────┐
                  │  atp_ajax_save()  (intake/atp-candidate-      │
                  │                    intake.php:469)            │
                  │  • check_ajax_referer('atp_form')             │
                  │  • wp_insert_post(post_type=atp_candidate)    │
                  │  • update_post_meta(<every field>)            │
                  │  • update_post_meta('_v3_json', $v3)          │
                  │  • atp_send_notifications($data, $pid)        │
                  └─────────┬──────────────────────────────┬──────┘
                            │                              │
                  Files attached?                          │ always
                            │ yes (headshot/logo/photos)   │
                            ▼                              ▼
       ┌─────────────────────────────┐     ┌───────────────────────────────┐
       │  atp_handle_file_upload()   │     │  wp_mail() to                 │
       │  (file-upload.php)          │     │  atp_settings.notify_emails   │
       │                             │     │  • Subject:                   │
       │  Reads option               │     │    "New ATP Intake: {name}"   │
       │  atp_upload_storage:        │     │  • HTML body with summary     │
       │                             │     │    table + full V3 JSON       │
       │  ┌─ "wordpress" (default)   │     │  • Link to admin view         │
       │  │   → WP media library     │     └───────────────────────────────┘
       │  │     under /atp-intake/   │
       │  │     <candidate-slug>/    │
       │  │                          │
       │  └─ "google_drive"          │
       │      → atp_drive_upload()   │
       │        (drive-client.php)   │
       │                             │
       └────────────┬────────────────┘
                    │
            "google_drive" path:
                    ▼
   ┌────────────────────────────────────────────────┐
   │  Google Drive (atp-intake-drive project)       │
   │  Parent folder: Intake_Submissions_Live        │
   │    └─ <YYYY-MM-DD>_<Candidate-Name>_<Office>/  │
   │        ├─ headshot_<filename>                  │
   │        ├─ logo_<filename>                      │
   │        └─ additional_photos_<filename>...      │
   │                                                │
   │  Auth: service-account JWT (RS256)             │
   │  Cred: JSON key path (off web root)            │
   │  Returns: webViewLink + file ID per upload     │
   └────────────────────────────────────────────────┘

After save, two outputs exist:
  1. Post in WP admin: ATP Candidates → <Name>
       (full submission, exportable as CSV/JSON)
  2. Email + (optional) Drive folder for Mirror Factory
```

### Storage choice (WordPress vs Drive)

Configured per-host in **WP Admin → ATP → White Label Settings → File
Upload Storage**:

| Option | Where files land | When to use |
|---|---|---|
| **WordPress Media Library** (default) | `wp-content/uploads/atp-intake/<slug>/` | Local dev, single-tenant, or when Drive isn't set up yet |
| **Google Drive** | `Intake_Submissions_Live/<YYYY-MM-DD>_<Name>_<Office>/` on the configured parent folder | Production on ATP's intake host — shared visibility for the Mirror Factory team |

If Drive is selected but auth/folder/upload fails, the handler logs to
`error_log()` and **falls back to WP media** so submissions are never
lost.

### What an intake submission produces

| Artifact | Where | Used for |
|---|---|---|
| `atp_candidate` post | WP DB on the intake host | Admin view, exports, audit trail |
| Post meta `_v3_json` | Same post | Source of truth for site generation (matches `v3-schema.json`) |
| Per-field post meta | Same post | Quick column display, admin filters |
| Uploaded files | WP media or Drive subfolder | Headshot, logo, additional photos for the candidate site |
| Notification email | Mirror Factory inbox | Triggers the new-site provisioning workflow |

---

## Diagram 3 — From intake to live candidate site

This is what Mirror Factory does once the intake email arrives.

```
   Mirror Factory engineer receives intake email
              │
              │  Pulls V3 JSON from email body OR exports from
              │  ATP Candidates admin: ATP → Candidates → Export
              ▼
   ┌──────────────────────────────────────────────────────┐
   │  scripts/new-site.sh <slug> "Name" "Tagline"         │
   │  ────────────────────────────────────────────────    │
   │  Creates sites/<slug>/                               │
   │    ├── site-config.json   (defaults, edit colors,    │
   │    │                       domain, page list)        │
   │    ├── intake-v3.json     (paste exported V3 here)   │
   │    └── page-overrides/    (empty)                    │
   └──────────────────────────────┬───────────────────────┘
                                  │
                                  │  human edits site-config.json
                                  │  (domain, colors, etc.)
                                  ▼
   ┌──────────────────────────────────────────────────────┐
   │  AI generation                                       │
   │  ────────────────────────────────────────────────    │
   │  Input:   sites/<slug>/intake-v3.json                │
   │           packages/atp-plugin-core/PROMPT-TEMPLATE.md│
   │  Output:  sites/<slug>/page-json.json                │
   │           (HTML per shortcode for all 7 pages)       │
   │                                                      │
   │  Drive folder for the candidate is also pulled       │
   │  here for headshot / logo / photos.                  │
   └──────────────────────────────┬───────────────────────┘
                                  │
                                  ▼
   ┌──────────────────────────────────────────────────────┐
   │  scripts/build-site.sh <slug>                        │
   │  ────────────────────────────────────────────────    │
   │  • cp packages/atp-plugin-core/* dist/<slug>/        │
   │      atp-campaign-site/                              │
   │  • cp sites/<slug>/site-config.json into the build   │
   │  • cp sites/<slug>/intake-v3.json into the build     │
   │  • cp sites/<slug>/page-overrides/* (if any)         │
   │  • Patches Plugin Name header with client name       │
   │                                                      │
   │  Output: dist/<slug>/atp-campaign-site/              │
   │          (zip-ready, drop-in WP plugin)              │
   └──────────────────────────────┬───────────────────────┘
                                  │
                                  ▼
   ┌──────────────────────────────────────────────────────┐
   │  Deploy to client WordPress (SiteGround, etc.)       │
   │  ────────────────────────────────────────────────    │
   │  1. Spin up fresh WP install on client domain        │
   │  2. Upload + activate the built plugin               │
   │  3. On activate: site-config.php applies whitelabel  │
   │     (name, tagline, colors, login bg) automatically  │
   │  4. Run "Import Pages" (importer.php) → creates the  │
   │     7 pages with shortcodes                          │
   │  5. Run candidate-page.php import → fills shortcodes │
   │     from page-json.json                              │
   │  6. Upload media from Drive into WP media library    │
   │  7. Configure DNS, SSL, final domain                 │
   └──────────────────────────────┬───────────────────────┘
                                  │
                                  ▼
              Staging link → ATP review → candidate feedback
              → edits applied (back to AI or manual override
              in sites/<slug>/page-overrides/) → live launch
```

### Staying updated after launch

Each deployed candidate plugin includes `updater.php`. When core fixes
land in `packages/atp-plugin-core/`:

1. Re-tag the repo (or release).
2. Each client plugin's auto-updater pulls the new core code from
   GitHub and replaces its bundled core files.
3. Per-client `site-config.json`, `intake-v3.json`, `page-json.json`,
   and `page-overrides/` are preserved.

---

## Plugin runtime: two roles, one codebase

The same plugin behaves differently depending on context. Both modes
are active simultaneously — they don't conflict, they just light up
based on which shortcodes are placed on which pages.

| | **Intake host** | **Candidate site** |
|---|---|---|
| Where it runs | ATP's marketing WP site (or any sandbox) | The candidate's own WP site |
| Active shortcode(s) | `[atp_intake]` | `[atp_cand_*]` family (14 sections) |
| Active admin pages | ATP Candidates list, intake settings, white label, importer | White label, candidate page editor |
| Reads | `atp_settings`, `atp_settings.questions` | `site-config.json`, `page-json.json`, post meta |
| Writes | `atp_candidate` post type, post meta, Drive uploads | Front-end HTML to visitors |
| Uses Drive integration? | ✅ yes (file uploads) | ⚠️ optional (only if you want admin file uploads to go to Drive) |

This is why the codebase has both `intake/atp-candidate-intake.php`
and `candidate-page.php` — one feeds the pipeline, the other consumes
the pipeline's output.

---

## Where each piece of data lives

```
intake form fills      →   atp_candidate post + _v3_json meta
                              (on the intake host)
                                         │
                                         ▼
                       sites/<slug>/intake-v3.json
                              (in the monorepo, after export)
                                         │
                              AI prompt + headshot/logo
                                         │
                                         ▼
                       sites/<slug>/page-json.json
                              (in the monorepo)
                                         │
                              build-site.sh
                                         │
                                         ▼
                       dist/<slug>/atp-campaign-site/
                              (deployable plugin)
                                         │
                              activate on client WP
                                         │
                                         ▼
                       WP options + post content
                              (on the client's WP)
```

The monorepo is the **source of truth** between intake and
deploy. Once the plugin is on a client's server, the WP database
becomes the runtime source of truth for that one site — but the
monorepo still holds the recipe to rebuild it.

---

## FAQ

**Q: We're going to have many candidate sites — do they all share one
plugin install?**
No. Each candidate gets their own WordPress install + their own copy
of the built plugin. The "shared" part is the **codebase in this
repo**, not a runtime install.

**Q: When an intake form is submitted, does it automatically create a
candidate site?**
No — and that's intentional. A submission produces a notification +
exportable JSON; a Mirror Factory engineer (with help from the AI
prompt) produces the page content and runs the deploy. The hand-off
is human-mediated for now.

**Q: Where does a candidate fill out the form? On their own site or on
ATP's site?**
On ATP's site (`americatrackingpolls.com`) before they have a campaign
site. The form is the *first* step; the campaign site is the *output*.

**Q: Can the same plugin handle both intake and the campaign site
simultaneously on one WP install?**
Yes — both modes coexist. In practice ATP's marketing site only uses
the intake shortcode, and candidate sites only use the
`atp_cand_*` shortcodes. But there's no hard partition.

**Q: What's the V3 JSON for?**
It's the contract between intake and site generation. The form
produces it, the AI consumes it, and `page-json.json` is derived from
it. Schema lives at `packages/atp-plugin-core/v3-schema.json`; field
mapping at `v3-field-map.json`.

**Q: Where do file uploads go?**
Configurable per-host in **WP Admin → ATP → White Label Settings**.
Default is the WordPress media library. For the production intake
host, switch to Google Drive (see `docs/google-drive-setup.md`).

**Q: How do candidate sites get plugin updates?**
`updater.php` pulls from GitHub on a schedule. Per-client config files
are preserved across updates.

**Q: What if a client wants something the schema doesn't support?**
Drop a custom shortcode override file into
`sites/<slug>/page-overrides/`. `site-config.php` registers anything
in that folder at WP `init`. Bigger asks → schema bump in
`packages/atp-plugin-core/v3-schema.json` so every client benefits.

---

## Related docs

- `README.md` (repo root) — project overview, parties, flow
- `docs/google-drive-setup.md` — Drive credential setup
- `docs/deployment.md` — SiteGround / WP install procedure
- `docs/json-schema.md` — V3 JSON field reference
- `docs/editing.md` — how to edit a deployed site via repo + AI
- `EDIT_LOG.md` — running history of edits to this repo
