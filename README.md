# ATP Campaign Site Platform

**Version:** 3.1.0
**By:** Mirror Factory / ROI Amplified
**For:** America Tracking Polls (ATP)

---

## What This Is

A WordPress plugin system that generates complete campaign websites from a structured intake form. ATP staff fill out a 16-step form about a political candidate, the form produces a V3 JSON file, and that JSON drives the generation of a 7-page campaign website — homepage, issues, donate, contact, about, privacy policy, and cookie/SMS compliance policy.

The system is built as a monorepo that manages multiple client sites from one codebase. Each client gets a self-contained WordPress plugin that can run independently.

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

## Repository Structure

```
ATP-Demo/                           ← this repo (mirror-factory/ATP-Demo)
├── packages/
│   └── atp-plugin-core/            ← shared plugin code (ONE codebase)
│       ├── atp-demo-plugin.php     ← main plugin file
│       ├── includes/
│       │   ├── registry.php        ← page templates (shortcode defaults)
│       │   ├── shortcodes.php      ← shortcode rendering engine
│       │   ├── admin.php           ← shortcode editor admin page
│       │   ├── importer.php        ← one-click page creator
│       │   ├── candidate-page.php  ← Page JSON import engine
│       │   ├── whitelabel.php      ← custom login, admin bar, dashboard
│       │   ├── site-config.php     ← reads client config on activation
│       │   ├── file-upload.php     ← file upload (WP media / Google Drive)
│       │   ├── setup-wizard.php    ← first-run onboarding
│       │   ├── changelog.php       ← version history viewer
│       │   ├── updater.php         ← GitHub auto-updater
│       │   └── intake/
│       │       └── atp-candidate-intake.php  ← the 16-step intake form
│       ├── v3-schema.json          ← the JSON contract
│       ├── v3-field-map.json       ← form field ID → JSON path mapping
│       ├── PROMPT-TEMPLATE.md      ← AI generation prompt
│       └── assets/                 ← logos, admin CSS
│
├── sites/
│   └── john-stacy/                 ← CLIENT: John Stacy
│       ├── site-config.json        ← client name, colors, domain, pages
│       ├── intake-v3.json          ← completed intake data
│       ├── page-json.json          ← generated site content (HTML per shortcode)
│       └── overrides/              ← manual shortcode overrides (optional)
│
├── scripts/
│   ├── new-site.sh                 ← scaffold a new client site
│   └── build-site.sh              ← assemble deployable plugin for a client
│
├── docs/                           ← detailed documentation
│   ├── json-schema.md              ← V3 JSON schema reference
│   ├── pages.md                    ← page-by-page breakdown
│   ├── deployment.md               ← deployment guide
│   ├── editing.md                  ← how to edit via repo + AI
│   └── launch-checklist.md         ← SOW acceptance checklist
│
├── .github/workflows/
│   └── build-and-release.yml       ← auto-build client plugins on tag
│
└── playground-blueprint.json       ← WordPress Playground demo
```

### Separate Repo: ATP Website
```
CrazySwami/atp-website              ← ATP's own marketing site
├── atp-website-plugin/             ← plugin for americatrackingpolls.com
│   ├── includes/
│   │   └── intake/                 ← intake form (same form, different context)
│   └── ...
└── ...
```

---

## The 7 Pages

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
