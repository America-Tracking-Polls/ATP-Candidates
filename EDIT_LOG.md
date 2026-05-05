# Edit Log

> **Convention:** Whenever edits are made to this repository — code,
> content, copy, assets, configuration, anything — **always add an
> entry to this edit log**, dated, with the affected files and a brief
> description. New entries go at the **top** (most recent first). This
> applies to humans, AI assistants, and automation alike.
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

## 2026-05-05 — Architecture documentation

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** _pending push_
**File:** `packages/atp-plugin-core/ARCHITECTURE.md` (new)

Authored an architecture & intake-flow doc for the plugin, placed
inside `packages/atp-plugin-core/` so it travels with every client
deploy.

### Done

- Diagram 1: repository / monorepo layout
- Diagram 2: intake submission lifecycle (form → `wp_ajax_atp_save` →
  post + meta + email + Drive)
- Diagram 3: from intake to live candidate site (`new-site.sh` → AI →
  `build-site.sh` → deploy)
- Two-roles section explaining how the same codebase serves the intake
  host and each candidate site
- Data-lineage diagram (intake → V3 JSON → page-json → dist → live WP)
- FAQ covering: shared install vs per-client, automation level,
  upload destinations, schema overrides, plugin updates

---

## 2026-05-05 — Landing-page revisions + Drive integration status

**Branch:** `claude/activate-drive-upload-P3yOj`
**Commits:** `9756de2`, `00bd00c`, `5b6cc40`, `5537ff1`

Dated snapshot of every requested edit from the 9 review slides
(2 batches: 5 slides + 4 slides = 45 individual requests), plus the
Google Drive upload work that was active in parallel.

### Google Drive upload integration

| # | Task | Status | Commit |
|---|------|--------|--------|
| D1 | Implement service-account JWT auth + token caching | ✅ Done | `9756de2` |
| D2 | Implement folder find-or-create | ✅ Done | `9756de2` |
| D3 | Implement multipart file upload | ✅ Done | `9756de2` |
| D4 | Wire `atp_drive_upload()` to use real Drive client | ✅ Done | `9756de2` |
| D5 | Add WP admin field for service-account JSON path | ✅ Done | `9756de2` |
| D6 | Add "Test Drive Connection" button | ✅ Done | `9756de2` |
| D7 | Mirror changes to legacy `atp-demo-plugin/` folder | ✅ Done | `9756de2` |
| D8 | Update `.gitignore` to block service-account JSON files | ✅ Done | `9756de2` |
| D9 | Bump plugin version + changelog entry | ✅ Done | `9756de2` |
| D10 | Write deployment / setup guide | ✅ Done | `00bd00c` (`docs/google-drive-setup.md`) |
| D11 | Place JSON key on WP server, configure plugin, test | ⏳ Awaiting user (server-side step) | — |

### Landing page — first slide batch (5 slides, items 1–22)

#### Slide 1 — BIO/SLOGAN + AEO + AmeriTrack section

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 1 | Remove the entire first paragraph ("Make sure you optimize your BIO and SLOGAN…") | ❌ Skipped | Content not in `ATP-Homepage-Mockup.html` — lives on the deployed site / different source |
| 2 | Make the next paragraph larger ("Traditional SEO is no longer enough…") | ❌ Skipped | Same — content not in this repo |
| 3 | "Test Drives page" link — leave as-is | ❌ N/A | Link doesn't exist in this file |
| 4 | "Contact Us" link → repoint to top-of-page survey link | ❌ Skipped | Link doesn't exist in this file |
| 5 | Remove the entire "About AmeriTrack Polls" section | ❌ Skipped | Section not in this file |

#### Slide 2 — Video + ATP red-box copy

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 6 | Replace the video with a new one | ⚠️ Placeholder | `<video src="">` element added at line ~325; `src` empty pending MP4 |
| 7 | Add "Schedule your free consult" above the survey | ❌ Misplaced | I retitled the *intake* section heading; you wanted it above the *SMS-survey* simulation |
| 8 | Make the survey view taller | ❌ Missed | iPhone mockup still at original height |
| 9 | Remove the name "America Tracking Polls" from headline | ✅ Done | `5b6cc40` |
| 10 | New headline: "5 coordinated multi-media channels help reach out to 95% of registered voters." | ✅ Done | `5b6cc40` |
| 11 | New body opener: "America Tracking Polls delivers the most powerful integrated solution…" | ✅ Done | `5b6cc40` |
| 12 | Continue with existing "We combine Answer Engine Optimized data…" sentence | ✅ Done | `5b6cc40` |

#### Slide 3 — Required components list

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 13 | Hero MP4 with application below it | ⚠️ Structure done | Markup + CSS in place; MP4 source empty |
| 14 | Typeform Campaign Application | ⚠️ Structure done | Placeholder div in place; embed not pasted |
| 15 | Quick-view Benchmark Survey **combined with** social-media examples | ❌ Skipped | Social-media examples block not located |
| 16 | Sample Typeform Benchmark Survey | ❌ Skipped | Distinct embed/URL needed |

#### Slide 4 — Asset

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 17 | "WIN BEFORE ELECTION DAY" graphic | ❌ Skipped | No placement specified, no image file in repo |

