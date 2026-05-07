# Master Plan — Diagrams

**Last updated:** 2026-05-05
**Companion to:** the master plan delivered in chat
**Companion to:** `packages/atp-plugin-core/ARCHITECTURE.md` (which has
intake-flow + new-site-deployment diagrams)

This file holds the architectural diagrams that explain how the
candidate-platform works at scale: many candidate sites, two editing
parties (ATP + Mirror Factory), an MCP server unifying the team's
view, release channels + election-day freezes, customization lanes,
schema migrations, and site lifecycle.

Each diagram is a slice. Read in order.

---

## Diagram 1 — System topology (where each piece lives)

The single most important thing to understand: **there is no
"one plugin containing all candidates."** Each candidate has their
own WordPress install. The plugin is the same on each. The MCP
server is the layer that lets the team operate across all of them
from one place.

```
                    Mirror Factory infrastructure
   ┌──────────────────────────────────────────────────────────┐
   │  GitHub: mirror-factory/ATP-Demo (this monorepo)         │
   │  ────────────────────────────────────────────────────    │
   │   packages/atp-plugin-core/   ← ONE plugin codebase      │
   │   sites/<slug>/               ← per-client config        │
   │   scripts/build-site.sh       ← per-client zip builds    │
   └────────┬─────────────────────────────────────────────────┘
            │ build + deploy on tag, or on demand via MCP
            ▼
   ┌──────────────────────────────────────────────────────────┐
   │  ATP MCP Server (Node/Python, hosted by Mirror Factory)  │
   │  ────────────────────────────────────────────────────    │
   │   Site registry (every live candidate WP)                │
   │   App-Password vault + auth scopes                       │
   │   Tools: list_sites, get_page, update_v3_json,           │
   │          update_shortcode, bulk_update,                  │
   │          preview_in_playground, trigger_build, …         │
   └─┬────────────────────────────┬───────────────────────────┘
     │ WP REST API (/atp/v1/) +   │ Same protocol, different
     │ Application Passwords      │ auth scope
     │                            │
     ▼                            ▼
 ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐
 │ Cand A │  │ Cand B │  │ Cand C │  │ Cand D │  │  …N    │
 │  WP    │  │  WP    │  │  WP    │  │  WP    │  │  WPs   │
 │        │  │        │  │        │  │        │  │        │
 │ core   │  │ core   │  │ core   │  │ core   │  │ core   │
 │ + A's  │  │ + B's  │  │ + C's  │  │ + D's  │  │ +…data │
 │ data   │  │ data   │  │ data   │  │ data   │  │        │
 └────────┘  └────────┘  └────────┘  └────────┘  └────────┘

         ▲                                          ▲
         │                                          │
   ┌─────┴───────┐                          ┌───────┴──────┐
   │ ATP staff   │                          │ MF engineer  │
   │ (Claude     │                          │ (Claude Code,│
   │  Code with  │                          │  Cursor,     │
   │  atp-staff  │                          │  Tauri…)     │
   │  scope)     │                          │  with        │
   │             │                          │  mf-admin    │
   │             │                          │  scope       │
   └─────────────┘                          └──────────────┘

      Both parties hit the SAME MCP server.
      Auth scope determines which tools they can call.
```

### What lives where

| Thing | Where | Notes |
|---|---|---|
| Plugin source code | GitHub monorepo | One canonical version |
| Build artifacts | `dist/<slug>/` (gitignored) | Per-client zips |
| Per-client config (colors, domain) | `sites/<slug>/site-config.json` | Edit + commit |
| V3 JSON (intake answers) | `_v3_json` post meta on each candidate's WP | Source of truth |
| Page overrides (custom HTML) | `sites/<slug>/page-overrides/` | Lane 3 customizations |
| Headshots / logos / photos | WordPress media library + Drive mirror | Both, by design |
| Site registry (which sites exist) | MCP server | Includes App Passwords |
| Drive folder for intake submissions | Google Drive (ATP intake host only) | One folder, dated subfolders |

---

## Diagram 2 — Edit lifecycle (a content change from request to live)

