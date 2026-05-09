# Edit Log — ATP Marketing Site (atp-website branch)

> **Convention:** Whenever edits are made to this branch / repo,
> **always add an entry to this edit log**, dated, with the affected
> files and a brief description. New entries go at the **top** (most
> recent first). This applies to humans, AI assistants, and
> automation alike.
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

## 2026-05-05 — Refactor marketing site to shortcode library

**Branch:** `atp-website`
**Commits:** _pending push_
**Plugin version:** 1.0.0 → 2.0.0

User's instinct was right: shortcodes = editable, slottable, one
unified pattern across both the marketing site and the candidate
plugin. Refactored the wrapper-plugin (which served static HTML) into
a real shortcode-based plugin that mirrors the candidate-platform
pattern.

### Architecture

- 13 shortcodes (`[atp_mkt_*]`), one per logical section:
  styles, poll_bar, header, hero, about, survey, journey, pipeline,
  aeo, compliance, intake, footer, scripts
- Each shortcode's default content lives in `templates/<file>`
- WP options override defaults (key: `atp_mkt_sc_<tag>`) — admins
  edit per-section without touching the rest, and can revert to
  default
- Plugin activation creates 3 WP pages: Marketing Home (composed of
  all 13 shortcodes), Brand Guide, Demo Hub
- Admin UI at WP Admin → ATP Marketing: shortcode library overview
  + per-shortcode editor with Save / Revert
- Same edit pattern as the candidate-platform plugin → one mental
  model for both

### Done

- New `atp-marketing-plugin.php` (replaces the previous static-HTML
  wrapper). Includes:
  - Shortcode registry (`atp_mkt_registry()`) — tag → template,
    wrapper open/close, label, description
  - Generic renderer reading from option override or template file
  - Activation hook: creates Marketing Home / Brand Guide / Demo Hub
    pages with shortcode markup
  - Admin pages: Library (overview) + Edit Shortcodes (textarea
    editor per shortcode, with revert)
- New `templates/` folder with 13 files extracted from
  `ATP-Homepage-Mockup.html`:
  - `styles.html` (277 lines, all CSS)
  - `poll-bar.html`, `header.html`, `hero.html`, `about.html`,
    `survey.html`, `journey.html`, `pipeline.html`, `aeo.html`,
    `compliance.html`, `intake.html`, `footer.html`
  - `scripts.js` (159 lines of GSAP + canvas + ticker JS)
- Updated `playground-blueprint.json`:
  - Writes a proper Canvas page template into the active theme that
    includes `wp_head()` / `wp_footer()` so styles and scripts load
  - Installs the plugin (which creates the marketing pages)
  - Post-install: sets `_wp_page_template = page-canvas.php` on each
    marketing page so they render full-bleed without theme chrome
  - Lands on `/marketing/`
- Updated `AGENTS.md` scope: now allows the plugin + templates/
  directory (was "single PHP file only")
- Updated `README.md` with the shortcode library, composition
  guidance, and the new admin paths
- Bumped plugin version 1.0.0 → 2.0.0

### Why this matters
The marketing site is now editable section-by-section through:
- WP Admin (ATP Marketing → Edit Shortcodes)
- WP REST API (any tool that can write to `atp_mkt_sc_*` options)
- AI / MCP (when the WP MCP Adapter is wired up — same option
  storage works)
The original `ATP-Homepage-Mockup.html` is preserved as the canonical
fallback / reference. If the plugin is ever removed, the static file
still renders the site as it was.

### Not yet shortcoded
- Brand guide page (`brand-guide.html`) — still served as static.
  Activation creates a placeholder WP page; full shortcoding deferred.
- Demo hub (`index.html`) — same.

These are easy follow-up commits if/when needed.

### Skipped — needs input
(unchanged from prior entries: hero MP4, Typeform embed, BIO/SLOGAN
content source, WIN BEFORE ELECTION DAY graphic placement, etc.)

---

## 2026-05-05 — Fix the three "missed/done wrong" landing-page items

**Branch:** `atp-website`
**Commits:** _pending push_
**File:** `ATP-Homepage-Mockup.html`

Cleaned up the three open items from the original review-slide notes
that were either missed or placed in the wrong section.

### Done

- **Survey iPhone mockup is taller.** `.iphone` width 320 → 340,
  height 680 → 820. The inner SMS thread + Typeform questions now
  have room to breathe and stay readable without the screen feeling
  cramped.
- **"Schedule your free consult" CTA moved above the SMS-survey
  simulation.** Added a `.survey-cta-banner` block at the top of the
  `survey-sim` section with a red button that anchors to `#intake`.
  Reverted the intake section heading from "SCHEDULE YOUR FREE
  CONSULT" back to "GET STARTED WITH ATP" so the CTA isn't
  duplicated as a section title later in the page.
- **Dropped the secondary hero CTA.** Removed "WATCH THE VIDEO"
  button. The hero now has one CTA — "SCHEDULE A STRATEGY CALL" —
  pointing at the intake section. The video sits adjacent in the
  hero-media column anyway, so the redundant CTA was pulling focus.

### Still pending (asset-blocked)
- Hero MP4 (`<source src="">` still empty)
- Typeform Campaign Application embed
- "WIN BEFORE ELECTION DAY" graphic placement
- Quick-View Benchmark Survey + social-media examples block
- Sample Typeform Benchmark Survey
- BIO/SLOGAN section edits (content lives elsewhere — source TBD)

---

## 2026-05-05 — Make atp-website installable as a WP plugin (Playground)

**Branch:** `atp-website`
**Commits:** _pending push_

