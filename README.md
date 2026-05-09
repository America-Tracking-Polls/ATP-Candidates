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
| `atp-marketing-plugin.php` | **The WP plugin.** Registers 13 shortcodes (`[atp_mkt_*]`), creates the marketing pages on activation, provides an admin editor. |
| `templates/` | Default content for each shortcode (HTML / CSS / JS). Source of truth when no admin override exists. |
| `ATP-Homepage-Mockup.html`, `brand-guide.html`, `index.html` | Original static pages — kept as canonical references and fallbacks. |
| `ATP-Logo-*.png` | Logo assets. |
| `css/brand.css`, `js/brand-*.js` | Accessory page assets (used by the brand guide / demo hub static pages). |
| `playground-blueprint.json` | Playground recipe — boots WP, writes a Canvas template, installs the plugin, lands on `/marketing/`. |
| `AGENTS.md` | Operating rules for AI agents working in this branch. |
| `EDIT_LOG.md` | Running log of edits. |

## Open this in WordPress Playground

[**Boot in Playground**](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/mirror-factory/ATP-Demo/atp-website/playground-blueprint.json)

The blueprint logs you in as `admin` / `password`, installs the
plugin, creates the marketing pages, and lands on `/marketing/`.

From WP Admin you'll see an **ATP Marketing** menu:

- **ATP Marketing** — overview of the 13-shortcode library, links
  to view the live page
- **Edit Shortcodes** — pick any shortcode, override its content,
  revert to template default

## How the shortcodes work

The marketing homepage is composed of 13 shortcodes:

```
[atp_mkt_styles]      ← all CSS (load once, before everything else)
[atp_mkt_poll_bar]    ← animated 4-item scrolling ticker
[atp_mkt_header]      ← logo + nav + Get Started button
[atp_mkt_hero]        ← WIN YOUR ELECTION + body + video + Typeform
[atp_mkt_about]       ← 95% multi-channel hook + body
[atp_mkt_survey]      ← Schedule CTA + iPhone mockup + What You Learn
[atp_mkt_journey]     ← Strategic Path 5 cards
[atp_mkt_pipeline]    ← Converting Data Into Action
[atp_mkt_aeo]         ← Voting Line / ChatGPT box
[atp_mkt_compliance]  ← TCPA / Ethics / Privacy cards
[atp_mkt_intake]      ← Get Started CTA + phone/email/samples
[atp_mkt_footer]      ← branded bottom strip
[atp_mkt_scripts]     ← GSAP + canvas chart + ticker (load once, last)
```

Each shortcode reads its content from:

1. `wp_options.atp_mkt_sc_<tag>` if an admin override is saved, OR
2. `templates/<file>` as the default

This means an editor (or AI via MCP) can rewrite any single section
without touching the rest, and revert to the default at any time.

You can also build alternate pages by composing a different subset
of shortcodes — e.g., a one-pitch landing page that uses only
`[atp_mkt_styles] [atp_mkt_hero] [atp_mkt_intake] [atp_mkt_footer]
[atp_mkt_scripts]`. Always include `[atp_mkt_styles]` first and
`[atp_mkt_scripts]` last on any page that uses these.

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
