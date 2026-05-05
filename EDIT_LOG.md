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
