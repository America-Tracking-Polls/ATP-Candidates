# AGENTS.md — Operating Rules (atp-website branch)

This branch contains only the **ATP marketing-site** static files
(HTML/CSS/JS for `americatrackingpolls.com`). It was carved out of
the `mirror-factory/ATP-Demo` monorepo so it can be exported as its
own repository.

If you're an AI agent (Claude Code, Cursor, Aider, Copilot) working
in this branch, follow the rules below. If you find yourself needing
to edit anything outside this branch's scope, you're on the wrong
branch — switch to `main` or a `claude/*` branch.

---

## The single most important rule

**Every change MUST be logged in `EDIT_LOG.md` at the repo root in
the same commit (or the commit immediately after).** New entries go
at the top. Format is at the top of `EDIT_LOG.md`. No exceptions.

---

## Scope of this branch

In scope:
- `index.html`
- `ATP-Homepage-Mockup.html`
- `brand-guide.html`
- `ATP-Logo-*.png`
- `css/brand.css`
- `js/brand-*.js`
- `AGENTS.md`, `EDIT_LOG.md`, `README.md`

Out of scope (do not add):
- WordPress plugin code
- Intake form code (PHP)
- Per-client `sites/<slug>/` folders
- Build scripts
- Drive integration code
- Anything PHP

If a request needs the intake form or the candidate-site plugin,
push back: that work belongs on the `main` branch (or a `claude/*`
task branch) of this repo, not here.

### About the V3 JSON contract

In the candidate-platform repo, page templates are bound to a V3
JSON schema (`v3-schema.json`) that drives every shortcode. **That
contract does not apply here.** Files in this branch are pure static
HTML/CSS/JS for ATP's own marketing site — no JSON contract, no
field map, no schema migration. If you find yourself thinking about
JSON-driven content, you are on the wrong branch.

---

## Style + scope rules

1. **Don't refactor uninvited.** Stick to the user's ask.
2. **Match the existing style.** The marketing pages use vanilla
   HTML + CSS variables + GSAP animations from a CDN. No build step.
   No frameworks. Don't introduce React, Tailwind, Vite, etc. unless
   the user asks.
3. **No new dependencies without permission.** GSAP is grandfathered.
   New CDN scripts or npm packages need explicit approval.
4. **Test in a browser.** Open the affected HTML file locally and
   eyeball the change before committing. The marketing site has no
   automated tests.
5. **Don't commit secrets.** Service-account keys, API tokens, OAuth
   refresh tokens — none of these go into git or chat.
6. **Default to a task branch.** Don't push to `atp-website`
   directly without explicit instruction. Use a `claude/*` branch
   off `atp-website` and open a PR (once this branch becomes its own
   repo, this rule applies to the new `main` there).

---

## Edit log convention

```
## YYYY-MM-DD — Short title

**Branch:** `branch-name` &nbsp; **Commits:** `abc1234`

Optional 1-2 sentence summary.

### Done
- bullet list

### In progress / blocked
- bullet list with reason
```

If a change is partial (e.g., placeholder waiting on assets), say so.
Don't oversell.

---

## Quick checklist before you commit

- [ ] HTML/CSS/JS changes match existing style
- [ ] Visual check in a browser
- [ ] `EDIT_LOG.md` has a top-of-file entry describing what you did
- [ ] No secrets in the diff
- [ ] Branch name reflects the task; you didn't force-push the main
      branch of the repo
- [ ] Commit message explains the *why*, not just the *what*
