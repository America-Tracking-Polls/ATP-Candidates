# Override system — how the ATP plugin separates content from template

**Last reviewed:** 2026-05-05
**Plugin version:** 3.5.0
**Audience:** Mirror Factory engineers + ATP staff who edit live sites

This document explains how the ATP Campaign Site plugin keeps **data
(JSON)** separate from **presentation (HTML/CSS/JS)** while still letting
each WP install override either or both per-shortcode — with a toggle
to revert to core defaults at any time, and a preview parameter to
compare versions without committing.

If you only have 60 seconds, jump to [The four override states](#the-four-override-states).

---

## Mental model

```
   ┌─────────────────────────────────────────────────────────────┐
   │                                                             │
   │   DATA (V3 JSON)              TEMPLATE (HTML/CSS/JS)        │
   │   ─────────────────           ─────────────────────────     │
   │   - display_name              - <section class="hero">      │
   │   - tagline                       <h1>{{display_name}}</h1>│
   │   - color_primary                 ...                       │
   │   - issue_positions           - card grid                   │
   │   - links                     - color variables             │
   │   - etc.                      - JS animations               │
   │                                                             │
   │              ──── + ──── = final HTML page                  │
   │                                                             │
   └─────────────────────────────────────────────────────────────┘
```

The data is the single source of truth. The template is a layout that
pulls from the data via `{{token}}` placeholders. Either side can be
overridden independently per WP install.

---

## The four override states

For any shortcode (let's pretend we're talking about `[atp_cand_hero]`):

| State | Template comes from | Data comes from | Use case |
|---|---|---|---|
| **Default** | Plugin's registry default | V3 JSON (the candidate's intake) | Brand-new install. Most sites stay here. |
| **Template-only override** | Per-site `wp_options.atp_sc_atp_cand_hero` | V3 JSON | Custom layout, default content (e.g., candidate wanted a different hero design but the bio/name comes from intake) |
| **Data-only override** | Plugin's registry default | V3 JSON ← per-shortcode patch | Default layout, custom content (e.g., site C wants the headline phrased differently for one shortcode only) |
| **Full override** | Per-site `atp_sc_atp_cand_hero` | V3 JSON ← per-shortcode patch | Custom layout AND custom content for this one section |

Plus a fifth state: **disabled override**. The override is stored, but
a toggle says "use core default." Useful for testing whether new core
fixes a problem your override was working around.

---

## Where everything is stored

### Per shortcode tag (e.g. `atp_cand_hero`)

| Storage key | Purpose | Type |
|---|---|---|
| `wp_options.atp_sc_atp_cand_hero` | Template HTML override | string (HTML) |
| `wp_options.atp_sc_atp_cand_hero_data` | Data patch for this shortcode | string (JSON) |
| `wp_options.atp_sc_atp_cand_hero_disabled` | Toggle: when truthy, ignore the override and render core default | bool |

For marketing shortcodes the prefix is `atp_mkt_sc_*` instead of
`atp_sc_*`, but the pattern is identical.

### Site-wide V3 JSON

Stored on the `atp_candidate` post type as `_v3_json` post meta plus
flat field meta. Read by `atp_cand_get_data()`. This is the source of
truth for the candidate's content — name, bio, issues, colors, photos,
etc. Edit this and every shortcode that references those tokens
updates automatically.

---

## How the renderer decides at runtime

```
   [atp_cand_hero]
        │
        ▼
   atp_demo_render_shortcode($atts, $content, $tag)
        │
        ├─► atp_demo_resolve_template($tag, $atts['source'])
        │       │
        │       ├─► source="core"     → registry default
        │       ├─► source="override" → atp_sc_<tag> (or default if empty)
        │       └─► (no source attr) → if disabled OR empty override
        │                                  → registry default
        │                              else
        │                                  → atp_sc_<tag>
        │
        ▼
   Got the HTML template (with {{tokens}} unsubstituted)
        │
        ├─► atp_demo_get_data_patch($tag) → loads atp_sc_<tag>_data
        │
        ▼
   atp_cand_replace_tokens($html, $patch)
        │
        ├─► Pull V3 JSON via atp_cand_get_data()
        ├─► Merge: V3 JSON ← $patch (patch wins per key)
        ├─► Substitute {{token}} with merged data
        └─► Strip any unmatched {{tokens}}
        │
        ▼
   Final HTML rendered to the page
```

---

## Preview mode (compare versions on a test page)

Every shortcode supports a `source` attribute. Drop these on a test
page to compare without changing live behavior:

```
[atp_cand_hero]                    ← whichever is active per the toggle (live default)
[atp_cand_hero source="core"]      ← force registry default (preview the upcoming version)
[atp_cand_hero source="override"]  ← force the stored override (preview your customization)
```

Recommended workflow when something looks broken:

1. Create a temporary page **"Hero comparison"**
2. Drop both `[atp_cand_hero source="core"]` and `[atp_cand_hero source="override"]` on it
3. Refresh — see them side-by-side
4. Decide: keep the override, update it, or toggle it off

---