```
   Candidate emails ATP: "Update my About section paragraph 2"
                  │
                  ▼
   ┌────────────────────────────────────────────────┐
   │  ATP triages: severity = LIGHT (copy edit)?    │
   └─────────┬──────────────────────────┬───────────┘
             │ yes                      │ no (structural / new feature)
             ▼                          ▼
   ┌──────────────────────────┐   ┌─────────────────────────┐
   │  ATP staffer in Claude   │   │  ATP files request      │
   │  Code:                   │   │  with Mirror Factory    │
   │  "Update Sarah's about   │   └─────────────┬───────────┘
   │   p2 to: <new copy>"     │                 │
   └─────────┬────────────────┘                 │
             │                                  ▼
             │                      ┌──────────────────────┐
             │                      │  MF engineer in      │
             │                      │  Claude Code on a    │
             │                      │  branch              │
             │                      │  ─ codes the change  │
             │                      │  ─ runs PHP lint     │
             │                      │  ─ adds EDIT_LOG     │
             │                      │    entry             │
             │                      └────────┬─────────────┘
             │                               │
             ▼                               ▼
   ┌─────────────────────────────────────────────────────┐
   │  MCP: preview_in_playground(slug, pending_change)   │
   │  ──────────────────────────────────────────────     │
   │  Snapshots site's V3 JSON + overrides + the         │
   │  pending change into a fresh Playground blueprint.  │
   │  Returns a Playground URL.                          │
   └────────────────┬────────────────────────────────────┘
                    │
                    ▼
   ┌─────────────────────────────────────────────────────┐
   │  Reviewer opens the Playground URL.                 │
   │  Sees the change live in a sandbox WordPress.       │
   │  Clicks around the site exactly as voters would.    │
   │  ─ Looks good? → push live                          │
   │  ─ Looks broken? → revise, re-preview               │
   └────────────────┬────────────────────────────────────┘
                    │ approved
                    ▼
   ┌─────────────────────────────────────────────────────┐
   │  Lane decides write path:                           │
   │   • Lane 1 (content): MCP → WP REST → V3 JSON or    │
   │                        page content updated         │
   │   • Lane 2 (new core feature): merge to main, all   │
   │                        sites on `stable` channel    │
   │                        get it on next pull          │
   │   • Lane 3 (one-off): override committed to         │
   │                        sites/<slug>/page-overrides/ │
   │                        and that site's plugin       │
   │                        rebuild deploys              │
   └────────────────┬────────────────────────────────────┘
                    │
                    ▼
              Site is live.
              MCP records the change in audit log.
              EDIT_LOG.md updated for any code change.
```

### Why Playground for previews

- **No staging server cost.** Spin up on demand, dispose when done.
- **Realistic sandbox.** Real WordPress, real plugin, real V3 JSON.
- **Shareable.** Send the URL to ATP for sign-off without giving
  them server access.
- **Branchable.** Multiple in-flight changes can have separate
  Playground previews simultaneously.

---

## Diagram 3 — Release channels + the election-day freeze

When core ships a fix or feature, how does it reach 30 live sites
without breaking someone two weeks before their election? Three
channels with auto-freeze.

```
   Core release tagged on GitHub
       │
       ▼
   ┌─────────────────────────────────────────────────┐
   │  Release type?                                  │
   │   patch (3.2.1)    │  minor (3.3.0)  │  major   │
   └────────┬───────────┬──────────────────┬─────────┘
            │           │                  │ (3.x → 4.0)
            ▼           ▼                  ▼
        deploy to   deploy to          deploy to
        `stable`    `stable`           `beta` only
        within 24h  within 24h         (manual opt-in)


   PER-SITE CHANNEL ASSIGNMENT
   ───────────────────────────────────────────────────────────

   Day -∞ ──────────── Day -30 ──── Election Day ──── Day +90 ──→ archive
                       │                              │
   channel: stable     │  channel: frozen             │ channel: stable
                       │  (auto, no updates land)     │ (until archival)
                       │                              │
                       └──────────────────────────────┘
                          "blackout" window: anything
                          shipping after this point
                          must be a manual hotfix


   EXAMPLE: 30 LIVE SITES, A MINOR RELEASE SHIPS

   Sites in `stable` (and not in blackout):    auto-update next pull
   Sites in `frozen` (election in <30 days):   defer
   Sites in `beta`:                            already had it
   Sites that are archived (>1yr post):        skip


   HOTFIX OVERRIDE
   If a critical security fix needs to land in `frozen` sites,
   MCP can force-deploy a single tagged release to a specific
   site list. Bypass requires `mf-admin` scope and is logged.
```

### Why this design

- **`stable` default** = 80%+ of sites stay current with no manual work.
- **Auto-freeze 30d before election** = the candidate is never
  guinea-pigged at the moment that matters.
- **`beta` channel** = MF can battle-test changes on internal /
  long-time-out sites before promoting.
- **Force-deploy escape hatch** = security fixes still reach frozen
  sites without weakening the safety net.

---

## Diagram 4 — Customization decision tree (the three lanes)

