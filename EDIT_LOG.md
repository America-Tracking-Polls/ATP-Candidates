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
