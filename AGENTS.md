# AGENTS.md — Operating Rules for AI Coding Agents

This file is read-on-arrival by any AI coding agent working in this
repository (Claude Code, Cursor, Aider, Copilot Workspace, etc.) and
by humans who want to understand how AI agents are expected to behave
here. It complements `README.md` and
`packages/atp-plugin-core/ARCHITECTURE.md`.

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

If you're on `main` or a `claude/*` branch: focus on the candidate
platform. The marketing-site HTML files at the repo root
(`ATP-Homepage-Mockup.html`, `brand-guide.html`, `index.html`, the
`css/` and `js/` brand assets, the `ATP-Logo-*.png` files) are kept
here transitionally and will be removed once the `atp-website` branch
is exported as its own repo.

If you're on `atp-website`: only touch ATP marketing files. The
plugin, `sites/`, `scripts/`, and `dist/` are out of scope on that
branch.

---

## The two-repo split

This monorepo currently contains both:
- The candidate-site plugin platform (the primary purpose)
- ATP's marketing site HTML/CSS/JS (transitional)

The plan is to split the marketing site into its own repo. The
`atp-website` branch holds a clean version of just those files so it
can be exported.

Files that belong to the **ATP marketing site** (i.e. on the
`atp-website` branch, will leave this repo eventually):

- `ATP-Homepage-Mockup.html`
- `brand-guide.html`
- `index.html`
- `ATP-Logo-Blue-White.png`, `ATP-Logo-Red-White.png`,
  `ATP-Logo-Standard.png`
- `css/brand.css`
- `js/brand-*.js`

Files that belong to the **candidate platform** (i.e. stay in this
repo permanently):

- `packages/atp-plugin-core/` — shared plugin code
- `sites/` — per-client configs
- `scripts/` — build pipeline
- `dist/` — build output (gitignored)
- `docs/` — engineering docs
- `playground-blueprint.json` — Playground demo
- `atp-candidate-intake.php` — top-level legacy copy of the intake plugin
- `atp-demo-plugin/` — legacy v2 plugin folder (kept until v3 migration is verified everywhere)
- `campaign-site/` (Sarah Chen demo), `personal-site/` (Michael Torres demo) — candidate-site mockups for reference

The intake form code is **shared between both contexts** by virtue of
living in the plugin. When the marketing site repo is broken out, it
will pull the intake form from the published plugin (or sync the
file). Either way, the schema lives at
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
4. **Two plugin folders, one canonical.**
   `packages/atp-plugin-core/` is canonical (v3.1.0+). The legacy
   `atp-demo-plugin/` (v2.1.0) is kept as a mirror for backwards
   compatibility but should not be the primary edit target. Mirror
   non-trivial changes to both during the deprecation window.
5. **Schema changes are big deals.** `v3-schema.json` and
   `v3-field-map.json` are contracts between the intake form and
   site generation. If you change them, you must also: update the AI
   prompt template (`PROMPT-TEMPLATE.md`), update
   `docs/json-schema.md`, and add an explicit migration note to
   `EDIT_LOG.md`.
6. **Secrets never enter the repo.** Service-account JSON keys, API
   tokens, OAuth refresh tokens — none of these go into git, the WP
   database, or chat. The Drive integration uses a JSON key file
   stored outside the web root; see `docs/google-drive-setup.md`.
7. **Don't push to `main` without an explicit instruction.** Default
   to a `claude/*` task branch. Open a PR or wait for review.
8. **Test what you can locally.** PHP `php -l <file>` for syntax,
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
| Edit log (READ THIS FIRST) | `EDIT_LOG.md` |

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
