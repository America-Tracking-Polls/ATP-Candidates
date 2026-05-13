# AGENTS.md — Operating Rules for AI Coding Agents

This file is read-on-arrival by any AI coding agent working in this
repository (Claude Code, Cursor, Aider, Copilot Workspace, etc.) and
by humans who want to understand how AI agents are expected to behave
here. It complements `README.md` and
`packages/atp-plugin-core/ARCHITECTURE.md`.

> **Before you start: read `HANDOFF.md` at the repo root.**
> It's the single source of truth for what's shipped, what's still
> being tested, and what to do next. If anything you're about to do
> contradicts it, stop and ask.

> **The single most important rule:**
> Every change you make to this repo MUST be logged in `EDIT_LOG.md`
> at the repo root in the same commit (or the commit immediately
> after). New entries go at the **top** of the file. No exceptions.

---

## What this repo is

A WordPress plugin monorepo that powers two kinds of sites from one
codebase:

1. **The ATP intake host** — a WordPress install (typically on
   `americatrackingpolls.com`) where political candidates submit a
   16-step intake form. The form lives at the `[atp_intake]`
   shortcode.
2. **Candidate campaign sites** — one WordPress install per candidate,
   each running a built copy of this plugin, rendering the candidate's
   7-page campaign site via the `[atp_cand_*]` shortcode family.

The shared plugin code lives at `packages/atp-plugin-core/`. Per-client
config lives at `sites/<slug>/`. Build pipeline:
`scripts/new-site.sh` → AI generates `page-json.json` →
`scripts/build-site.sh` → `dist/<slug>/atp-campaign-site/` (deployable
plugin).

For full detail, read `packages/atp-plugin-core/ARCHITECTURE.md`. It
has three diagrams (repo layout, intake submission lifecycle, new-site
deployment) and an FAQ.

---

## EDIT_LOG.md — the convention

Whenever you make changes — code, content, copy, config, assets,
docs, anything — add an entry to `EDIT_LOG.md`. The convention:

- New entries go at the **top** of the file (most recent first).
- Date the entry with today's date in `YYYY-MM-DD`.
- Group multiple changes from the same session under one dated entry
  rather than creating several entries on the same day.
- Use the entry template at the top of `EDIT_LOG.md`:

```
## YYYY-MM-DD — Short title

**Branch:** `branch-name` &nbsp; **Commits:** `abc1234`, `def5678`

Optional 1–2 sentence summary.

### Done
- bullet list

### In progress / blocked
- bullet list with reason
```

- If a change is partial (e.g., placeholder waiting on assets), say
  so explicitly. Don't oversell.
- If you skipped something on the user's list because of missing
  input, list it under a "Skipped — needs input" section.
- After committing your code change, append the commit hash to the
  entry's `Commits:` line. If you committed the log update separately,
  that's fine — just include both hashes.

**Why this exists:** humans pick up where AI agents left off (and vice
versa). The edit log is the shared state. If it's out of date, the
next agent or engineer is flying blind.

---

## Branches you should know about

| Branch | Purpose |
|---|---|
| `main` | Production-ready candidate-site monorepo |
| `claude/...` | In-flight AI agent work, scoped per task |
| `atp-website` | Carved-out copy of the ATP marketing site files, intended for export to a separate repo. Do **not** add candidate-platform code to this branch. See "The two-repo split" below. |

If you're on `main` or a `claude/*` branch: this repo is the
**candidate-site platform**. It contains the plugin, the build
pipeline, per-client configs, the candidate-site demo mockups, and
an ATP-branded intake landing page (`index.html`) that fronts the
intake form on whatever WP install hosts this plugin. The standalone
ATP marketing site (homepage mockup, brand guide, brand JS) lives on
the `atp-website` branch — do **not** add those back here.

If you're on `atp-website`: only touch ATP marketing files. The
plugin, `sites/`, `scripts/`, and `dist/` are out of scope on that
branch.

---

## The two-repo split

This repo split into two scopes on 2026-05-05:

- **This repo (`mirror-factory/ATP-Demo`)** — the candidate-site
  platform. ATP's marketing site files are NOT here.
- **`atp-website` branch** — a carved-out copy of just the ATP
  marketing site files, intended to be exported into its own repo.

Files that belong to the **candidate platform** (this repo):

- `packages/atp-plugin-core/` — shared plugin code
- `sites/` — per-client configs
- `scripts/` — build pipeline
- `dist/` — build output (gitignored)
- `docs/` — engineering docs
- `playground-blueprint.json` — Playground demo
- `legacy/` — historical files kept for reference only; **not loaded**.
  This is where the old root-level `atp-candidate-intake.php` was
  moved on 2026-05-13 because its Plugin header was causing repo-ZIP
  uploads to install an intake-only plugin instead of the canonical
  full plugin. See `legacy/README.md`.
- `scripts/build-plugin-zip.sh` — the one supported way to produce an
  installable plugin ZIP. Never upload the repo ZIP directly.
- `campaign-site/` (Sarah Chen demo), `personal-site/` (Michael
  Torres demo) — **foundational examples** of what a real candidate
  site looks like. Treat as reference templates, not throwaway
  demos. They share the V3 JSON contract.
- `index.html` — ATP-branded landing page that fronts the intake
  form on whatever WP install hosts this plugin. Can be removed
  from a candidate's WP install once their site goes live.

Files that belong to the **ATP marketing site** (`atp-website`
branch — keep them out of this repo):

- `ATP-Homepage-Mockup.html`
- `brand-guide.html`
- `css/brand.css`
- `js/brand-*.js`

