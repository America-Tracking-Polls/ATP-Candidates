# ATP Campaign Site Plugin

**Version:** 3.0.0
**Author:** Mirror Factory / ROI Amplified
**For:** America Tracking Polls

A WordPress plugin that powers Tier 1 campaign websites. One intake form collects everything about a candidate, outputs structured JSON, and drives the generation of a complete campaign site — homepage, issues, donate, contact, legal compliance pages, and more.

---

## How It Works

```
Candidate fills 16-step intake form
        ↓
Form outputs V3 JSON (nested, structured)
        ↓
AI takes the JSON + prompt template → generates Page JSON
        ↓
Page JSON imported into WordPress → each shortcode gets custom HTML
        ↓
Campaign website is live (7+ pages)
```

---

## The Intake Form

**16 steps. ~100 fields. Three-condition branching (A/B/C).**

The intake form (`[atp_intake]`) is the foundation of everything. It collects candidate identity, bio, issues, branding, legal compliance, fundraising, domain, and timeline — then outputs a V3 JSON file that drives every downstream system.

### V3 Form Steps

| Step | Section | What It Collects |
|------|---------|-----------------|
| 1/16 | 00 — Source Check | Submitter info, filing URL, Ballotpedia URL, existing website |
| 2/16 | 01 — Identity & Race | Legal name, display name, ballot name, office, district, state, party, election date/type, incumbent status |
| 3/16 | 02 — Campaign Contact | Primary contact, campaign manager, treasurer (name, email, phone, address) |
| 4/16 | 03 — Bio & Messaging | Ballotpedia status, homepage intro, full bio, why running, tagline, key messages, endorsements for about page |
| 5/16 | 04 — Platform & Issues | 15 issue category checkboxes + positions per issue, opponent gaps, evolved positions |
| 6/16 | 05 — Background | Profession, role, experience, education (3 slots), military service |
| 7/16 | 06 — Visual Branding | Headshot (required), logo, photos, 3 brand colors, visual style, design notes |
| 8/16 | 07 — Social Media | Facebook, X/Twitter, Instagram, YouTube, TikTok, LinkedIn, Other |
| 9/16 | 08 — Video | Main campaign video URL, other video assets |
| 10/16 | 09 — Survey Page | Include survey? Primary focus (6 categories), page name, placement, intro text |
| 11/16 | 10 — Legal & Compliance | Committee name, paid-for-by, filing level, privacy contacts, cookies, SMS categories, analytics, data sharing |
| 12/16 | 11 — Fundraising | Donation page needed? Platform, platform status, URL, embed code, button label, text-to-donate |
| 13/16 | 12 — Domain Setup | Domain status (5 options), preferred/primary domain, registrar, hosting, campaign email |
| 14/16 | 13 — Approval & Timeline | Content approver, copy help, launch timeline/date, communication pref, referral source |
| 15/16 | 14 — Additional Services | 11 campaign services, 6 Tier 2 page upgrades, additional survey focuses |
| 16/16 | 15 — Summary | Tier 1 pages list, key details review, scope + compliance acknowledgment |

### V3 JSON Output

On submit, the form produces a nested JSON file following `v3-schema.json`. Key sections:

- `meta` — form version, candidate ID, timestamp, status
- `source_check` — submitter info and data source URLs
- `identity` — candidate identity and race details
- `contacts` — primary contact, campaign manager, treasurer
- `bio_messaging` — bio, tagline, key messages, endorsements
- `platform_issues` — issue categories array + position text
- `background` — profession, education, military
- `visual_branding` — headshot, logo, colors, style
- `social_media` — all platform URLs
- `video` — campaign video URLs
- `survey` — survey page config and focus
- `legal_compliance` — committee, disclaimers, SMS, cookies, analytics
- `fundraising` — platform, status, URLs, embed code
- `domain_setup` — domain, registrar, hosting, email
- `approval_timeline` — approver, timeline, referral
- `additional_services` — upsell interest signals
- `acknowledgment` — scope and compliance checkboxes
- `pages_standard` — Tier 1 page list

### Field Mapping

`v3-field-map.json` maps every form field ID to its V3 schema path. Example:
- `filler_name` → `source_check.submitter_name`
- `committee_name` → `legal_compliance.committee_name`
- `issue_categories` → `platform_issues.issue_categories`

---

## Campaign Website Pages

### Tier 1 Standard Pages (built automatically)

| Page | Shortcodes Used | Purpose |
|------|----------------|---------|
| **Home** | `atp_cand_styles` + `nav` + `hero` + `stats` + `about` + `messages` + `issues` + `endorsements` + `video` + `volunteer` + `survey` + `donate` + `social` + `footer` | Full campaign landing page with 14 sections |
| **Issues & Answers** | `atp_cand_styles` + `nav` + `issues_page` + `footer` | Detailed policy positions — 5 issue cards with navy headers |
| **Donate** | `atp_cand_styles` + `nav` + `donate_page` + `footer` | Embedded donation form (Anedot/ActBlue/WinRed iframe) + mail-in info |
| **Contact** | `atp_cand_styles` + `nav` + `contact` + `footer` | Phone, email, office address, Calendly embed, social links |
| **Privacy Policy** | `atp_cand_styles` + `nav` + `privacy` + `footer` | 13-section policy with SMS/10DLC/TCPA disclosures |
| **Cookie & SMS Policy** | `atp_cand_styles` + `nav` + `cookies` + `footer` | 9-section cookie/tracking/TCPA/10DLC compliance policy |
| **Sign-Up** | *(built from survey/Typeform embed)* | Voter engagement survey — configured in intake Step 9 |