User asked to be able to boot the marketing site inside WordPress
Playground. Added a single-file WP wrapper plugin and a Playground
blueprint at the branch root.

### Done
- New `atp-marketing-plugin.php` — single PHP file at the branch
  root with a proper Plugin Name header. On activation it registers
  three rewrite rules:
  - `/marketing/` → `ATP-Homepage-Mockup.html`
  - `/marketing/brand/` → `brand-guide.html`
  - `/marketing/hub/` → `index.html`
  Each route reads the corresponding HTML file, rewrites relative
  `href=` and `src=` URLs (`css/brand.css`, `js/brand-*.js`, ATP
  logos) to plugin URLs so assets load correctly, and serves the
  result with `Content-Type: text/html`. Also adds an "ATP Marketing"
  admin menu with links to all three pages.
- New `playground-blueprint.json` — boots WP, sets pretty
  permalinks (required for rewrite rules), installs the plugin from
  this branch via `git:directory` with `path: "."`, lands on
  `/marketing/`.
- `AGENTS.md` updated: scope now allows this single PHP wrapper
  file. "No PHP" rule relaxed accordingly. Additional PHP still
  out of scope.
- `README.md` updated: added the new files to the file table and a
  "Open this in WordPress Playground" section with the boot link.

### Caveats
- Blueprint uses `path: "."` (whole branch as the plugin directory).
  If Playground rejects that, fallback is to move the plugin + static
  assets into an `atp-marketing-plugin/` subdirectory and switch the
  blueprint path. Will iterate based on user testing.
- The plugin is **optional** in production. ATP's actual marketing
  site is served as static HTML/CSS/JS — this plugin exists purely
  for previewing the site inside WP. When this branch is exported
  into its own repo, the static-only path is still the recommended
  deployment.

---

## 2026-05-05 — AGENTS.md note: V3 JSON contract is out of scope

**Branch:** `atp-website`
**Commits:** _pending push_

Added a short clarifying note to `AGENTS.md` so an AI agent landing
on this branch isn't confused about JSON contracts. The
candidate-platform repo binds its page templates to
`v3-schema.json` / `v3-field-map.json`, but **this branch has no
such contract** — it's pure static marketing HTML/CSS/JS.

The candidate-platform monorepo (`mirror-factory/ATP-Demo`,
`claude/activate-drive-upload-P3yOj` and `main`) finished its half
of the split today: `ATP-Homepage-Mockup.html`, `brand-guide.html`,
the brand CSS/JS, and two of the three ATP logo PNGs were removed
from that branch. Only `ATP-Logo-Standard.png` was kept there
(referenced by an ATP-branded intake landing page that fronts the
intake form on the candidate-onboarding WP install).

### Done

- AGENTS.md gains a short "About the V3 JSON contract" subsection
  under Scope, explaining the contract belongs to the
  candidate-platform repo and does NOT apply here.

### No file changes
Files in scope on this branch are unchanged.

---

## 2026-05-05 — Branch initialized from monorepo carve-out

**Branch:** `atp-website` (created from
`mirror-factory/ATP-Demo` `claude/activate-drive-upload-P3yOj`
at commit `28bd359`)

This branch was carved out of the candidate-site monorepo so the
ATP marketing site can be maintained in its own repository. Only
ATP-marketing files are present here.

### Done
- Removed candidate-platform code: `packages/`, `sites/`,
  `scripts/`, `atp-demo-plugin/`, `atp-candidate-intake.php`,
  `campaign-site/`, `personal-site/`, `playground-blueprint.json`,
  `CHANGELOG.md`, all engineering docs under `docs/`.
- Replaced `README.md` with an ATP-marketing-site overview.
- Replaced `AGENTS.md` with rules scoped to this branch.

### Files retained
- `index.html`, `ATP-Homepage-Mockup.html`, `brand-guide.html`
- `ATP-Logo-Blue-White.png`, `ATP-Logo-Red-White.png`,
  `ATP-Logo-Standard.png`
- `css/brand.css`
- `js/brand-charts.js`, `js/brand-issues.js`, `js/brand-map.js`,
  `js/brand-network.js`, `js/brand-pixels.js`, `js/brand-quiz.js`

### Carries over from the monorepo
The landing-page revisions applied on
`claude/activate-drive-upload-P3yOj` (commit `5b6cc40`) are
already baked into `ATP-Homepage-Mockup.html` on this branch.
Specifically:
- Scrolling header trimmed to 4 items
  (Data Insights, 5 Strategies, Compliance, AI Optimized)
- Header nav adds Compliance / AI Ethics / Privacy anchors
- Hero tag, headline, body, video+Typeform placeholders
- About section rewritten to the 95% multi-channel hook
- Survey section "What You Learn" / "How It Powers Your Campaign"
  rewritten with the new 5-bullet list and 4-paragraph block
- Pipeline branches: MAIL → QR PRINT, SMS → MMS, small descriptions
  removed
- Intake section retitled "Schedule Your Free Consult"; phone,
  email, samples link added in CTA + footer

### Still pending (was open at carve-out time)
- Hero MP4 source (`<source src="">` is empty)
- Typeform Campaign Application embed
- BIO/SLOGAN section edits — that content was reported to be on the
  deployed site / a different page, never landed here
- "WIN BEFORE ELECTION DAY" graphic placement
- Three "missed or done wrong" items: survey-view height, "Schedule
  your free consult" CTA placement, hero CTAs decision
- Quick-View Benchmark Survey + social-media examples block
- Sample Typeform Benchmark Survey

### Next steps
1. Export this branch into a standalone repo (instructions in
   `README.md`).
2. Once exported, remove the corresponding files from the
   candidate-platform monorepo on its own branch.