## How to use it — by use case

### "I just want to tweak the hero copy on Sarah's site"

The hero template uses `{{display_name}}`, `{{tagline}}`, etc. Update
the V3 JSON for Sarah and every shortcode that references those tokens
updates automatically. No override needed.

WP Admin → ATP Candidates → Sarah Chen → edit fields → save.

### "Sarah wants a completely different hero layout"

Override the template for that one shortcode on her site:

WP Admin → ATP Demo → find the `[atp_cand_hero]` card → edit the HTML
in the textarea → keep the `{{display_name}}` etc tokens so JSON
content still flows in → Save.

A "Override active" badge appears on the card. Other sites are
unaffected.

### "Sarah wants different copy in just the hero, but keep the rest of the design"

Use a **data patch** instead of a template override. In the same card,
expand the **"Data patch"** section and paste:

```json
{
  "display_name": "Sarah J. Chen",
  "tagline": "Working for District 5"
}
```

The hero template renders using these values instead of the V3 JSON
for those keys. Other shortcodes still pull from V3 JSON unchanged.

### "We shipped a new hero with a built-in form. Sarah's site is on an override and missing it. Toggle the override off temporarily to see the new core."

WP Admin → ATP Demo → Sarah's `[atp_cand_hero]` card → check
**"Disable override (use core default)"** → Save. Live page now
renders the new core hero. Override stays in storage. To go back, just
uncheck.

### "Site C overrode three shortcodes. We don't remember which. Find them."

Look at the Edit Shortcodes screen on that site. Cards with
**"Override active"** or **"Override stored, disabled"** or
**"Data patch"** badges have customizations. The rest are on core.

---

## Marketing site uses the same system

Marketing shortcodes (`[atp_mkt_*]`) follow the identical pattern:

| Storage | Marketing equivalent |
|---|---|
| Template override | `atp_mkt_sc_<tag>` |
| Disabled toggle | `atp_mkt_sc_<tag>_disabled` |
| Preview attribute | `[atp_mkt_hero source="core"]` etc |
| Admin UI | WP Admin → ATP Marketing → Edit Shortcodes |

Marketing templates don't use `{{tokens}}` yet (content is currently
inline in `templates/marketing/*.html`). Adding tokens to a marketing
template will Just Work as soon as the templates use `{{tokens}}` —
the same data-patch flow is wired but a no-op until template tokens
exist.

---

## What about the AI-generated `page-json.json`?

When a candidate site is provisioned, the build pipeline runs the V3
JSON through `PROMPT-TEMPLATE.md` and the model returns a
`page-json.json` containing rendered HTML for each shortcode. The
build script imports those into `wp_options.atp_sc_<tag>` —
which means **every candidate site starts as a fresh install with
template-only overrides for every shortcode** (the AI-rendered HTML).

This is intentional:

- The AI-rendered HTML matches the candidate's brand exactly out of
  the gate.
- If you later want to update copy, two paths:
  - **Quick**: edit the V3 JSON, then **toggle off** the override on
    that shortcode. Now the shortcode renders the registry default
    populated from your updated V3 JSON.
  - **Visual**: edit the override HTML directly in WP admin. JSON
    stays the same; the override carries the new copy.

Both work. Pick based on the kind of change.

---

## Conflict detection (future)

When core ships an updated default for a shortcode, sites with an
override still render the override and miss the new core feature.
This is by design — overrides are explicit choices.

Planned (not yet shipped): a per-release conflict detector that
compares the override creation date against the core file's modified
date and flags drift in WP admin: *"Core updated `[atp_cand_hero]` —
your customization may be missing a new feature. Compare with
`source='core'` and decide."*

Until then, the `source="core"` preview attribute is your manual
diff tool.

---

## Storage cheat sheet

| What | Where | Persistence |
|---|---|---|
| Plugin default templates | `packages/atp-plugin-core/includes/registry.php` | Code, ships with plugin |
| Marketing default templates | `packages/atp-plugin-core/templates/marketing/*.html` | Code, ships with plugin |
| V3 JSON for a candidate | `wp_post_meta` of the `atp_candidate` post (key `_v3_json`) | DB on the intake host |
| Per-site template override | `wp_options.atp_sc_<tag>` | DB on each candidate's WP |
| Per-site data patch | `wp_options.atp_sc_<tag>_data` | DB on each candidate's WP |
| Per-site disable toggle | `wp_options.atp_sc_<tag>_disabled` | DB on each candidate's WP |
| Plugin source code | This repo (`America-Tracking-Polls/ATP-Candidates`) | git, deployed via updater.php |

---

## TL;DR

- **JSON = data, template = presentation.** Edit JSON to change content; edit template to change layout.
- **Per-shortcode override** for either side, stored in `wp_options`.
- **Toggle** to disable the override without deleting it.
- **`source="core"` / `source="override"`** attributes for preview.
- **Same system** on marketing shortcodes (`atp_mkt_*`).
- **Brand guide** is a tokenized shortcode (`[atp_cand_brand_guide]`) — add it to any candidate site via the importer.
