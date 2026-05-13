# Candidate Website Creation — End-to-end flow

**Audience:** ATP staff and Mirror Factory engineers walking a new
candidate from intake submission to live campaign site.

**Plugin version this doc reflects:** 3.6.0

This document walks the **entire path** from "ATP sits down with a
prospective candidate" to "the candidate's website is live on its own
domain." It calls out which party does what, where each artifact
lives, and the exact buttons / commands / prompts to use.

---

## At a glance — who does what

```
   PHASE 1  Intake                  ATP staff + candidate
            (form on ATP's WP site)
                    │
                    ▼
   PHASE 2  Auto-routing            (plugin does this automatically)
            (email + bundle + Drive)
                    │
                    ▼
   PHASE 3  MF receives + provisions Mirror Factory
            (SiteGround → install plugin)
                    │
                    ▼
   PHASE 4  AI-assisted build       Mirror Factory + Claude/ChatGPT
            (via Vibe AI MCP)
                    │
                    ▼
   PHASE 5  Review + edits          ATP + candidate + MF
                    │
                    ▼
   PHASE 6  Domain + launch         Mirror Factory + ATP
                    │
                    ▼
   PHASE 7  Ongoing edits           ATP (or MF) via Claude + Vibe AI
```

Each phase below has the **trigger**, **steps**, and **handoff**.

---

## PHASE 1 — Intake (ATP staff, on ATP's WordPress)

**Trigger:** ATP signs a new candidate, or a prospective candidate
asks to start the onboarding.

**Where:** `americatrackingpolls.com/candidate-intake-form/` (the
`[atp_intake]` shortcode lives there).

**Steps**

1. ATP staff sits with the candidate (in person or screen-share) and
   walks through the 16-step intake form.
2. The form covers:
   - Identity & race (name, office, district, state, party, election date)
   - Bio & messaging (full bio, why running, taglines, key messages)
   - Platform & issues (top 5 issue categories + position statements)
   - Background & credentials (profession, education, military)
   - **Visual branding** (headshot upload, logo upload, additional photos, brand colors)
   - Social media handles
   - Video links
   - Survey/poll preferences
   - Legal & compliance (committee, paid-for-by, privacy contacts, SMS opt-in language)
   - Fundraising (donation platform, embed code, donate button label)
   - Domain setup (preferred + primary, registrar, hosting)
   - Approval & timeline
   - Tier-2 services interest
3. Headshot / logo / additional photos upload through the form. They
   land in **WP media library** under `/wp-content/uploads/atp-intake/<candidate-slug>/`
   AND get **mirrored to the connected Google Drive folder** under
   `Intake_Submissions_Live/<YYYY-MM-DD>_<Candidate-Name>_<Office-Slug>/`.
4. Candidate (or ATP filler) hits **Submit**.

**Handoff:** Form data is now persisted as an `atp_candidate` post on
ATP's WP install with a `_v3_json` post meta containing the structured
JSON. Files are in WP media + Drive subfolder.

---

## PHASE 2 — Auto-routing (the plugin handles this)

**Trigger:** Form submission.

No human does anything here. The plugin:

1. Creates the `atp_candidate` post (full data as post meta + V3 JSON).
2. Uploads attached files to WP media library + mirrors to the picked
   Drive folder (auto-creating the dated subfolder).
3. Sends a notification email to every address in
   `atp_settings.notify_emails`. Defaults: `alfonso@mirrorfactory.com`,
   `gary@americatrackingpolls.com`, `dan@americatrackingpolls.com`.
4. The email includes:
   - Candidate summary (name, office, party, etc.)
   - **Download Bundle (zip)** button — generates a fresh
     `<slug>-intake-bundle.zip` containing:
     - `REFERENCE.md` — quick links + uploaded asset URLs + 12-step engineer guide
     - `<slug>-v3.json` — V3 JSON ready to paste anywhere
     - `<slug>-PROMPT.md` — the AI generation prompt with V3 JSON inlined; paste into Claude / ChatGPT
   - **View Submission** button (admin view)
   - **Open Drive Folder** button

**Handoff:** Mirror Factory team has an email in their inbox.

---

## PHASE 3 — Mirror Factory provisions the WP container

**Trigger:** The intake email lands.

**Where:** Mirror Factory engineer's local machine + SiteGround (or
chosen WP host).

**Steps**

1. **Engineer reviews the email.** Confirm scope: is this a real
   submission, what's the timeline, any obvious blockers.
2. **Download the Intake Bundle** from the email. Unzip locally.
3. **Open `REFERENCE.md`.** It contains every link the engineer needs:
   - Direct URL to the submission in WP admin
   - Filtered link to the WP media library for this candidate
   - Direct URL to the Drive subfolder
   - List of every uploaded asset URL (headshot, logo, photos)
4. **Pull assets locally** (or just leave them in Drive — they'll be
   re-imported in Phase 4).
