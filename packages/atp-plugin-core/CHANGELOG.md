# Changelog

All notable changes to the ATP Campaign Site plugin are documented here.

Format follows [Keep a Changelog](https://keepachangelog.com/).
Version numbering follows [Semantic Versioning](https://semver.org/).

---

## [3.1.0] - 2026-05-03

### Added — Google Drive Upload Integration

Activated the previously stubbed Google Drive storage adapter for intake-form
file uploads (headshots, logos, additional photos).

- New `includes/drive-client.php` implements service-account JWT auth (RS256),
  access-token caching via transient, folder find-or-create, and multipart
  file upload — using only `wp_remote_*` and `openssl_sign` (no Composer).
- `atp_drive_upload()` now performs real Drive uploads:
  1. Authenticate with the service account
  2. Find or create a daily submission folder named
     `YYYY-MM-DD_Candidate-Name_Office-Slug` under the configured parent
  3. Upload the file with the field name as a prefix
  4. Return the file's `webViewLink` and the submission folder URL
- On any auth/folder/upload failure the handler logs and falls back to the
  WordPress media library, so submissions are never lost.
- White Label settings page gains a **Service Account JSON Path** field
  (absolute path to the JSON key on disk; must live outside the web root) and
  a **Test Drive Connection** button that authenticates, verifies folder
  access, and round-trips a tiny test file.
- `atp_drive_is_configured()` now requires both `folder_id` and a readable
  `credentials_path`.

### Security
- `.gitignore` now blocks common service-account JSON filename patterns as a
  safety net. Credentials still must NOT be placed in the repo or web root.

---

## [3.0.0] - 2026-04-19

### Changed — Intake Form V3 (Final)

Complete rewrite of the intake form to V3 spec. 16 steps (00–15), compiled from
Alfonso's V2 build + Dan's edits. April 16, 2026.

#### V3 Step Changes
- **Step 2 (Campaign Contact):** Added treasurer_phone field (was in V1, missing from V2)
- **Step 3 (Bio & Messaging):** Added endorsements_about field — Tier 1 endorsements
  live on the About page; dedicated Endorsements page is a Tier 2 upgrade
- **Step 9 (Survey Page):** Merged V2 Steps 9+10 into one step. Replaced tier-based
  model (Benchmark/Tracking/Full Sequence) with 6-category outcome-driven primary
  survey focus dropdown. Relocated SMS/compliance fields to Step 10
- **Step 10 (Legal & Compliance):** Was Step 11. Upgraded committee_address,
  campaign_phone, privacy_contact_address to required. Added uses_cookies question.
  Consolidated all SMS categories into single unified list (added Volunteer recruitment,
  Polling, Other). Added survey_follow_up and third_party_analytics questions
- **Step 11 (Fundraising):** Was Step 12. Added fundraising_platform_status dropdown.
  Added "Haven't chosen yet" platform option. Split URL and embed code into separate
  fields. Changed button label to dropdown with Custom option. Added text-to-donate gate
- **Step 12 (Domain Setup):** Renamed from "Site Pages & Domain." Removed additional
  pages checkboxes (moved to Step 14). Expanded domain status to 5 options. Added
  hosting_provider, domain_credentials (conditional). Added campaign_email_needed
- **Step 13 (Approval & Timeline):** Added content approver Yes/No gate. Added target
  launch date picker. Changed referral source from free text to 8-option dropdown
- **Step 14 (Additional Services & Upgrades):** NEW — 11 campaign service checkboxes,
  6 Tier 2 page upgrade checkboxes, 6 additional survey focus checkboxes
- **Step 15 (Summary & Acknowledgment):** NEW — Tier 1 pages summary, key details
  review, scope and compliance acknowledgment checkboxes

#### V3 JSON Schema
- Form now outputs nested V3 JSON structure on submit (not flat key-value)
- Added `v3-schema.json` — the complete empty schema contract
- Added `v3-field-map.json` — maps all form field IDs to V3 schema paths
- Added `buildV3()` JS function for client-side schema construction
- V3 JSON stored as `_v3_json` post meta alongside flat fields (backwards compatible)
- Download, copy, and preview all output V3 nested structure

#### Standard Pages (Tier 1)
Home, About, Issues, Sign-Up, Donate, Contact, Privacy Policy,
Cookie-Tracking-SMS Compliance Policy, Survey (if selected)

### Changed — Privacy Policy Template
- Expanded from 9 to 13 sections
- Added 10DLC/TCR-aligned SMS non-sharing language
- Added state privacy rights section (CA, CO, CT, FL, OR, TX, etc.)
- Added data retention with SMS consent log retention
- All candidate-specific fields use [bracket] placeholders

### Changed — Cookie, Tracking & SMS Compliance Policy Template
- Expanded from 10 to 9 deeper sections
- Added regional consent models (EU/UK opt-in vs US opt-out)
- Added Global Privacy Control (GPC) and Do Not Track signals
- Added TCPA/10DLC consent recording via cookies
- All candidate-specific fields use [bracket] placeholders

---

## [2.1.0] - 2026-04-19

### Changed — Restructured to Client-Only Plugin

Stripped all ATP internal pages (Homepage, Brand Guide, Demo Hub) and converted
to a clean client campaign site plugin.

#### Plugin Renamed
- "ATP Demo Shortcodes" → "ATP Campaign Site"
- Removed: 28 ATP internal shortcodes (Global, Demo Hub, HP Sections ×13,
  Brand Guide Sections ×13)
- Registry reduced from 2661 to 1101 lines

#### White Label System (NEW)
- Custom login page: client logo, brand colors, background image
- Admin bar branded with client's primary color
- Admin menu active items use client's accent color
- Admin footer text customizable (replaces "Thank you for WordPress")
- Dashboard widget with campaign name, welcome message, quick links
- Settings page: ATP Shortcodes → White Label
- All settings stored in WP options, editable per client

#### Candidate Landing Page — Redesigned
- Expanded from 9 to 14 shortcode sections (+ 3 standalone pages)
- All content updated with real John Stacy data from public sources
- GSAP ScrollTrigger animations on every section
- Hero: Ken Burns background animation, 56px title, dual CTAs
- Stats: White numbers (not red), staggered fade-in on scroll
- About: Extended bio with awards (Hometown Hero 2024, Pierson Resolution 2025)
- Key Messages: Three numbered commitment cards with real platform
- Issues: Centered 3-column grid, 5 cards + "Trusted by Leaders"
- Video: Real campaign video (commissionerjohnstacy.com MP4) with play/pause
- Volunteer: Navy background with diagonal flag stripes, glassmorphic cards
- Survey: Real Typeform iframe (atp.ameritrackpolls.com)
- Social: Icon-only circles with "John Stacy" signature
- Scroll progress bar at top of page

#### New Pages
- **Issues & Answers** (`atp_cand_issues_page`): 5 detailed issue cards with
  real policy positions from public news sources
- **Donate** (`atp_cand_donate_page`): Embedded Anedot iframe + mail-in alternative
- **Contact** (`atp_cand_contact`): Phone, email, office, Calendly embed, social links
- **Privacy Policy** (`atp_cand_privacy`): 13-section comprehensive template
- **Cookie & SMS Policy** (`atp_cand_cookies`): 9-section TCPA/10DLC template

#### Page Importer Updated
- Auto-creates Canvas template when Elementor not present
- 7 client pages importable (Landing, Issues, Donate, Contact, Privacy, Cookie, Intake)
- All pages use Canvas template for full-width rendering

#### Setup Wizard
- Always visible in menu (not hidden after completion)
- "Restart Setup Wizard" button available after completion

#### Playground Blueprint
- All 7 pages auto-created with Canvas template
- Site title set to client name
- Lands on ATP Shortcodes admin page

---

## [2.0.1] - 2026-04-12

### Added
- Candidate landing page template shortcodes (`atp_cand_*`)
- Candidate page engine: token replacement from intake data
- Page JSON workflow: AI-generated custom HTML per shortcode
- Admin UI for importing Page JSON (ATP Shortcodes → Candidate Page)
- Example intake JSON (`example-intake.json`)
- Example page JSON (`example-page.json`)
- AI generation prompt template (`PROMPT-TEMPLATE.md`)
- CHANGELOG.md and admin Changelog page
- WordPress Playground blueprint

### Changed
- Intake form restructured from 18-step to 16-step Tier 1 spec (V2)
- Version bump from 2.0.0 to 2.0.1

---

## [2.0.0] - 2026-04-08

### Added
- Initial 16-step candidate intake form with full admin backend
- Custom Post Type (atp_candidate) for submission storage
- Email notification system with HTML templates
- Admin settings: question editor, branding, notifications
- JSON export (single + bulk)
- LocalStorage form persistence with auto-save
- Animated dark-theme UI with canvas background

---

## [1.2.0] - 2026-03-15

### Added
- ATP Homepage shortcode set (13 shortcodes)
- Brand Guide shortcode set (13 shortcodes)
- Demo Hub landing page
- Page Importer with one-click page creation and SEO metadata
- Setup Wizard for first-run onboarding
- GitHub auto-updater

---

## [1.0.0] - 2025-10-01

### Added
- Initial release: plugin framework
