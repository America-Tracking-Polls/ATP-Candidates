# ATP Campaign Site Platform

**Version:** 3.5.0
**By:** Mirror Factory / ROI Amplified
**For:** America Tracking Polls (ATP)
**Repo:** [America-Tracking-Polls/ATP-Candidates](https://github.com/America-Tracking-Polls/ATP-Candidates)
**Plugin dependency:** [Vibe AI](https://wordpress.org/plugins/vibe-ai/) (declared via `Requires Plugins`)

---

## Quick links to the rest of the docs

Before reading this README in full, know which doc to grab for which question:

| If you want to know… | Read this |
|---|---|
| The big picture / why two kinds of WordPress installs | This README (you're here) |
| How the system works at a system-architecture level + diagrams | `packages/atp-plugin-core/ARCHITECTURE.md` |
| The override system (template + data + toggle + preview) | `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` |
| Five ASCII diagrams covering topology, edit lifecycle, release channels, customization lanes, site lifecycle | `MASTER-PLAN.md` |
| Operating rules for any AI agent or new engineer | `AGENTS.md` |
| What we shipped, when, and what broke | `EDIT_LOG.md` |
| How to set up Drive OAuth | `docs/google-drive-setup.md` |
| Plugin changelog (per-version notes) | `packages/atp-plugin-core/CHANGELOG.md` |

---

## What This Is

A WordPress plugin that generates complete campaign websites from a
structured intake form. ATP staff (or candidates themselves) fill out
a 16-step form. That submission produces a V3 JSON file. Mirror
Factory takes the JSON + the AI prompt template and generates a
7+1-page campaign site with full white-label branding.

The system is **one plugin, one repo, one release pipeline**. Each
WP install — whether ATP's own marketing site or any candidate's
campaign site — runs the same plugin and uses only the shortcodes
relevant to it.

For a deeper architectural read with diagrams, see
[`packages/atp-plugin-core/ARCHITECTURE.md`](packages/atp-plugin-core/ARCHITECTURE.md)
and [`MASTER-PLAN.md`](MASTER-PLAN.md).

---

## The Parties

| Who | Role |
|-----|------|
| **America Tracking Polls (ATP)** | The political services company. Sells campaign websites + services to candidates. |
| **Mirror Factory** | The vendor. Builds and maintains the websites using this system. |
| **Candidate** | The end client. Gets a campaign website. Interacts with ATP, not Mirror Factory. |

---

## The Flow

```
ATP staff talks to candidate
        ↓
ATP fills out 16-step intake form (internal tool on ATP's WordPress)
        ↓
Form saves to ATP's database + uploads assets to Google Drive
        ↓
ATP clicks "Submit to Mirror Factory"
        ↓
Mirror Factory receives:
  - Email notification with candidate summary
  - V3 JSON file (all candidate data)
  - Drive folder with headshot, logo, photos
  - Invoice sent to ATP for first half payment
        ↓
Mirror Factory runs JSON through AI generation prompt
        ↓
AI generates Page JSON (HTML for each shortcode section)
        ↓
Mirror Factory deploys to SiteGround:
  - Installs WordPress
  - Installs ATP Campaign Site plugin (built from monorepo)
  - Imports Page JSON into shortcodes
  - Imports media from Drive into WP media library
  - Configures domain, SSL, white label
        ↓
ATP receives staging link
ATP reviews with candidate
Candidate sends consolidated feedback doc
        ↓
Mirror Factory applies edits
        ↓
ATP approves → site goes live
Invoice sent for second half payment
```

---

## Architecture in 60 seconds

```
                  ONE plugin: packages/atp-plugin-core/   v3.5.0
                  ───────────────────────────────────────────
                  Deps: Vibe AI (auto-prompted on plugin activation)

  ┌─────────────────────────┐    ┌─────────────────────────┐
  │ ATP intake host         │    │ Each candidate site     │
  │ americatrackingpolls.com│    │ sarahchen2026.com etc.  │
  ├─────────────────────────┤    ├─────────────────────────┤
  │ Active shortcodes:      │    │ Active shortcodes:      │
  │  [atp_intake]           │    │  [atp_cand_styles]      │
  │  [atp_mkt_*]   (13)     │    │  [atp_cand_hero]        │
  │                         │    │  [atp_cand_about]       │
  │ Drive: connected        │    │  [atp_cand_issues]      │
  │ via OAuth, picks dest   │    │  [atp_cand_signup]      │
  │ folder, uploads mirror  │    │  [atp_cand_brand_guide] │
  │                         │    │  [atp_cand_*]    (16)   │
  │ Intake submissions      │    │                         │
  │ → email + bundle (zip)  │    │ Drive: not used         │
  │ → assets in WP media +  │    │ Intake: shortcode       │
  │   Drive subfolder       │    │   exists, never placed  │
  └─────────────────────────┘    └─────────────────────────┘
                  ▲                          ▲
                  │   same plugin everywhere │
                  └──────────────────────────┘
```

Each WP install loads the whole plugin and only renders the
shortcodes its pages reference. The plugin has zero practical
overhead for unused shortcodes.

---

## The override system (data ↔ template separation)

The plugin keeps **data (JSON)** separate from **presentation
(HTML/CSS/JS)** — and lets each site override either or both per
shortcode, with a toggle to revert to core defaults at any time. Full
write-up is in
[`packages/atp-plugin-core/OVERRIDE-SYSTEM.md`](packages/atp-plugin-core/OVERRIDE-SYSTEM.md).

The mental model:

```
   DATA (V3 JSON)              TEMPLATE (HTML w/ {{tokens}})
   ─────────────────           ─────────────────────────────
   - display_name              - <section class="hero">
   - tagline                       <h1>{{display_name}}</h1>
   - color_primary             - card grid
   - issue_positions           - JS animations
   - links                     - layout
                ──── + ──── = final rendered HTML
```

For any shortcode, an admin can store:

| Storage | What it overrides |
|---|---|
| `wp_options.atp_sc_<tag>` | Template HTML |
| `wp_options.atp_sc_<tag>_data` | JSON data patch (overrides specific {{tokens}}) |
| `wp_options.atp_sc_<tag>_disabled` | Toggle: when truthy, ignore the override and render core default |

Plus two preview attributes for testing without committing:

```
[atp_cand_hero source="core"]      ← force registry default (preview the upcoming version)
[atp_cand_hero source="override"]  ← force stored override even if disabled
```

The renderer always runs `{{token}}` substitution last, so any
tokenized template stays JSON-driven regardless of where the template
came from.

The four override states (plus disabled):

| State | Template comes from | Data comes from |
|---|---|---|
| **Default** | Registry | V3 JSON |
| **Template-only override** | Per-site override | V3 JSON |
| **Data-only override** | Registry | V3 JSON ← patch |
| **Full override** | Per-site override | V3 JSON ← patch |
| **Disabled** | Registry (fall-through) | V3 JSON |

Marketing shortcodes (`atp_mkt_*`) use the same system under the
`atp_mkt_sc_*` storage prefix.

---

## Repository Structure

```
ATP-Candidates/                            ← github.com/America-Tracking-Polls/ATP-Candidates
├── packages/
│   └── atp-plugin-core/                   ← THE plugin (one codebase, v3.5.0)
│       ├── atp-demo-plugin.php            ← entry; declares Vibe AI as Requires Plugins
│       ├── ARCHITECTURE.md                ← system architecture + 3 diagrams
│       ├── OVERRIDE-SYSTEM.md             ← override system write-up
│       ├── CHANGELOG.md
│       ├── PROMPT-TEMPLATE.md             ← AI generation prompt
│       ├── v3-schema.json                 ← V3 JSON contract
│       ├── v3-field-map.json              ← form field id → JSON path mapping
│       ├── includes/
│       │   ├── registry.php               ← shortcode defaults (atp_cand_*, atp_intake)
│       │   ├── shortcodes.php             ← renderer: source attr + toggle + data patch
│       │   ├── admin.php                  ← Edit Shortcodes UI w/ override controls
│       │   ├── importer.php               ← one-click page creator (Home, Issues, Donate, Contact, About, Privacy, Cookie/TCPA, Sign Up, Brand Guide)
│       │   ├── candidate-page.php         ← Page-JSON importer + atp_cand_replace_tokens()
│       │   ├── marketing-shortcodes.php   ← atp_mkt_* registration + admin UI (templates from templates/marketing/)
│       │   ├── signup.php                 ← [atp_cand_signup] + AJAX + email + atp_subscriber CPT
│       │   ├── drive-client.php           ← Drive OAuth (drive scope, shared folders, user_meta state)
│       │   ├── file-upload.php            ← upload router: always WP media + optional Drive mirror
│       │   ├── whitelabel.php             ← brand settings + Drive admin UI
│       │   ├── site-config.php            ← reads sites/<slug>/site-config.json on activation
│       │   ├── setup-wizard.php           ← first-run onboarding
│       │   ├── changelog.php              ← in-admin version history viewer
│       │   ├── updater.php                ← GitHub auto-updater
│       │   └── intake/
│       │       └── atp-candidate-intake.php  ← 16-step intake form + bundle export
│       ├── templates/
│       │   └── marketing/                 ← 13 marketing section templates (.html / .js)
│       └── assets/
│           ├── images/                    ← 3 ATP logos
│           └── marketing/                 ← brand css + brand-*.js
│
├── sites/
│   └── john-stacy/                        ← Per-client config (one folder per candidate)
│       ├── site-config.json
│       ├── intake-v3.json
│       └── page-json.json
│
├── scripts/
│   ├── new-site.sh                        ← scaffold a new client site
│   └── build-site.sh                      ← assemble deployable plugin for a client
│
├── docs/
│   ├── google-drive-setup.md              ← Drive OAuth client setup steps
│   ├── json-schema.md                     ← V3 JSON schema reference
│   ├── pages.md, deployment.md, editing.md, launch-checklist.md
│
├── .github/workflows/
│   └── build-and-release.yml              ← auto-build client plugins on tag
│
├── AGENTS.md                              ← operating rules for AI/human contributors
├── EDIT_LOG.md                            ← running edit history
├── MASTER-PLAN.md                         ← five architecture diagrams
├── README.md                              ← this file
├── CHANGELOG.md
├── index.html                             ← ATP-branded onboarding landing for the intake host
├── ATP-Logo-Standard.png                  ← logo referenced by index.html
└── playground-blueprint.json              ← WordPress Playground recipe
```

### The `atp-website` branch (ATP marketing site)

A separate branch (`atp-website`) carries the standalone marketing
site files (HTML/CSS/JS for `americatrackingpolls.com` outside
WordPress). Intended to be exported into its own repo when ATP wants
to maintain it independently. Until then, the marketing **plugin**
(at `packages/atp-plugin-core/includes/marketing-shortcodes.php` +
`templates/marketing/`) is what runs on ATP's actual WP install.

---

## The 9 Pages

> Originally 7. Expanded to 9 in v3.5.0 — added **Sign Up** (TCPA-compliant
> email/SMS list builder) and **Brand Guide** (per-candidate visual reference,
> JSON-driven). Both are creatable from WP Admin → ATP Demo → Import Pages.

### Page 1: Home

The main campaign landing page. 14 shortcode sections.

| Section | Shortcode | JSON Source |
|---------|-----------|-------------|
| CSS + GSAP | `atp_cand_styles` | `visual_branding.primary_color`, `visual_branding.secondary_color`, `visual_branding.accent_color` |
| Navigation | `atp_cand_nav` | `identity.display_name`, `identity.office_sought` |
| Hero | `atp_cand_hero` | `identity.party`, `identity.district`, `identity.state`, `bio_messaging.tagline`, `bio_messaging.homepage_intro`, `visual_branding.headshot_link` |
| Stats Bar | `atp_cand_stats` | Derived from `background.*` and `bio_messaging.*` by AI |
| About | `atp_cand_about` | `bio_messaging.full_bio`, `bio_messaging.why_running`, `background.*` (profession, education, military), `bio_messaging.endorsements_list` |
| Key Messages | `atp_cand_messages` | `bio_messaging.key_messages` |
| Issues | `atp_cand_issues` | `platform_issues.issue_categories`, `platform_issues.positions` |
| Endorsements | `atp_cand_endorsements` | `bio_messaging.endorsements_list` |
| Video | `atp_cand_video` | `video.main_video_url` |
| Get Involved | `atp_cand_volunteer` | `identity.display_name` (standard template) |
| Survey | `atp_cand_survey` | `survey.existing_survey_link` |
| Donate CTA | `atp_cand_donate` | `fundraising.donation_page_url`, `fundraising.button_label` |
| Social | `atp_cand_social` | `social_media.*` (only platforms with URLs) |
| Footer | `atp_cand_footer` | `legal_compliance.paid_for_by`, `legal_compliance.committee_mailing_address` |

### Page 2: Issues & Answers

Detailed policy positions. One shortcode: `atp_cand_issues_page`.

| JSON Source | What It Generates |
|-------------|-------------------|
| `platform_issues.issue_categories` | One card per selected issue (up to 5) |
| `platform_issues.positions` | Full position text per issue |
| `bio_messaging.differentiator` | Intro paragraph |

### Page 3: Donate

Embedded donation form. One shortcode: `atp_cand_donate_page`.

| JSON Source | What It Generates |
|-------------|-------------------|
| `fundraising.donation_page_url` | Donate button link |
| `fundraising.embed_code` | Inline donation form iframe |
| `fundraising.button_label` | Button text |
| `identity.display_name` | Page title |
| `legal_compliance.committee_mailing_address` | Mail-in check address |

### Page 4: Contact

Contact information + scheduling. One shortcode: `atp_cand_contact`.

| JSON Source | What It Generates |
|-------------|-------------------|
| `legal_compliance.campaign_phone_legal` | Phone card |
| `legal_compliance.campaign_email_legal` | Email card |
| `legal_compliance.committee_mailing_address` | Office address card |
| `social_media.*` | Social media links |
| Calendly URL (from candidate) | Embedded scheduling |

### Page 5: About

Currently part of the homepage (`atp_cand_about` section). Can be made standalone.

| JSON Source | What It Generates |
|-------------|-------------------|
| `bio_messaging.full_bio` | Multi-paragraph biography |
| `bio_messaging.why_running` | Why running section |
| `background.*` | Credentials sidebar |
| `bio_messaging.endorsements_list` | Endorsements on about page |

### Page 6: Privacy Policy

13-section legal page. One shortcode: `atp_cand_privacy`.

| JSON Source | What It Generates |
|-------------|-------------------|
| `legal_compliance.committee_name` | [Candidate Committee Name] |
| `legal_compliance.committee_mailing_address` | [Mailing Address] |
| `legal_compliance.campaign_email_legal` | [Campaign Email Address] |
| `legal_compliance.campaign_phone_legal` | [Campaign Phone Number] |
| `domain_setup.preferred_domain` | [Website URL] |

### Page 7: Cookie, Tracking & SMS Compliance Policy

9-section legal page. One shortcode: `atp_cand_cookies`.

| JSON Source | What It Generates |
|-------------|-------------------|
| `identity.display_name` | [Candidate Name] |
| `identity.office_sought` | [Office] |
| `legal_compliance.committee_name` | [Candidate Committee Name] |
| `legal_compliance.committee_mailing_address` | [Mailing Address] |
| `legal_compliance.campaign_email_legal` | [Campaign Email Address] |
| `legal_compliance.campaign_phone_legal` | [Campaign Phone Number] |
| `domain_setup.preferred_domain` | [Website URL] |

### Page 8: Sign Up

TCPA-compliant email/SMS signup form. One shortcode: `atp_cand_signup`.
Captures Name (first/last), Email, Phone, SMS opt-in. Submissions land
as `atp_subscriber` posts; campaign contact gets an email per submit.

| JSON Source | What It Generates |
|-------------|-------------------|
| `identity.display_name` | Form heading + intro copy |
| `legal_compliance.committee_name` | TCPA disclosure + paid-for-by |
| `legal_compliance.paid_for_by` | Footer disclaimer |
| `legal_compliance.campaign_email_legal` | Notification recipient (fallback to admin_email) |
| `social_media.*` | Social icons row above the form (only platforms with URLs) |
| `home_url('/privacy-policy/')` | Privacy link in TCPA opt-in copy |

### Page 9: Brand Guide

Per-candidate visual identity reference. One shortcode: `atp_cand_brand_guide`.
Tokenized template — pulls everything from V3 JSON.

| JSON Source | What It Generates |
|-------------|-------------------|
| `identity.display_name` | Page heading |
| `bio_messaging.tagline` | Voice/tone reference paragraph |
| `visual_branding.primary_color` / `secondary_color` / `accent_color` | Color swatches |
| `visual_branding.headshot_link` | Headshot panel |
| `visual_branding.logo_link` | Logo panel |

---

## The Intake Form (V3)

### Overview

16-step guided form. ATP staff fills it out after talking to the candidate. Produces a V3 JSON file that contains everything needed to generate the website.

### Steps

| Step | Section | Key Fields |
|------|---------|-----------|
| 1/16 | Source Check | Who's filling this out, filing URL, Ballotpedia URL |
| 2/16 | Identity & Race | Legal name, display name, office, district, state, party, election date |
| 3/16 | Campaign Contact | Primary contact, campaign manager, treasurer (name/email/phone) |
| 4/16 | Bio & Messaging | Homepage intro, full bio, why running, tagline, key messages, endorsements |
| 5/16 | Platform & Issues | Up to 5 issue categories with positions |
| 6/16 | Background | Profession, education (3 slots), military service |
| 7/16 | Visual Branding | Headshot upload, logo upload, photos, brand colors, visual style |
| 8/16 | Social Media | Facebook, X, Instagram, YouTube, TikTok, LinkedIn |
| 9/16 | Video | Campaign video URL |
| 10/16 | Survey Page | Include survey? Focus category, page name, placement |
| 11/16 | Legal & Compliance | Committee name, paid-for-by, jurisdiction, privacy contacts, SMS categories |
| 12/16 | Fundraising | Platform, status, donation URL, embed code, button label |
| 13/16 | Domain Setup | Domain status, preferred domain, hosting, campaign email |
| 14/16 | Approval & Timeline | Content approver, launch timeline, communication preference |
| 15/16 | Grow Beyond Your Website | Additional services interest, Tier 2 pages, survey upsells |
| 16/16 | Summary & Acknowledgment | Review all data, confirm scope, generate profile |

### Output

The form outputs a nested V3 JSON with 17 sections. See `v3-schema.json` for the complete empty schema and `v3-field-map.json` for the mapping between form field IDs and JSON paths.

---

## The JSON-to-Site Pipeline

### How the JSON becomes a website

1. **Intake form produces V3 JSON** — all candidate data in one structured file
2. **AI reads the JSON + prompt template** — `PROMPT-TEMPLATE.md` tells the AI how to generate HTML for each shortcode section
3. **AI outputs Page JSON** — each key is a shortcode tag, each value is production HTML
4. **Page JSON imported into WordPress** — ATP Shortcodes → Candidate Page → paste → import
5. **Legal pages auto-populated** — privacy and cookie policy templates have `[bracket]` variables replaced from `legal_compliance.*`
6. **Media imported from Drive** — headshot, logo, photos downloaded into WP media library
7. **Site renders** — each page is a list of shortcode tags, each shortcode renders its HTML from the database

### What's automatic vs manual

| Step | Automatic | Manual |
|------|-----------|--------|
| JSON generation from form | Yes | — |
| File upload to Drive/WP | Yes | — |
| AI page generation | Could be automated | Currently: paste JSON into prompt |
| Page JSON import | One-click in admin | — |
| Legal page variable replacement | In the prompt | — |
| Domain + SSL setup | — | SiteGround admin |
| White label branding | Auto from site-config.json | — |

---

## Content Architecture — How JSON Drives Everything

### The Three JSON Files Per Client

Every client site is defined by three JSON files in their `sites/{slug}/` folder:

| File | Purpose | Who Creates It |
|------|---------|---------------|
| `site-config.json` | Branding, domain, page list, shortcode order | Mirror Factory (on new client setup) |
| `intake-v3.json` | Raw candidate data from the intake form | ATP staff (via the intake form) |
| `page-json.json` | Generated HTML content for every shortcode | AI (from intake JSON + prompt template) |

### How They Connect

```
intake-v3.json          ← "The candidate's name is John Stacy, he's running for..."
        ↓
AI + PROMPT-TEMPLATE.md
        ↓
page-json.json          ← "Here's the hero HTML, here's the about HTML, here's the issues HTML..."
        ↓
site-config.json        ← "Put these shortcodes on these pages in this order"
        ↓
WordPress renders the site
```

### What page-json.json Looks Like

Each key is a shortcode tag. Each value is the complete HTML for that section.

```json
{
  "atp_cand_nav": "<nav class='cand-nav'>...<span class='cand-nav-name'>John Stacy</span>...</nav>",
  "atp_cand_hero": "<section class='cand-hero'>...<h1>The Choice for the People.</h1>...</section>",
  "atp_cand_about": "<section class='cand-section'>...<p>John Stacy is a 6th generation Texan...</p>...</section>",
  "atp_cand_issues": "<section class='cand-section'>...5 issue cards...</section>",
  "atp_cand_privacy": "<section class='cand-legal'>...13-section privacy policy...</section>",
  "atp_cand_cookies": "<section class='cand-legal'>...9-section cookie policy...</section>"
}
```

### How Pages Are Assembled

`site-config.json` defines which shortcodes go on which page and in what order:

```json
{
  "pages": [
    {
      "title": "Home",
      "slug": "home",
      "shortcodes": [
        "atp_cand_styles",
        "atp_cand_nav",
        "atp_cand_hero",
        "atp_cand_stats",
        "atp_cand_about",
        "atp_cand_issues",
        "atp_cand_footer"
      ],
      "is_front_page": true
    }
  ]
}
```

WordPress renders the page by looking up each shortcode tag in `page-json.json` and outputting the HTML in order.

### Adding a New Section to One Client

To add a section that only exists on one client's site:

1. Add the HTML to their `page-json.json`:
```json
{
  "atp_cand_community": "<section class='cand-section'>...custom community section...</section>"
}
```

2. Add the shortcode tag to the page in their `site-config.json`:
```json
{
  "shortcodes": [
    "atp_cand_hero",
    "atp_cand_community",
    "atp_cand_about"
  ]
}
```

3. Build and deploy. The section appears between the hero and about sections on that client's site only.

### Editing Content

To edit any section, change the HTML value for that shortcode key in `page-json.json`. The AI can do this directly:

```
"Edit sites/john-stacy/page-json.json — change the hero tagline to 
'Four More Years of Results'"
```

The AI finds the `atp_cand_hero` key, edits the HTML, commits, pushes. The plugin picks it up on the next build/update.

### Removing a Section

Remove the shortcode tag from the page's shortcode list in `site-config.json`. The HTML can stay in `page-json.json` (it just won't render) or be removed.

### The Priority Chain

When the plugin renders a shortcode, it checks in this order:

```
1. Does page-json.json have content for this tag?    → use it
2. Does the WP database have a saved admin edit?     → use it
3. Use the core default from registry.php            → fallback
```

This means:
- `page-json.json` is the primary content source (version-controlled in the repo)
- WP admin edits work as quick fixes but `page-json.json` takes priority on rebuild
- Core defaults in `registry.php` are the safety net if nothing else exists

### At 30 Sites

```
sites/
├── client-01/page-json.json    ← all content for client 1
├── client-02/page-json.json    ← all content for client 2
├── client-03/page-json.json    ← all content for client 3
│   ...
└── client-30/page-json.json    ← all content for client 30
```

Each client's content lives in one JSON file. The AI can read any of them, edit any of them, or generate new ones from intake data. Core plugin updates propagate to all 30 sites without touching any client's content.

---

## Plugin Features

| Feature | What It Does |
|---------|-------------|
| **Shortcode Editor** | Edit any section's HTML in the admin. Database edits override plugin defaults. |
| **Page Importer** | One-click creation of all 7 pages with Canvas template and SEO metadata. |
| **Candidate Page Engine** | Import Page JSON from AI. Each key populates a shortcode. |
| **White Label** | Custom login page, admin bar, dashboard, footer — all from settings. |
| **File Upload** | Native drag-and-drop upload. WordPress media (default) or Google Drive. |
| **Setup Wizard** | First-run onboarding. Restartable from admin menu. |
| **Auto-Updater** | Checks GitHub for new releases, updates plugin automatically. |
| **Site Config** | `site-config.json` auto-applies client branding on activation. |

---

## Monorepo Operations

### Add a new client

```bash
./scripts/new-site.sh client-slug "Client Name" "Office Tagline"
# Edit sites/client-slug/site-config.json
# Save intake JSON to sites/client-slug/intake-v3.json
```

### Build a client's plugin

```bash
./scripts/build-site.sh client-slug
# Output: dist/client-slug/atp-campaign-site/
# Zip and deploy to their WordPress
```

### Update all clients

Edit `packages/atp-plugin-core/` → commit → tag → GitHub Action builds all client plugins.

### Give a client their site

Zip `dist/client-slug/atp-campaign-site/` — it's a self-contained WordPress plugin. No dependency on the monorepo, Mirror Factory, or ATP.

---

## Post-Launch Editing

### How edits work

The shortcode system has two layers:

1. **Registry defaults** — template HTML in `registry.php`
2. **Database edits** — changes made in the shortcode editor

Database always wins. Plugin updates change defaults, never database content. "Reset" button reverts to the latest default.

### Edit workflow

1. ATP meets with candidate, collects feedback
2. ATP sends consolidated document to Mirror Factory (screenshots + edit descriptions)
3. Mirror Factory edits shortcodes in admin or re-generates via AI
4. Changes go live immediately

### Scope tracking

Edits within the 7-page Standard package are included. Additional pages, features, or services are quoted separately. The intake form's "Grow Beyond Your Website" section captures interest signals for upsells.

---

## Key Files Reference

| File | Location | Purpose |
|------|----------|---------|
| `v3-schema.json` | `packages/atp-plugin-core/` | The JSON contract — every field the form outputs |
| `v3-field-map.json` | `packages/atp-plugin-core/` | Maps form field IDs to JSON paths |
| `PROMPT-TEMPLATE.md` | `packages/atp-plugin-core/` | AI prompt for generating Page JSON from intake data |
| `site-config.json` | `sites/{client}/` | Client branding, domain, page list with shortcode order |
| `intake-v3.json` | `sites/{client}/` | Completed intake data — raw candidate info |
| `page-json.json` | `sites/{client}/` | Generated HTML per shortcode — the site's content |
| `example-intake.json` | `packages/atp-plugin-core/` | Example completed intake (John Stacy) |
| `example-page.json` | `packages/atp-plugin-core/` | Example Page JSON output |
| `playground-blueprint.json` | repo root | WordPress Playground instant demo |

---

## Pricing Model

| Milestone | Payment |
|-----------|---------|
| Intake form completed + submitted to Mirror Factory | First half invoice sent |
| Site approved + launched | Second half invoice sent |

Additional services (text messaging, polling, ads, Tier 2 pages) quoted separately per ATP's service catalog.

---

See `/docs/` for detailed documentation:
- `docs/json-schema.md` — V3 JSON schema reference (every field, which pages use it)
- `docs/pages.md` — page-by-page breakdown with all shortcodes
- `docs/deployment.md` — step-by-step deployment guide
- `docs/editing.md` — how to make changes post-launch via repo + AI
- `docs/launch-checklist.md` — SOW Exhibit B acceptance checklist