### Homepage Sections (14 shortcodes)

| Shortcode | Section | Key Features |
|-----------|---------|-------------|
| `atp_cand_styles` | CSS + GSAP | Full design system, CSS variables, scroll animations |
| `atp_cand_nav` | Navigation | Sticky nav, scroll progress bar, mobile menu, donate CTA |
| `atp_cand_hero` | Hero | Ken Burns background, 56px title, dual CTAs, headshot photo |
| `atp_cand_stats` | Metrics Bar | 4 key stats with staggered fade-in animation on scroll |
| `atp_cand_about` | About | Multi-paragraph bio + credentials sidebar (6 cards) |
| `atp_cand_messages` | Key Messages | 3 numbered commitment cards |
| `atp_cand_issues` | Issues Grid | Centered 3-column grid, 5 issue cards + "Trusted by Leaders" |
| `atp_cand_endorsements` | Endorsements | Quote cards with names, roles, organizations |
| `atp_cand_video` | Video | HTML5 video player with play/pause overlay |
| `atp_cand_volunteer` | Get Involved | Navy background with flag stripes, 3 glassmorphic action cards |
| `atp_cand_survey` | Voter Survey | Typeform iframe embed |
| `atp_cand_donate` | Donate CTA | Full-width dark section with white donate button |
| `atp_cand_social` | Social | Icon-only circles (FB, X, IG, LI) + signature |
| `atp_cand_footer` | Footer | Paid-for-by disclaimer, committee info, compliance links, scroll progress JS |

---

## Plugin Features

### Shortcode Editor
Every section of every page is an independently editable shortcode. Go to **ATP Shortcodes** in the admin sidebar to see all shortcodes, copy their HTML, edit it, paste it back. The registry provides defaults; any edits are stored in the WP database and take priority. Plugin updates never overwrite your edits.

### Page Importer
**ATP Shortcodes → Import Pages** — One-click page creation for all 7 client pages. Each page gets:
- Canvas template (works with or without Elementor)
- Yoast SEO focus keyword and meta description
- Title hidden via post meta

### Candidate Page Engine
**ATP Shortcodes → Candidate Page** — Two modes:
1. **Page JSON Import** (primary): Paste AI-generated Page JSON where each key is a shortcode tag and each value is final HTML. Full creative control.
2. **Intake Data Fallback**: Link to an intake submission or paste raw JSON for automatic token replacement.

### White Label
**ATP Shortcodes → White Label** — Customize the admin for each client:
- Client name and logo on login page
- Brand colors on admin bar and menus
- Custom login background image
- Dashboard welcome widget with quick links
- Custom admin footer text

### Setup Wizard
**ATP Shortcodes → Setup Wizard** — First-run onboarding with plugin installation and page import. Always accessible in the menu with a "Restart" button.

### Changelog
**ATP Shortcodes → Changelog** — Renders CHANGELOG.md with styled formatting.

### Auto-Updater
GitHub-based plugin updates. Checks for new releases automatically.

### Intake Form Admin
**ATP Candidates** — View all submissions, export JSON, send email notifications.
**ATP Candidates → Settings** — Edit questions, customize branding, configure notification recipients.

---

## Key Files

| File | Purpose |
|------|---------|
| `v3-schema.json` | The V3 JSON schema — the contract between the form and all downstream systems |
| `v3-field-map.json` | Maps form field IDs to V3 schema paths |
| `example-intake.json` | Complete example intake output (John Stacy) |
| `example-page.json` | Complete example Page JSON (generated HTML per shortcode) |
| `PROMPT-TEMPLATE.md` | AI prompt template for generating Page JSON from intake JSON |
| `CHANGELOG.md` | Version history |
| `playground-blueprint.json` | WordPress Playground blueprint — instant demo environment |

---

## Downstream System Mapping

The V3 JSON feeds multiple systems beyond the website:

| System | JSON Sections Used |
|--------|-------------------|
| **Website Build** | All sections (primary consumer) |
| **HubSpot CRM** | source_check, identity, contacts, approval_timeline, additional_services |
| **Campaign Verify** | identity, contacts, legal_compliance |
| **10DLC Registration** | identity, legal_compliance, fundraising, contacts |
| **Wikidata** | identity, source_check, social_media, domain_setup |
| **Schema Markup / JSON-LD** | identity, social_media, source_check, domain_setup |
| **Privacy Policy (auto-gen)** | legal_compliance (committee, privacy contacts, vendors) |
| **Cookie/SMS Policy (auto-gen)** | legal_compliance (cookies, SMS, analytics) |
| **Legal Footer** | legal_compliance.paid_for_by |
| **Billing Form** | contacts (treasurer), legal_compliance, fundraising |

