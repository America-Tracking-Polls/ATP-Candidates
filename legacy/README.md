# `legacy/`

Files that are kept for reference and git history but **are no longer
loaded or executed by anything**. None of these are active code paths.

## Why this folder exists

Before the 2026-05-05 plugin consolidation, the intake form lived as a
standalone WordPress plugin at the repo root
(`atp-candidate-intake.php`). After consolidation, the intake form
moved into the canonical plugin at
`packages/atp-plugin-core/includes/intake/atp-candidate-intake.php`
and the root-level copy was kept around as a reference.

That root-level copy turned out to be a footgun: when anyone
downloaded the GitHub repo ZIP and uploaded it through wp-admin's
"Add New Plugin → Upload" form, WordPress detected the root-level
PHP file's `Plugin Name:` header and installed *that* as the plugin —
which gave them an intake-only install with no White Label, no
shortcodes, no Drive OAuth, etc. (See EDIT_LOG entry for 2026-05-13
for the full story.)

To stop that from happening, the file was moved here and its
`Plugin Name:` header was stripped. WordPress can no longer detect
it as a plugin.

## Current canonical paths

| Old path | Current path |
|---|---|
| `atp-candidate-intake.php` (repo root) | `packages/atp-plugin-core/includes/intake/atp-candidate-intake.php` |

## How to install the plugin correctly

Don't upload the repo ZIP. Use the build script:

```
./scripts/build-plugin-zip.sh
```

That produces `atp-plugin-core-<version>.zip` whose root folder is
`atp-plugin-core/` — the path WordPress expects.

Then in wp-admin → Plugins → Add New → Upload, pick that ZIP.

See `HANDOFF.md` for the full install + Drive setup walkthrough.
