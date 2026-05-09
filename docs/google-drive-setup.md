# Google Drive Mirror — Setup Guide (OAuth flow)

The intake plugin can mirror every uploaded file (headshot, logo,
additional photos) into a Google Drive folder you choose, organized
into one subfolder per submission. **Mirroring is on top of the
WordPress media library**, not instead of it: every file always lands
in WP media first, and Drive is a secondary destination.

This guide walks you through:

1. Creating an OAuth 2.0 client in Google Cloud
2. Connecting WordPress to your Google account
3. Picking a destination folder
4. Testing the connection

> Stored in the WP database (admin-only): the OAuth Client ID, Client
> Secret, refresh token, connected account email, and picked folder
> ID/name. No JSON key files, no shell access, no Drive folder
> sharing.

---

## One-time Google Cloud setup

Do this once per environment (typically once for ATP's intake host).

### 1. Create or pick a Google Cloud project

1. Go to <https://console.cloud.google.com/>.
2. Project picker (top bar) → **New Project** (e.g. `atp-intake-drive`)
   or select an existing one.

### 2. Enable the Drive API

1. Project dashboard → **APIs & Services → Library**.
2. Search "Google Drive API" → **Enable**.

### 3. Configure the OAuth consent screen

1. **APIs & Services → OAuth consent screen**.
2. User type: **External** (unless this is a Google Workspace project,
   in which case **Internal** is fine).
3. App name: e.g. "ATP Intake Drive Mirror".
4. User support email: yours.
5. Developer contact: yours.
6. Save.
7. On the **Scopes** step you don't need to add scopes here — they're
   requested at connect time.
8. On the **Test users** step (if External): add the Google account
   that will be connecting (the account that owns the destination
   folder). External apps in test mode only allow listed users to
   connect; this is fine for an internal tool.

### 4. Create the OAuth 2.0 Client ID

1. **APIs & Services → Credentials → Create Credentials → OAuth
   client ID**.
2. Application type: **Web application**.
3. Name: e.g. "ATP WordPress Plugin".
4. Authorized JavaScript origins: not required.
5. **Authorized redirect URIs:** add the exact URL shown in your
   plugin's settings page. It looks like:
   ```
   https://YOUR-WP-DOMAIN/wp-admin/admin.php?page=atp-whitelabel&atp_drive_oauth=callback
   ```
   You can grab this from **WP Admin → ATP → White Label Settings →
   File Upload Storage → Authorized redirect URI** (the URL is shown
   in a code box right under the Client Secret field).
6. Create. Google shows you the **Client ID** and **Client Secret** —
   copy both. Treat the secret like a password.

> If your WP site has a staging URL and a production URL, add both
> redirect URIs to the same OAuth client.

---

## Connect WordPress to Drive

1. **WP Admin → ATP → White Label Settings**, scroll to the **File
   Upload Storage** section.
2. **Drive mirroring** dropdown: select **WordPress + Google Drive**.
3. Paste the **OAuth Client ID** and **OAuth Client Secret** from
   step 4 above.
4. Click **Save Settings**. A Connect button now appears.
5. Click **Connect Google Drive**. You're redirected to Google's
   consent screen.
6. Pick the Google account that owns (or has access to) the folder
   you want submissions mirrored into.
7. Approve the requested scope (`drive` — full Drive access; required
   so the plugin can browse and pick destination folders).
8. You're redirected back to the WP settings page. The status row
   should now show **"Connected as: your-email@example.com"**.

---

## Pick a destination folder

After connecting, a **"Browse my Drive…"** button appears.

1. Click it. A folder browser opens with your Drive root.
2. Click **Open** on a folder to navigate into it; click **Select**
   on the folder you want to use, or use the **Select this folder**
   button in the breadcrumbs.
3. Click **Pick this folder** at the bottom of the picker.
4. The settings page reloads showing **"Picked folder: \<name\>"**.

You can change the picked folder later by clicking **Browse my
Drive…** again.

---

## Test the connection

Click **Test Connection**. The plugin will:

1. Refresh the access token using your stored refresh token
2. Read the picked folder's metadata
3. Upload a tiny `atp-drive-test-<timestamp>.txt` file into it
4. Delete that test file

On success: **"Drive test passed. OK — authenticated, folder
reachable, test file uploaded and removed. Folder: \<name\>"**.

---

## What happens on a real intake submission

For every uploaded file:

1. The plugin always saves it to the **WordPress media library**
   under `wp-content/uploads/atp-intake/<candidate-slug>/`. This is
   the primary, always-on copy.
2. If Drive is connected and a folder is picked, the plugin **also**
   mirrors the file into the picked Drive folder, inside a per-
   submission subfolder named:
   ```
   YYYY-MM-DD_Candidate-Name_Office-Slug/
   ```
3. The file's filename in Drive is prefixed with the intake field
   name (`headshot_*`, `logo_*`, `additional_photos_*`) for clarity.

If the Drive mirror fails (auth expired, network blip, folder
deleted) the plugin logs to `error_log()` and **the submission still
succeeds** — the WP media-library copy is the safety net.

---

## Disconnect / reconnect / rotate

- **Disconnect**: click **Disconnect** in the Connection row. The
  refresh token and folder selection are cleared. Future uploads go
  to WP media only until you reconnect.
- **Reconnect**: click **Connect Google Drive** again and re-approve
  consent. You'll get a fresh refresh token.
- **Rotate the OAuth client secret**: regenerate it in Google Cloud,
  paste the new value into the WP settings, save, click **Connect
  Google Drive** again. The old refresh token will be invalidated.
- **Revoke from Google's side**: <https://myaccount.google.com/security>
  → "Third-party apps with account access" → remove. Plugin will
  detect the revocation on next refresh and clear its cached tokens.

---

## Troubleshooting

### "OAuth state token mismatch"
The CSRF state token expired (15-minute window) or was lost. Click
**Connect Google Drive** again.

### "Token exchange failed: redirect_uri_mismatch"
The redirect URI you registered in Google Cloud doesn't exactly match
what the plugin is sending. Compare the URL shown in the **Authorized
redirect URI** row on the settings page against what's in your OAuth
client → **Authorized redirect URIs**. Trailing slashes, http vs
https, and query params must match exactly.

### "Token exchange failed: invalid_client"
Client ID or Client Secret is wrong, or the OAuth consent screen
isn't published / you're not in the test users list. Re-check both
fields and the consent screen configuration.

### "Token refresh failed: invalid_grant"
The refresh token has been revoked (someone clicked "remove access"
in Google Account settings, or the OAuth client was deleted). The
plugin will auto-clear its cached tokens and prompt you to reconnect.

### "Google did not return a refresh token"
This happens if you've connected this OAuth client to this Google
account before. Go to
<https://myaccount.google.com/permissions>, remove the app's prior
grant, then click **Connect Google Drive** again. The plugin sends
`prompt=consent` to force re-issue.

### "Could not access folder"
The connected account doesn't have permission on the picked folder
(e.g. you connected as user A but the folder is owned by user B and
not shared). Either share the folder with the connected account, or
disconnect and reconnect as the folder's owner.

### "Folder lookup failed: insufficientScopes"
The OAuth consent didn't include the Drive scope. Disconnect and
reconnect — the plugin requests `drive` on every connect.

### Submissions go to WP media only, never to Drive
Check, in order:
- Drive mirroring dropdown is set to **"WordPress + Google Drive"**
- Connection status shows **Connected**
- A folder is picked
- `error_log()` for `[ATP Drive] mirror upload failed: ...` entries —
  any failure path leaves a line there

---

## What's stored where

| Item | Stored in | Why |
|---|---|---|
| OAuth Client ID | `wp_options.atp_drive_oauth.client_id` | Public identifier |
| OAuth Client Secret | `wp_options.atp_drive_oauth.client_secret` | Required for token refresh — admin-only |
| Refresh token | `wp_options.atp_drive_oauth.refresh_token` | Long-lived credential — admin-only |
| Connected account email | `wp_options.atp_drive_oauth.connected_email` | Display only |
| Access token | WP transient `atp_drive_access_token`, ~55 min TTL | Cached; refreshed automatically |
| Picked folder ID + name | `wp_options.atp_drive_config` | Destination |
| Per-submission Drive subfolders | The picked folder, named `YYYY-MM-DD_Name_Office` | Mirror of submission |
| Files | WP media library (always) + Drive subfolder (when mirroring on) | Belt + suspenders |

The OAuth Client Secret and refresh token live in `wp_options`, which
is admin-only via WordPress capability checks. If you want extra
hardening you can encrypt them at rest using a key in
`wp-config.php` — that's a follow-up, not currently implemented.
