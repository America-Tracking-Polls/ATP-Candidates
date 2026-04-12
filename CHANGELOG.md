# Changelog

All notable changes to the ATP Demo plugin and Candidate Intake Form are documented here.

Format follows [Keep a Changelog](https://keepachangelog.com/).
Version numbering follows [Semantic Versioning](https://semver.org/).

---

## [2.0.1] - 2026-04-12

### Changed — Intake Form: 18-Step to 16-Step Tier 1 Restructure

Restructured the entire candidate intake form from Alfonso's original 18-step layout to the
definitive 16-step (0-15) Tier 1 Website Intake Spec. This implements the three-condition
architecture (A/B/C) field structure where all of Alfonso's original fields are preserved
but conditionally displayed based on data source availability.

#### New Steps
- **Step 0 — Source Check (Gateway):** Added filler contact info, official filing URL,
  Ballotpedia URL, and existing website. This step determines which condition path (A/B/C)
  the candidate follows.
- **Step 5 — Background & Credentials:** Added profession, current role, previous experience,
  education (3 university slots matching Ballotpedia structure), military service.

#### Merged Steps
- Old Steps 01 (Candidate Identity) + 02 (The Race) + 03 (Race Position) → **Step 1 (Identity & Race)**
- Old Steps 14 (Site Pages) + 16 (Domain & Technical) → **Step 13 (Site Pages & Domain)**
- Old Steps 17 (Custom Requests) + 18 (Timeline) → **Step 15 (Approval & Timeline)**

#### Modified Steps
- **Step 1 — Identity & Race:** Added org_type, ballot_name, seat_number, election_year.
  Removed opponents (deferred to LPI layer).
- **Step 2 — Campaign Contact:** Added campaign manager fields (name/email/phone), moved
  treasurer from old Step 12 with email and address. Moved existing_website to Step 0.
- **Step 3 — Bio & Messaging:** Added Ballotpedia status toggle, homepage_intro (renamed
  from bio_short), why_running, key_messages, policy_passions.
- **Step 4 — Platform & Issues:** Replaced free-text issues with multi-select issue_categories
  (15 policy areas) + structured issue_positions textarea. Added opponents_missing_issue and
  changed_position. Removed target_voter (deferred to ads/survey module).
- **Step 6 — Visual Branding:** Split headshot (now required) from additional_photos.
  Structured colors into primary/secondary/accent. Removed fonts and other_assets.
  Added design_notes.
- **Step 9 — Survey Page:** Reframed from service-tier commitment to website page decision.
  Added survey_page_wanted gate, page label selection (custom names), survey_intro_text,
  and "Recommend for me" tier option.
- **Step 10 — Survey Embed Setup:** Replaced old delivery channels with website-specific
  embed config: survey_display placement, survey_embed_code, phone collection,
  SMS opt-in language, message types, survey_goal. Removed channels, voter_file,
  matrix_figures (deferred to Survey Design Brief).
- **Step 11 — Legal & Compliance:** Added committee_name, paidfor_text (for auto-generation),
  filing_level, committee_address, campaign_phone/email, expanded privacy contact to include
  phone and address. Added text messaging questions (will_send_texts, text_types,
  donations_by_text, data_sharing, service_providers). Removed tcpa status and legal_notes
  (deferred to future SMS and compliance modules).
- **Step 12 — Fundraising:** Expanded platform options (added Revv, NGP VAN). Added
  donation_button_label, donation_sms_optin, donation_sms_description,
  text_donation_processor/accreditation. Removed fundraising_message.
- **Step 13 — Site Pages & Domain:** Standard pages now auto-include Home, About, Issues,
  Privacy Policy, Cookie Policy, SMS Terms. Added domain_primary, domain_redirects,
  domain_registrar, web_optin_url/placement. Removed tracking_ids, email_platform,
  other_integrations (deferred to digital ads and HubSpot modules).
- **Step 14 — Endorsements:** Added endorsement_links field.
- **Step 15 — Approval & Timeline:** Replaced date picker with timeline dropdown (ASAP /
  2-4 weeks / 1-3 months / planning ahead). Added approver_email and referral_source.
  Removed custom_requests open text.

#### Updated Supporting Code
- Admin single-view groups reorganized for 16 new sections.
- Email notification summary updated with new field references.
- JavaScript summary display expanded to 10 sections.
- Standard pages updated to: Home, About, Issues, Privacy Policy, Cookie Policy, SMS Terms.

#### Fields Summary
- ~40 new fields added
- ~14 fields removed/deferred to future modules
- ~15 fields modified (renamed, restructured, or retyped)
- ~20 fields kept as-is

### Added
- Candidate landing page template shortcodes (`atp_cand_*`) for reusable campaign websites
  driven by intake form data.
- CHANGELOG.md for version tracking.
- Admin "Changelog" page under ATP Shortcodes menu.

---

## [2.0.0] - 2026-04-08

### Added
- Initial 18-step candidate intake form with full admin backend.
- Custom Post Type (atp_candidate) for submission storage.
- Email notification system with HTML templates.
- Admin settings: question editor, branding, notifications.
- JSON export (single + bulk).
- LocalStorage form persistence with auto-save.
- Animated dark-theme UI with canvas background.

---

## [1.2.0] - 2026-03-15

### Added
- ATP Homepage shortcode set (13 shortcodes).
- Brand Guide shortcode set (13 shortcodes).
- Demo Hub landing page.
- Page Importer with one-click page creation and SEO metadata.
- Setup Wizard for first-run onboarding.
- GitHub auto-updater.

---

## [1.0.0] - 2025-10-01

### Added
- Initial release: ATP Logo shortcode, plugin framework.