```
   Someone asks for a change. Does it fit the V3 schema?
                  │
       ┌──────────┴──────────┐
       │                     │
       ▼ yes                 ▼ no
   ┌──────────┐    Will another candidate benefit
   │  LANE 1  │    from this in 12 months?
   │ Content  │              │
   │ edit     │     ┌────────┴────────┐
   │          │     │                 │
   │ ─ MCP    │     ▼ yes             ▼ no
   │   write  │ ┌─────────┐    ┌──────────────┐
   │ ─ no     │ │ LANE 2  │    │   LANE 3     │
   │   code   │ │ Core    │    │ One-off      │
   │ ─ minutes│ │ feature │    │ override     │
   └──────────┘ │         │    │              │
                │ ─ Add   │    │ ─ Lives in   │
                │   to    │    │   sites/<x>/ │
                │   schema│    │   page-      │
                │ ─ Gate  │    │   overrides/ │
                │   behind│    │ ─ Tagged in  │
                │   feat- │    │   manifest   │
                │   ure   │    │   .json with │
                │   flag  │    │   "asked by" │
                │ ─ All   │    │   "reason"   │
                │   sites │    │ ─ Counts     │
                │   on    │    │   against    │
                │   `stab │    │   the site's │
                │   le`   │    │   override   │
                │   get it│    │   limit (3)  │
                │ ─ Days  │    │ ─ Hours of   │
                │   of    │    │   work       │
                │   work  │    │ ─ Billed as  │
                │ ─ Free  │    │   custom     │
                │   for   │    │              │
                │   client│    │              │
                └─────────┘    └──────────────┘

         ~80%             ~15%              ~5%
        of asks          of asks          of asks


   MAINTENANCE LOOP
   ──────────────────────────────────────────────────────
   Every release, the override conflict detector scans:
     ─ For each site, for each override file:
         hash(override) ↔ hash(core_template_at_override_creation)
       Did core's version of this template change?
            │
       ┌────┴────┐
       │ yes     │ no → leave alone
       ▼
   Slack/email MF: "Site X's override of atp_cand_hero
   diverges from new core. Review needed."
            │
       ┌────┴────────┬──────────────┐
       ▼             ▼              ▼
   PROMOTE       REFRESH         DROP
   override →    rewrite         delete the
   core (Lane 2  override        override; site
   features for  against new     uses new core
   everyone)     core template
```

### Why the override limit

If a single site has more than 3 page-overrides, that's a signal
the candidate is not actually a Tier 1 fit and either: (a) needs to
move to Tier 2, or (b) needs the most-overridden component
genericized into a Lane 2 core feature. Without this signal, drift
is invisible.

---

## Diagram 5 — Site lifecycle (intake → launch → archive)

```
   T = -∞                  Intake submission received
        │                  ─ V3 JSON written to ATP intake host
        │                  ─ Headshot/logo/photos to Drive mirror
        │                  ─ Email to Mirror Factory
        ▼
   T-90 to T-30            Build + deploy
        │                  ─ scripts/new-site.sh <slug>
        │                  ─ AI generates page-json.json
        │                  ─ scripts/build-site.sh <slug>
        │                  ─ Deploy to fresh WP install
        │                  ─ MCP registers the site
        │                  channel: `stable`
        ▼
   T-30                    Election approaches → AUTO-FREEZE
        │                  channel: `stable` → `frozen`
        │                  Only hotfix deploys land
        ▼
   T = 0                   ELECTION DAY
        │
        ▼
   T+1 to T+90             Site stays live, full edits enabled
        │                  channel: `frozen` → `stable` (back)
        │                  Renewal touchpoint for ATP
        ▼
   T+91 to T+365           Archived, read-only, low-cost hosting
        │                  channel: `frozen` (locked)
        │                  V3 JSON + Drive folder retained 1y min
        ▼
   T+365+                  Candidate decides:
        │
        ├──► (a) Pay for permanent archive — site stays up,
        │       great for re-runs ("Sarah Chen 2028")
        │
        ├──► (b) Export as static site — handed over,
        │       MF/ATP no longer host
        │
        └──► (c) Full deletion — V3 JSON purged from active
                stores, retained in cold storage per
                campaign-finance retention rules
```

### Touchpoints for ATP's sales motion

The lifecycle has built-in moments where ATP can re-engage:

- **T-30 freeze**: "We've locked your site for election day. Need
  any last changes? Add them now."
- **T+90 unlock**: "Election's over — congrats / condolences. Want
  us to keep the site live for re-runs / advocacy work? $/month."
- **T+365 archive decision**: forced choice = renewal opportunity.

---

## What this means for "many candidates, one plugin"

Putting it all together:

- **One plugin codebase** in this monorepo. Every candidate's WP
  install runs the same code, just different config + content +
  optional overrides.
- **Per-site state** lives entirely in that site's WP DB + media
  library + (mirrored) Drive folder. The monorepo holds the
  recipe; the WP DB holds the live result.
- **Updates propagate** by channel. `stable` for most, `frozen`
  during election windows, `beta` for opt-in early adopters.
- **Customization is bucketed**. Most asks are Lane 1 content
  edits. Genuinely new features get promoted to core. Snowflake
  asks get isolated overrides with explicit tracking.
- **The MCP is the team's universal remote**. Both ATP and MF
  point Claude Code (or any MCP client) at the same server. Auth
  scope decides which tools each role can call.
- **Lifecycle is explicit**. Sites move through stable → frozen →
  stable → archived → choose-your-ending, with renewal
  touchpoints baked in.

This is the whole shape. Phase 2 of the build is the MCP server +
the plugin's `/atp/v1/` REST extensions. Everything else is already
in place.