#### Slide 5 — Scrolling header strip

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 18 | Remove standalone "AEO" item | ✅ Done | `5b6cc40` |
| 19 | Keep "5 Strategies — Proven High Engagement Coordinated Voter Outreach" | ✅ Done | `5b6cc40` |
| 20 | Keep "Compliance" | ✅ Done | `5b6cc40` |
| 21 | Keep/add "AI Optimized — Your campaign dominates AI search results (AEO)" | ✅ Done | `5b6cc40` |
| 22 | Keep/add "Data Insights — Persistent real-time reporting, survey results crosstabs" | ✅ Done | `5b6cc40` |

### Landing page — second slide batch (4 slides, items 23–45)

#### Slide 6 — "The rest of the landing page"

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 23 | Add Hero Video | ⚠️ Placeholder | `<video>` markup added; src empty |
| 24 | "POLLING POWERED" → "MULTI-MEDIA CAMPAIGN MARKETING" | ✅ Done | `5b6cc40` |
| 25 | Remove contact form; add the Typeform | ⚠️ Partial | Form removed ✓; Typeform = placeholder div only |
| 26 | Hero text: "WIN YOUR ELECTION BEFORE ELECTION DAY." | ✅ Done | `5b6cc40` |
| 27 | New hero body: "Every channel we deliver — five high-response MMS surveys…" | ✅ Done | `5b6cc40` |
| 28 | Decide if 2 hero CTAs are still needed once Typeform is below the video | ❌ Pending | Currently both CTAs left in place, no decision made |

#### Slide 7 — What You Learn / How It Powers

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 29 | Lead-in: "…giving you instant, real-time toplines and actionable intelligence." | ✅ Done | `5b6cc40` |
| 30 | "What You Learn" — 5-bullet list (exact issues, winning/losing, undecided, vulnerabilities, real intelligence) | ✅ Done | `5b6cc40` |
| 31 | "How It Powers Your Campaign" — 4 paragraphs | ✅ Done | `5b6cc40` |
| 32 | Bold close: "This is how campaigns stop reacting — and start controlling the outcome." | ✅ Done | `5b6cc40` |

#### Slide 8 — Strategic Path / Converting Data Into Action

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 33 | "Your Strategic Path" — no changes | ✅ Left untouched | `5b6cc40` |
| 34 | "Converting Data Into Action" — current text fine | ✅ Left untouched | `5b6cc40` |
| 35 | MAIL → QR-coded Print | ✅ Done | `5b6cc40` (also swapped the icon to a QR-code SVG) |
| 36 | SMS → MMS | ✅ Done | `5b6cc40` |
| 37 | Eliminate small text (unreadable) | ✅ Done | `5b6cc40` (removed `p-branch-desc` divs) |
| 38 | "The Voting Line…" — fine as is | ✅ Left untouched | — |
| 39 | "The ChatGPT Box" — amazing, text perfect | ✅ Left untouched | — |
| 40 | "Compliance" + text boxes — perfect | ✅ Left untouched | — |

#### Slide 9 — Footer / contact / nav

| # | Request | Status | Notes |
|---|---------|--------|-------|
| 41 | Second CTA at the bottom | ✅ Done | `5b6cc40` |
| 42 | Add phone (202) 815-4637 | ✅ Done | `5b6cc40` (intake section + footer) |
| 43 | Add email info@americatrackingpolls.com | ✅ Done | `5b6cc40` |
| 44 | Add "view more survey samples @ ameritrackpolls.com" link | ✅ Done | `5b6cc40` |
| 45 | Add existing compliance pages to navigation | ✅ Done | `5b6cc40` (Compliance / AI Ethics / Privacy nav links + matching IDs on trust cards) |

### Tally

| Status | Count |
|---|---|
| ✅ Done | 30 |
| ⚠️ Placeholder / partial (waiting on assets) | 4 (items 6, 13, 14, 23/25) |
| ❌ Missed or done incorrectly (fixable now) | 3 (items 7, 8, 28) |
| ❌ Skipped — needs input | 8 (items 1, 2, 3, 4, 5, 15, 16, 17) |
| ⏳ Awaiting user action (server-side) | 1 (D11) |

### Files touched in this update

```
ATP-Homepage-Mockup.html
packages/atp-plugin-core/atp-demo-plugin.php
packages/atp-plugin-core/CHANGELOG.md
packages/atp-plugin-core/includes/drive-client.php  (new)
packages/atp-plugin-core/includes/file-upload.php
packages/atp-plugin-core/includes/whitelabel.php
atp-demo-plugin/includes/drive-client.php           (new)
atp-demo-plugin/includes/file-upload.php
atp-demo-plugin/includes/whitelabel.php
docs/google-drive-setup.md                          (new)
docs/landing-page-status-2026-05-04.md              (new — superseded by this log)
.gitignore
```

### What's next

- (No input needed) Fix items 7, 8, and 28 — the three "missed or done wrong" items.
- (Awaiting user) Provide MP4 file for item 6/23, Typeform embed for 14/25, source location for slide-1 BIO/SLOGAN content (items 1–5), placement decision for "WIN BEFORE ELECTION DAY" graphic (17), and clarification on the Benchmark Survey + social-media examples block (15) and standalone Sample Typeform Benchmark Survey (16).
- (Awaiting user) Deploy Drive credentials on the WP server (D11) and run **Test Drive Connection** in WP admin.