---

## WordPress Playground

Instant demo with everything pre-installed:

```
https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/mirror-factory/ATP-Demo/claude/tier1-intake-form-uq7Mc/playground-blueprint.json
```

Creates 7 pages with Canvas template, sets homepage, logs in as admin.

---

## Development Workflow

1. **New client**: Fork the repo → update shortcode defaults with client content → deploy
2. **Content updates**: Edit shortcodes in the admin UI or push registry changes via GitHub
3. **Feature additions**: Add to the master repo, cherry-pick into client forks
4. **Safe updates**: Registry defaults are fallbacks; database edits always take priority

---

## Quick Start — New Client Site

### Step 1: Get the intake JSON

The candidate (or their campaign manager) fills out the 16-step intake form. When they click "Generate Profile," the form outputs a V3 JSON file. Download it or copy it from the admin.

### Step 2: Generate the Page JSON

Take the intake JSON and run it through this prompt in Claude, ChatGPT, or any AI:

```
You are a political campaign web designer. I'm giving you a candidate's
intake form data as JSON. Generate a Page JSON that contains the final
rendered HTML for each shortcode section of their campaign website.

Rules:
1. Output valid JSON with these keys: atp_cand_nav, atp_cand_hero,
   atp_cand_stats, atp_cand_about, atp_cand_messages, atp_cand_issues,
   atp_cand_endorsements, atp_cand_video, atp_cand_volunteer,
   atp_cand_survey, atp_cand_donate, atp_cand_social, atp_cand_footer
2. You MAY omit keys for sections that don't apply
3. Each value is a string of HTML using the CSS classes from atp_cand_styles
4. Include _candidate and _generated metadata fields
5. HTML should be production-ready with real content, no placeholders
6. Generate the right number of issue cards from platform_issues
7. Generate endorsement cards from bio_messaging.endorsements_list
8. Only include social links that have URLs
9. Footer MUST include the exact legal_compliance.paid_for_by text
10. Use identity fields for the nav bar (display_name, office_sought)
11. Use bio_messaging.homepage_intro for the hero intro paragraph
12. Use bio_messaging.tagline for the hero H1

Candidate Intake JSON:

{PASTE THE V3 JSON HERE}
```

The AI reads the structured data and writes production HTML for each section.

### Step 3: Generate the legal pages

Run a second prompt for the privacy and cookie policies:

```
I need you to populate two legal page templates with this candidate's data.
Use the legal_compliance section of the JSON to fill in all [bracketed]
variables. Output two separate HTML blocks.

Template 1: Privacy Policy — use the atp_cand_privacy shortcode template
Template 2: Cookie, Tracking & SMS Compliance Policy — use the atp_cand_cookies template

Variables to replace:
- [Candidate Committee Name] = legal_compliance.committee_name
- [Website URL] = domain_setup.preferred_domain
- [Mailing Address] = legal_compliance.committee_mailing_address
- [Campaign Email Address] = legal_compliance.campaign_email_legal
- [Campaign Phone Number] = legal_compliance.campaign_phone_legal
- [Month Day, Year] = today's date
- [Candidate Name] = identity.display_name
- [Office] = identity.office_sought

Candidate JSON:

{PASTE THE V3 JSON HERE}
```

### Step 4: Import into WordPress

1. Go to **WP Admin → ATP Shortcodes → Candidate Page**
2. Paste the Page JSON from Step 2
3. Click **Import Page JSON**
4. Go to **ATP Shortcodes** → find `atp_cand_privacy` and `atp_cand_cookies`
5. Paste the legal page HTML from Step 3 into each shortcode editor
6. Click Save on each

### Step 5: Create the pages

Go to **ATP Shortcodes → Import Pages** and import all 7 pages. Or run the Setup Wizard.

### Step 6: Review and launch

- Preview each page on the front end
- Edit any shortcode in the admin if something needs tweaking
- Set up the domain (DNS, SSL)
- Go live

### How edits work after launch

The shortcode system has two layers:

1. **Registry defaults** — the template HTML hardcoded in the plugin
2. **Database edits** — any changes made in the shortcode editor

Database edits always win. So when you import Page JSON or edit a shortcode manually, that content is stored in the WordPress database and renders on the front end. Plugin updates only change the registry defaults (the fallback), never the database content.

To update content:
- **Quick edit**: Go to ATP Shortcodes, find the section, edit the HTML directly
- **AI-assisted edit**: Copy the HTML, paste into AI with instructions ("make the hero title bigger", "add a new issue card"), paste back, save
- **Full regeneration**: Run the Page JSON prompt again with updated intake data, re-import

To add a new section:
- Add a new shortcode entry in the registry (or just edit the WordPress page content to include a custom HTML block)

To remove a section:
- Edit the WordPress page and remove that shortcode tag

---

*ATP Campaign Site Plugin v3.0.0*
*Built by Mirror Factory / ROI Amplified for America Tracking Polls*
