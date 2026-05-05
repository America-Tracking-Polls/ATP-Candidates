# ATP Marketing Site

**Branch:** `atp-website` (export this branch into a standalone repo)
**Origin monorepo:** `mirror-factory/ATP-Demo` (candidate-site
platform, separate concern)

This branch contains the static HTML/CSS/JS for ATP's own marketing
site — the public-facing pages on `americatrackingpolls.com`. It was
carved out of the candidate-platform monorepo so it can be maintained
in its own repository by people who don't need to touch the campaign
plugin.

> **If you arrived here looking for the candidate-site WordPress
> plugin, the build pipeline, the intake form code, or per-client
> configurations — those live on the other branches of this repo
> (or, eventually, in `mirror-factory/ATP-Demo` after this branch
> has been split out).**

---

## Files in this branch

| File | What it is |
|---|---|
| `index.html` | ATP demo hub landing page |
| `ATP-Homepage-Mockup.html` | The main marketing landing page (the page reviewed in the slide notes — hero, scrolling header, About, survey simulation, pipeline, AEO/ChatGPT, compliance, intake CTA, footer) |
| `brand-guide.html` | ATP brand guide (colors, typography, logo usage) |
| `ATP-Logo-Blue-White.png`, `ATP-Logo-Red-White.png`, `ATP-Logo-Standard.png` | Logo assets |
| `css/brand.css` | Brand stylesheet |
| `js/brand-*.js` | Brand-specific JS modules (charts, issues, map, network, pixels, quiz) |
| `AGENTS.md` | Operating rules for AI agents working in this branch |
| `EDIT_LOG.md` | Running log of edits (please keep updated) |

## What's intentionally NOT in this branch

- The candidate-site WordPress plugin
  (`packages/atp-plugin-core/`)
- The intake form code (lives in the plugin)
- Per-client folders (`sites/`)
- Build scripts (`scripts/`)
- Engineering docs about the plugin (`docs/`)

The intake form on `americatrackingpolls.com` is rendered by the WP
plugin, not by these static files. When this branch becomes its own
repo, the intake form will continue to be served by the plugin code
in the candidate-platform repo (synced or pulled at deploy time).

## Working in this branch

1. Read `AGENTS.md` for the conventions (especially the EDIT_LOG.md
   rule).
2. For HTML/CSS edits, just edit and preview locally
   (`open ATP-Homepage-Mockup.html`).
3. Add an entry to `EDIT_LOG.md` describing what you changed.
4. Commit and push.

## Splitting into a standalone repo

This branch is structured to be exported as-is. Suggested steps:

```sh
# from the parent ATP-Demo working tree
git checkout atp-website
git checkout-index -a --prefix=../atp-website-export/

cd ../atp-website-export
git init
git add .
git commit -m "Initial import from mirror-factory/ATP-Demo (atp-website branch)"
git remote add origin <new-repo-url>
git push -u origin main
```

After the new repo is up, the corresponding files can be removed from
the candidate-platform monorepo in a follow-up commit.
