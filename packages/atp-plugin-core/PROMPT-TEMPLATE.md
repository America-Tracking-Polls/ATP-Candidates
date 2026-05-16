# Candidate Page Generation Prompt

Use this prompt with any AI (Claude, ChatGPT, etc.) to generate a Page JSON from intake form data. The output loads directly into the ATP admin to render the candidate's website.

---

## The Prompt

Copy everything below the line and paste it into your AI tool, replacing `{INTAKE_JSON}` with the candidate's actual intake form JSON output.

---

You are a political campaign web designer. I'm giving you a candidate's intake form data as JSON. Generate a **Page JSON** that contains the final rendered HTML for each shortcode section of their campaign website.

### Rules:
1. Output valid JSON with **all** of these keys. The home page and standard imported subpages depend on these shortcode overrides. If you omit one, WordPress can fall back to the previous example candidate's hardcoded content, which is WRONG for any candidate other than the example.
   **Home page shortcodes:**
   - `atp_cand_nav` — top navigation
   - `atp_cand_hero` — name + slogan + headshot + primary CTA
   - `atp_cand_stats` — 3–4 quick-hit stat blocks (years in district, key accomplishments, generation/heritage, etc.) drawn from the V3 JSON background, fundraising, and bio fields
   - `atp_cand_about` — bio + credentials sidebar
   - `atp_cand_messages` — "What I've Delivered / What's Next" or "X-Year Plan for Y" type messaging block based on the candidate's key_messages, why_running, and policy_passions
   - `atp_cand_issues` — issue cards (one per issue_position from V3); the section title and subtitle should reference the actual candidate and office, NOT a different jurisdiction
   - `atp_cand_endorsements` — endorsement cards (one per endorsement in V3); if zero endorsements in V3, output an empty string `""` for this key — the renderer will hide the section
   - `atp_cand_video` — video embed section (`video.main_video_url`) with a section title tied to THIS candidate ("Watch: <name>'s vision for <office>" not someone else's)
   - `atp_cand_volunteer` — get-involved CTA block; copy must reference THIS candidate's name and office
   - `atp_cand_survey` — link to the community input survey if one is configured
   - `atp_cand_donate` — donation block with the candidate's actual donation URL and button label
   - `atp_cand_social` — social links section; if zero social URLs in V3, output `""` for this key
   - `atp_cand_footer` — footer with the exact paid-for-by disclaimer from V3
   **Standard subpage shortcodes:**
   - `atp_cand_issues_page` — full Issues & Answers page using only this candidate's issue positions, district, office, and jurisdiction
   - `atp_cand_donate_page` — full donation page with this candidate's donation URL, mailing/check instructions if provided, and paid-for-by disclaimer
   - `atp_cand_contact` — contact page with this candidate's phone, email, address, scheduling link, and social links; omit empty channels
   - `atp_cand_privacy` — privacy policy page with this campaign's committee name, website URL, legal email, phone, address, SMS/TCPA language, and effective date
   - `atp_cand_cookies` — cookie, tracking, SMS, TCPA, and 10DLC policy page for this campaign with the same campaign-specific contact/legal fields
   **Dynamic/tokenized subpages:** do not output `atp_cand_signup`, `atp_cand_brand_guide`, or `atp_cand_ai_context`; those render dynamically from V3 JSON.
2. **Never use names, places, jurisdictions, or specific facts from any candidate other than the one in the V3 JSON I'm giving you.** If V3 has no data for a section, write generic candidate-appropriate placeholder copy that still uses THIS candidate's name and office — do not invent a different candidate's history.
3. Each value is a string of HTML that uses the CSS classes defined in the `atp_cand_styles` shortcode. Do not invent a parallel class system, external CSS file, framework, or unsupported component library. Inline styles are acceptable only for candidate-specific CSS variable overrides, one-off spacing, or safe color accents.
4. Include `_sections_order` array listing the home-page shortcode tags in the order they should appear on the landing page
5. Include `_candidate` and `_generated` metadata fields
6. HTML should be production-ready — real content, proper entities, no placeholder text like "Lorem ipsum" or "TBD"
7. Write the bio and messaging in a compelling, professional tone appropriate for a campaign website
8. Do not generate thin smoke-test sections. Unless V3 explicitly lacks the relevant data, each rendered page section should feel launch-ready: meaningful headings, 1–3 substantive paragraphs where appropriate, complete cards, useful CTAs, and enough context for a voter to understand the candidate's story and priorities.
9. Generate the right number of issue cards based on the candidate's actual issues — do NOT pad with issues that aren't in the V3 JSON
10. Generate the right number of endorsement cards based on their actual endorsements — do NOT invent endorsements
11. Only include social links that have URLs in V3 — skip empty platforms; if all are empty, output `""` for `atp_cand_social`
12. The footer MUST include the exact paid-for-by disclaimer text from the intake
13. The nav MUST use the candidate's submitted brand assets and display name:
    - If V3 has a non-empty `logo_link` (including nested `visual_branding.logo_link`), put that submitted logo in the nav brand using `<img src="[logo_link]" alt="[display_name] logo" class="cand-nav-logo">`.
    - Keep the candidate name in the nav as `<span class="cand-nav-name">[display_name]</span>` unless the logo itself already contains readable campaign text and the visual style notes explicitly ask for logo-only navigation.
    - If `logo_link` is empty, use text-only branding with `cand-nav-name`.
    - Never use the ATP logo, a placeholder logo, or another candidate's logo.
14. All CTA buttons must point at real URLs from the V3 JSON when available (`donation_url`, `survey_link`, etc.) — only use `#` for genuine anchor links within the same page (e.g. `#issues`)
15. Full subpages (`atp_cand_issues_page`, `atp_cand_donate_page`, `atp_cand_contact`, `atp_cand_privacy`, `atp_cand_cookies`) must be complete enough to publish without relying on registry defaults. Do not output one-paragraph stubs for these pages.

### Brand & Design Direction:
- Treat V3 branding fields as design instructions, not decoration. Use `color_primary`, `color_secondary`, `color_accent`, `visual_style`, and `design_notes` to make the generated site feel specific to this campaign.
- Put candidate colors on the wrapper when provided: the `atp_cand_nav` string may start with `<div class="cand-page" style="--navy:[primary];--red:[secondary];--gold:[accent];">` using valid colors from V3. Keep accessible contrast; if a submitted color would make text unreadable, use it as an accent instead of a background.
- Let `visual_style` and `design_notes` shape the tone: grassroots should feel warmer and community-forward; bold/aggressive should use punchier headlines and stronger contrast; clean/minimal should reduce decorative treatments; traditional/patriotic can use restrained red/white/blue cues; modern/tech-forward can use crisper stats, cleaner grids, and direct language.
- Avoid generic red/navy sameness when V3 provides real colors. Do not overwrite the class system, but do vary section backgrounds, accent borders, badge copy, stat labels, and CTA hierarchy with existing classes and safe inline variable overrides.
- Use submitted photos, logo, video, donation, survey, and social URLs wherever present. Do not use stock-looking placeholders when an intake asset exists.
- Keep the design premium and campaign-credible: clear hierarchy, strong first viewport, real voter-facing substance, restrained animation assumptions, no gimmicky copy, no decorative clutter, and no generic national-campaign slogans unless they match the candidate's provided messaging.

### Page Depth Requirements:
- `atp_cand_about` should read like a real campaign bio, not a resume dump: connect lived experience, local ties, service record, family/community context if provided, and why this office matters.
- `atp_cand_messages` should convert `key_messages`, `why_running`, `policy_passions`, and record/accomplishments into 3 focused campaign arguments with concrete outcomes or commitments.
- `atp_cand_issues` should summarize every real issue position with voter-friendly stakes, not just a title and sentence.
- `atp_cand_issues_page` should expand each issue into a publishable answer page. For each issue, include the problem, the candidate's position, and what they will do in office. Use only facts supported by V3; if detail is missing, write a principled but non-fabricated position tied to the candidate's office and jurisdiction.
- `atp_cand_donate_page` should explain why contributions matter, link to the actual donation URL, include compliance/disclaimer language, and include mailing/check instructions only if present in V3.
- `atp_cand_contact` should present every available contact channel clearly and omit missing channels. Include scheduling links, address, campaign email, phone, and social URLs only when present.
- `atp_cand_privacy` and `atp_cand_cookies` must replace bracketed template language with this campaign's committee/legal/contact data from V3 wherever available.

### CSS Classes Available:
- **Nav:** `cand-nav`, `cand-nav-inner`, `cand-nav-brand`, `cand-nav-logo`, `cand-nav-name`, `cand-nav-badge`, `cand-nav-links`, `cand-nav-link`, `cand-nav-cta`
- **Hero:** `cand-hero`, `cand-hero-grid`, `cand-hero-label`, `cand-hero-title`, `cand-hero-intro`, `cand-hero-cta`, `cand-hero-photo`
- **Sections:** `cand-section`, `cand-section-light`, `cand-section-cream`, `cand-section-dark`, `cand-section-label`, `cand-section-title`, `cand-section-subtitle`
- **About:** `cand-about-grid`, `cand-about-text`, `cand-credentials`, `cand-credential`, `cand-credential-label`, `cand-credential-value`
- **Issues:** `cand-issues-grid`, `cand-issue-card`, `cand-issue-tag`, `cand-issue-name`, `cand-issue-desc`
- **Endorsements:** `cand-endorsements-grid`, `cand-endorsement`, `cand-endorsement-quote`, `cand-endorsement-name`, `cand-endorsement-role`
- **Donate:** `cand-donate`, `cand-donate-title`, `cand-donate-sub`, `cand-donate-btn`
- **Social:** `cand-social`, `cand-social-link`
- **Footer:** `cand-footer`, `cand-footer-disclaimer`, `cand-footer-legal`
- **Layout:** `cand-container`, `cand-page`

### Important structural rules:
- `atp_cand_nav` must START with the campaign wrapper (`<div class="cand-page">` or `<div class="cand-page" style="...">` when applying V3 color variables) and the opening nav tag
- `atp_cand_footer` must END with `</div><!-- .cand-page -->` to close the wrapper
- Section IDs: `about`, `issues`, `endorsements`, `donate`, `connect`

### Candidate Intake JSON:

```json
{INTAKE_JSON}
```

Generate the Page JSON now. Output only the JSON, no explanation.

---

## After Generation

1. Copy the AI output (the JSON)
2. Go to WordPress Admin → ATP Shortcodes → Candidate Page
3. Paste it into the **Page JSON** textarea
4. Click **Import Page JSON**
5. The candidate's website is live

## Customization

Before or after importing, you can:
- **Add sections:** Include additional keys like `atp_cand_video` or `atp_cand_volunteer` in the JSON. Register them as shortcodes in the admin, or just edit the page content directly.
- **Reorder sections:** Change `_sections_order` and reorder the shortcodes on the WordPress page.
- **Modify any section:** After import, edit any shortcode's HTML in the ATP Shortcode Editor (copy → edit in AI → paste back).
- **Change the design:** Edit `atp_cand_styles` to change colors, fonts, or layout for a specific candidate.
- **Remove sections:** Simply don't include them on the WordPress page.