5. **Run AI generation:**
   - Open Claude (Pro/Team/Enterprise) or ChatGPT (Plus or Team)
   - Paste the full contents of `<slug>-PROMPT.md` into a new chat
   - The model returns a JSON object — **the Page JSON**
   - Save it as `sites/<slug>/page-json.json` in the monorepo (or
     wherever the engineer keeps deploys)
   - Review the AI output for factual accuracy, tone, and any
     hallucinated claims. Edit if needed.
6. **Scaffold + build** from the monorepo:
   ```sh
   ./scripts/new-site.sh <slug> "Candidate Name" "Office"
   cp ~/Downloads/<slug>-v3.json sites/<slug>/intake-v3.json
   # save AI output to sites/<slug>/page-json.json
   ./scripts/build-site.sh <slug>
   # produces dist/<slug>/atp-campaign-site/  — a zip-ready WP plugin
   ```
7. **Provision a fresh WordPress install** on SiteGround:
   - Create a new site on the desired hostname (use a temp staging URL
     if the real domain isn't ready yet)
   - SSL on, PHP 8.2+, default theme is fine
   - Note the WP admin username + password
8. **Install the ATP plugin:**
   - Zip `packages/atp-plugin-core/` (or use `dist/<slug>/atp-campaign-site/`)
   - WP Admin → Plugins → Add New → Upload Plugin → upload zip
   - Activate. WP will auto-prompt to install **Vibe AI** (declared
     as `Requires Plugins`). Approve.

**Handoff:** A fresh WP install with the ATP plugin + Vibe AI both
active. The candidate's data isn't there yet.

---

## PHASE 4 — AI-assisted build (via Vibe AI MCP)

**Trigger:** Plugin is installed on the new WP. Engineer is ready to
populate the site.

**Where:** Engineer's Claude Code (or Claude.ai web, or ChatGPT —
whichever speaks MCP) with the Vibe AI connector pointed at the new
WP install.

### Step 4a — Wire Vibe AI to the new site

1. In WP Admin on the new site → **Plugins → Vibe AI → settings**
2. Follow Vibe AI's setup (it'll prompt to log in / create an account
   at wpvibe.ai)
