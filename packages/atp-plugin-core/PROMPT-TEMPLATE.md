# Candidate Page Generation Prompt

Use this prompt with any AI (Claude, ChatGPT, etc.) to generate a Page JSON from intake form data. The output loads directly into the ATP admin to render the candidate's website.

---

## The Prompt

Copy everything below the line and paste it into your AI tool, replacing `{INTAKE_JSON}` with the candidate's actual intake form JSON output.

---

You are a political campaign web designer. I'm giving you a candidate's intake form data as JSON. Generate a **Page JSON** that contains the final rendered HTML for each shortcode section of their campaign website.

### Rules:
1. Output valid JSON with **all** of these keys (the home page lays out all 14 of these shortcodes in this order — if you omit one, the site falls back to the previous example candidate's hardcoded content, which is WRONG for any candidate other than the example):
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
2. **Never use names, places, jurisdictions, or specific facts from any candidate other than the one in the V3 JSON I'm giving you.** If V3 has no data for a section, write generic candidate-appropriate placeholder copy that still uses THIS candidate's name and office — do not invent a different candidate's history.
3. Each value is a string of HTML that uses the CSS classes defined in the `atp_cand_styles` shortcode
4. Include `_sections_order` array listing the shortcode tags in the order they should appear on the page
5. Include `_candidate` and `_generated` metadata fields
6. HTML should be production-ready — real content, proper entities, no placeholder text like "Lorem ipsum" or "TBD"
7. Write the bio and messaging in a compelling, professional tone appropriate for a campaign website
8. Generate the right number of issue cards based on the candidate's actual issues — do NOT pad with issues that aren't in the V3 JSON
9. Generate the right number of endorsement cards based on their actual endorsements — do NOT invent endorsements
10. Only include social links that have URLs in V3 — skip empty platforms; if all are empty, output `""` for `atp_cand_social`
11. The footer MUST include the exact paid-for-by disclaimer text from the intake
12. The nav MUST use the candidate's display_name, NOT a generic "Candidate" or a different candidate's name
13. All CTA buttons must point at real URLs from the V3 JSON when available (`donation_url`, `survey_link`, etc.) — only use `#` for genuine anchor links within the same page (e.g. `#issues`)

### CSS Classes Available:
- **Nav:** `cand-nav`, `cand-nav-inner`, `cand-nav-brand`, `cand-nav-name`, `cand-nav-badge`, `cand-nav-links`, `cand-nav-link`, `cand-nav-cta`
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
- `atp_cand_nav` must START with `<div class="cand-page">` and the opening nav tag
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
