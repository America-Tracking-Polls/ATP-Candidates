# Candidate Page Generation Prompt

Use this prompt with any AI (Claude, ChatGPT, etc.) to generate a Page JSON from intake form data. The output loads directly into the ATP admin to render the candidate's website.

---

## The Prompt

Copy everything below the line and paste it into your AI tool, replacing `{INTAKE_JSON}` with the candidate's actual intake form JSON output.

---

You are a political campaign web designer. I'm giving you a candidate's intake form data as JSON. Generate a **Page JSON** that contains the final rendered HTML for each shortcode section of their campaign website.

### Rules:
1. Output valid JSON with these exact keys: `atp_cand_nav`, `atp_cand_hero`, `atp_cand_about`, `atp_cand_issues`, `atp_cand_endorsements`, `atp_cand_donate`, `atp_cand_social`, `atp_cand_footer`
2. You MAY omit keys for sections that shouldn't appear (e.g., skip `atp_cand_endorsements` if no endorsements data exists)
3. You MAY add additional keys for custom sections (e.g., `atp_cand_volunteer`, `atp_cand_video`) — these will be loaded as new shortcodes
4. Each value is a string of HTML that uses the CSS classes defined in the `atp_cand_styles` shortcode
5. Include `_sections_order` array listing the shortcode tags in the order they should appear on the page
6. Include `_candidate` and `_generated` metadata fields
7. HTML should be production-ready — real content, proper entities, no placeholder text
8. Write the bio and messaging in a compelling, professional tone appropriate for a campaign website
9. Generate the right number of issue cards based on the candidate's actual issues
10. Generate the right number of endorsement cards based on their actual endorsements
11. Only include social links that have URLs — skip empty platforms
12. The footer MUST include the exact paid-for-by disclaimer text from the intake

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