The intake form code is shared between both contexts by virtue of
living in the plugin. When the marketing site repo is broken out
into its own repository, it will pull the intake form from the
published plugin (or sync the file). Either way, the schema lives at
`packages/atp-plugin-core/v3-schema.json` and is the contract.

---

## Style + scope rules for AI agents

These are project-specific overrides on top of normal best practices.

1. **Don't refactor uninvited.** Stick to the user's ask. If you spot
   an unrelated bug, mention it in the edit log under "Noticed but
   didn't touch" — don't fix it silently.
2. **PHP follows the existing minified-ish style.** The plugin code is
   intentionally compact (single-line `if` guards, tight method
   bodies). Match the surrounding style; don't reformat.
3. **No new dependencies without permission.** No Composer, no NPM
   packages, no CDN scripts. Use WordPress core APIs (`wp_remote_*`,
   `wp_mail`, `openssl_sign`, etc.). Existing third-party scripts
   (GSAP CDN on the marketing pages) are grandfathered.
4. **One canonical plugin folder.** `packages/atp-plugin-core/` is the
   only WP plugin in this repo. The previous legacy `atp-demo-plugin/`
   mirror was deleted on 2026-05-05 because installing it caused
   fatal errors (its bootstrap drifted from canonical). Don't recreate
   it.
5. **Schema changes are big deals.** `v3-schema.json` and
   `v3-field-map.json` are contracts between the intake form and
   site generation. If you change them, you must also: update the AI
   prompt template (`PROMPT-TEMPLATE.md`), update
   `docs/json-schema.md`, and add an explicit migration note to
   `EDIT_LOG.md`.
6. **Page templates must stay compatible with the V3 JSON.** This is
   the most important rule about candidate-site work. Every shortcode
   in the `atp_cand_*` family is fed by specific paths in the V3 JSON
   (see the JSON-source table in `README.md` and the field map in
   `v3-field-map.json`). When you edit the styling, structure, or
   markup of any candidate-site template — whether in
   `packages/atp-plugin-core/includes/registry.php`, in
   `candidate-page.php`, or in the static demo mockups in
   `campaign-site/` and `personal-site/` — the result MUST still
   render correctly when fed real V3 JSON output. That means:
   - Don't rename or remove a placeholder/variable that the JSON
     populates.
   - Don't introduce new required fields without adding them to the
     schema (see rule 5) and the intake form.
   - If a template element should be optional, the markup must
     gracefully handle missing JSON fields (empty strings, missing
     keys, empty arrays).
   - The two demo sites (`campaign-site/` Sarah Chen and
     `personal-site/` Michael Torres) exist as **examples and
     foundations** for what a real candidate site looks like. If you
     change them, the changes should be portable back to the
     plugin's templates and the JSON contract.
7. **Secrets never enter the repo.** Service-account JSON keys, API
   tokens, OAuth refresh tokens — none of these go into git, the WP
   database, or chat. The Drive integration uses a JSON key file
   stored outside the web root; see `docs/google-drive-setup.md`.
8. **Don't push to `main` without an explicit instruction.** Default
   to a `claude/*` task branch. Open a PR or wait for review.
9. **Test what you can locally.** PHP `php -l <file>` for syntax,
   WordPress Playground for runtime checks (use
   `playground-blueprint.json` as the entry).

---

## Things that already exist — don't reinvent

Before adding new infrastructure, check whether one of these already
covers it:

| Thing | Where |
|---|---|
| Intake form (16 steps, V3 schema) | `packages/atp-plugin-core/includes/intake/atp-candidate-intake.php` |
| File upload routing (WP media / Drive) | `packages/atp-plugin-core/includes/file-upload.php` |
| Drive API client | `packages/atp-plugin-core/includes/drive-client.php` |
| Drive setup walkthrough | `docs/google-drive-setup.md` |
| Whitelabel / brand settings page | `packages/atp-plugin-core/includes/whitelabel.php` |
| Per-client config loader | `packages/atp-plugin-core/includes/site-config.php` |
| Page importer (creates 7 pages) | `packages/atp-plugin-core/includes/importer.php` |
| Page-JSON → shortcode importer | `packages/atp-plugin-core/includes/candidate-page.php` |
| Auto-update from GitHub | `packages/atp-plugin-core/includes/updater.php` |
| New-site scaffold | `scripts/new-site.sh` |
| Per-client build | `scripts/build-site.sh` |
| Architecture doc with diagrams | `packages/atp-plugin-core/ARCHITECTURE.md` |
| Handoff guide (READ THIS FIRST) | `HANDOFF.md` |
| Edit log (chronological history) | `EDIT_LOG.md` |
| Candidate-site provisioning flow | `docs/candidate-site-flow.md` |
| Override system (template + data + toggle) | `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` |
| AI editing skill for live sites | `.claude/skills/atp-site-edit/SKILL.md` |

---

## Quick checklist before you commit

- [ ] Code changes match existing style in the file you edited
- [ ] PHP files pass `php -l`
- [ ] `EDIT_LOG.md` has a top-of-file entry describing what you did
- [ ] No secrets in the diff (`git diff | grep -i 'BEGIN PRIVATE\|client_email\|api[_-]key\|password.*=\|token.*=' || echo OK`)
- [ ] If you touched both plugin folders, both passed lint
- [ ] Branch name reflects the task; you didn't force-push `main`
- [ ] Commit message explains the *why*, not just the *what*

If any item fails, don't commit yet.