3. Once Vibe AI gives you an MCP URL for this site, **add a custom
   connector in Claude.ai / Claude Code / your AI client**:
   - URL: the MCP endpoint Vibe AI shows you
   - Auth: OAuth (auto-managed) or Application Password (per Vibe AI's instructions)
4. Confirm the connector is green / connected.

### Step 4b — Install the `atp-site-edit` skill (one-time per engineer)

Drop the skill folder into your `~/.claude/skills/` location:

```sh
cp -r .claude/skills/atp-site-edit ~/.claude/skills/
```

After this, when Claude Code starts a session involving an ATP site,
the skill auto-activates and Claude knows how to behave (see
[`.claude/skills/atp-site-edit/SKILL.md`](../.claude/skills/atp-site-edit/SKILL.md)
for the full instructions Claude follows).

### Step 4c — Give Claude the initial task

Open a Claude chat with the new site's connector enabled. Use this
**copy-pasteable prompt** (replace the slug and paste your page-json):

> Set up Sarah Chen's candidate site. Follow the ATP site edit
> skill.
>
> 1. Load the site context (`GET /wp-json/atp/v1/site-context`) and
> confirm we're on a fresh install.
>
> 2. Delete the default WP pages (`Sample Page`, `Privacy Policy` if
> auto-created, any others). Don't touch anything created by the ATP
> plugin.
>
> 3. Import the standard candidate page set via WP Admin → ATP Demo
> → Import Pages: Home, Issues, Donate, Contact, About, Privacy,
> Cookie/TCPA, Sign Up, Brand Guide. Confirm each created
> successfully.
>
> 4. Import the candidate's V3 JSON. Here it is: <PASTE V3 JSON>.
> Update the `_v3_json` post meta on the `atp_candidate` post (create
> the post if it doesn't exist).
>
> 5. Apply the AI-generated Page JSON. Here it is: <PASTE PAGE JSON>.
> For each key in the page-json object, set the matching
> `wp_options.atp_sc_<key>` value to the HTML string.
>
> 6. Upload the candidate's media files to the WP media library from
> these Drive URLs: <PASTE ASSET URL LIST FROM REFERENCE.md>. Replace
> any references in the V3 JSON's `visual_branding.headshot_link` /
> `logo_link` / `additional_photos` with the new WP media URLs.
>
> 7. Verify each of the 9 pages renders. Report status of each.
>
> 8. Report which storage keys you wrote to so I can audit.

Claude will work through this end-to-end, asking for confirmation on
anything ambiguous.

**Handoff:** The candidate's site is built but on a staging URL.

---

## PHASE 5 — Review + edits

**Trigger:** Site is built; ready for review.

**Where:** Mirror Factory + ATP + the candidate.

**Steps**

1. **MF reviews** the staging site internally. Catch anything broken
   before the candidate sees it. Fix via Claude + the override system
   (see [`OVERRIDE-SYSTEM.md`](../packages/atp-plugin-core/OVERRIDE-SYSTEM.md)).
2. **ATP previews** the staging site. Walks through with the
   candidate.
3. **Candidate sends consolidated feedback** (one doc, batched
   changes — don't send a series of one-off requests).
4. **MF or ATP applies edits** via Claude:
   - "Change Sarah's tagline to 'X'" → V3 JSON update (one source of truth)
   - "Move the hero photo to the right" → template override
   - "Use a different headline just on the homepage" → data patch
   - All described in OVERRIDE-SYSTEM.md
5. Loop until candidate signs off.

**Handoff:** Approved, ready to launch.

---

## PHASE 6 — Domain + launch

**Trigger:** Candidate approves the staging site.

**Steps**

1. **Configure the production domain** at the registrar (point A /
   CNAME records at SiteGround).
2. **Add the domain** in SiteGround's site tools.
3. **Provision SSL** (Let's Encrypt via SiteGround, automatic).
4. **Set WordPress home + site URL** to the production domain.
5. **Whitelist the new domain in Google OAuth** if Drive integration
   is on (rare for candidate sites — usually Drive is only on the ATP
   intake host).
6. **Smoke test** every page on production. Verify the signup form
   submits (test entry → check email arrives → confirm `atp_subscriber`
   CPT records the entry → trash the test entry).
7. **Hand admin access** to ATP staff. ATP holds the keys; MF retains
   developer-level access for ongoing maintenance.

**Handoff:** Live site. Candidate is in business.

---

## PHASE 7 — Ongoing edits (the steady state)

**Trigger:** Any future content / layout change requested by the
candidate or ATP.

**Who:** ATP staff for routine edits, MF for structural changes.

**How:** Same Vibe AI + Claude flow as Phase 4. ATP staff opens a
Claude chat with the Vibe AI connector to the candidate's site, the
`atp-site-edit` skill kicks in, and Claude handles the request
correctly.

Example prompts ATP staff can use directly:

- *"Update Sarah's tagline to 'Working for District 5 families.'"*
  → Claude updates V3 JSON. Every section that uses the tagline updates.

- *"Add a new policy position on housing to Sarah's Issues page."*
  → Claude updates V3 JSON's `platform_issues.positions` field. The
  Issues page re-renders with the new card.

- *"The candidate wants their hero photo on the left side instead of
  right."*
  → Claude saves a template override on `[atp_cand_hero]` with the
  layout flipped. JSON-driven content still flows through.

- *"Disable Sarah's hero override; let's see what the new core hero
  looks like."*
  → Claude sets `atp_sc_atp_cand_hero_disabled = 1`. Override stays
  stored; site renders core default. Re-enable any time.

ATP staff never needs to touch FTP, the WordPress page editor, or the
plugin code directly.

---

## Things to know about Vibe AI specifically

- Vibe AI is a **hosted** MCP server at `mcp.wpvibe.ai`. It bridges
  AI clients (Claude / ChatGPT / Cursor / etc.) to a WordPress
  install via the REST API. It is not running on your WP server.
- **Trust model:** Vibe AI sees every operation your AI does on the
  site. Use a dedicated `atp-mcp-bot` user (per AGENTS.md
  recommendation) instead of an admin's account, so the blast radius
  is bounded.
- **Per-site connector:** each WP install needs its own Vibe AI
  setup. The candidate-platform plugin declares Vibe AI as a
  `Requires Plugins` dependency so it's auto-installed when our
  plugin activates — but the **Vibe AI side of the bridge needs a
  per-site authentication step.**
- **Capabilities boundary:** Vibe AI exposes generic WP CRUD. ATP-
  specific operations (V3 JSON, override management, intake bundles)
  are exposed via our plugin's REST API + the `atp-site-edit` skill
  tells Claude to use them.

---

## TL;DR

```
   1. ATP fills intake on their site
   2. Plugin emails MF + drops zip bundle on Drive
   3. MF spins up WP on SiteGround + installs the plugin
   4. MF connects Claude to the new site via Vibe AI
   5. MF pastes the page-json + tells Claude to wire everything up
   6. Review, iterate, launch
   7. ATP edits any time via Claude
```

Each step has a built-in safety: the plugin is one codebase, the
override system means edits are reversible, the AI follows a skill
that knows the rules, and every change leaves an audit trail in
`wp_options` so an engineer can always see what changed.

See:
- `packages/atp-plugin-core/OVERRIDE-SYSTEM.md` — how overrides work
- `packages/atp-plugin-core/ARCHITECTURE.md` — system architecture
- `MASTER-PLAN.md` — five architecture diagrams
- `AGENTS.md` — operating rules
- `.claude/skills/atp-site-edit/SKILL.md` — Claude's behavior on ATP sites
