# Google Drive Upload — Setup Guide

The intake form can store uploaded files (headshots, logos, additional photos)
in Google Drive instead of the WordPress media library. This document walks
through everything required to activate it on a fresh install.

> **Important:** the service-account JSON key is a credential. It must NEVER
> be committed to this repo, pasted into chat, or stored inside the WordPress
> web root. Treat it like a password.

---

## Architecture

```
WP form submission
   └─► atp_handle_file_upload()             (file-upload.php)
         └─► atp_drive_upload()             when storage = google_drive
               ├─► atp_drive_get_access_token()    JWT → OAuth token (cached)
               ├─► atp_drive_find_or_create_folder()
               │     parent: configured Folder ID
               │     name:   YYYY-MM-DD_Candidate-Name_Office-Slug
               └─► atp_drive_upload_file()        multipart upload
```

All Drive code lives in `packages/atp-plugin-core/includes/drive-client.php`.
It uses only `wp_remote_*` and PHP's `openssl` extension — no Composer or
Google SDK.

On any failure (auth, folder, upload) the handler logs to `error_log()` and
falls back to the WordPress media library so submissions are never lost.

---

## One-time Google Cloud setup

This is normally done once per agency / environment, not per campaign site.

1. **Create a Google Cloud project** (e.g. `atp-intake-drive`).
2. **Enable the Google Drive API** for the project.
3. **Create a service account** (e.g. `atp-intake-uploader`).
4. **Generate a JSON key** for the service account. Download it. This file
   is the credential — protect it accordingly.
5. **Create the parent Drive folder** (e.g. `Intake_Submissions_Live`) in a
   Drive you control (personal, shared drive, or Workspace).
6. **Share the folder with the service account email** as **Editor**. The
   email looks like `atp-intake-uploader@<project>.iam.gserviceaccount.com`.

> **Why Editor and not Viewer?** The plugin needs to create dated subfolders
> and upload files into them.

> **Why share the folder?** The plugin uses the `drive.file` OAuth scope,
> which means the service account can only see files it created or files
> explicitly shared with it. Without the share, the parent folder is
> invisible to the service account and upload will fail with
> "Could not access folder."

The folder ID is the string in the folder URL after `/folders/`:

```
https://drive.google.com/drive/folders/1AmUatOOqqliQezIJZM2qqO6jt3M_dHZR
                                       └────────── folder ID ──────────┘
```

---

## Per-server installation

Do this once per WordPress server.

### 1. Place the JSON key outside the web root

The web root is wherever WordPress lives (e.g. `/var/www/html/`). The key
must NOT be under that path — anything in the web root can in principle be
served as a static file.

```bash
sudo mkdir -p /var/www/atp-private
sudo chmod 700 /var/www/atp-private
sudo chown www-data:www-data /var/www/atp-private
```

Then upload the JSON key from your laptop:

```bash
scp ~/Downloads/atp-intake-drive-XXXXX.json \
    user@host:/tmp/atp-drive-key.json

ssh user@host
sudo mv /tmp/atp-drive-key.json /var/www/atp-private/atp-drive-key.json
sudo chmod 600 /var/www/atp-private/atp-drive-key.json
sudo chown www-data:www-data /var/www/atp-private/atp-drive-key.json
```

Verify the web server user can read it:

```bash
sudo -u www-data cat /var/www/atp-private/atp-drive-key.json | head -c 50
# Should print the start of the JSON (e.g. {"type": "service_account", ...)
```

> **Why `chmod 600` and `chown www-data`?** Only the WordPress process needs
> to read this file. No other users on the box should be able to. The exact
> user name (`www-data`, `nginx`, `apache`, etc.) depends on your distro and
> web server.

### 2. Configure the WordPress plugin

In WP admin: **ATP → White Label Settings → File Upload Storage**:

| Field | Value |
|---|---|
| Upload destination | **Google Drive** |
| Google Drive Folder ID | The ID from step 5 of Cloud setup |
| Service Account JSON Path | Absolute path, e.g. `/var/www/atp-private/atp-drive-key.json` |

Click **Save Settings**.

### 3. Test the connection

Click **Test Drive Connection** on the same page.

On success you'll see:

> **Drive test passed.** OK — authenticated, folder reachable, test file
> uploaded and removed. Parent folder: `Intake_Submissions_Live`

The test:
1. Performs a fresh JWT exchange (bypasses the token cache).
2. Reads the parent folder metadata.
3. Uploads a tiny `atp-drive-test-<timestamp>.txt` file into the parent.
4. Deletes the test file.

### 4. Submit a real intake

Walk through the candidate intake form with a test headshot. After submit,
verify:
- A subfolder named `YYYY-MM-DD_Candidate-Name_Office-Slug` exists under the
  parent.
- The uploaded file is inside that subfolder.
- In WP admin the submission detail page shows an "Open submission folder
  in Drive" button that opens the right folder.

---

## Troubleshooting

### "Service account JSON file is missing or not readable"

The path saved in settings does not exist or `www-data` cannot read it.

- Confirm the file exists: `ls -l /var/www/atp-private/atp-drive-key.json`
- Confirm web server can read it:
  `sudo -u www-data test -r /var/www/atp-private/atp-drive-key.json && echo OK`
- Check parent directory is traversable: `chmod 711` on the directory if
  needed (or keep `700` and chown the directory to `www-data`).

### "Token exchange failed (400): invalid_grant"

The JWT couldn't be signed or the system clock is skewed.

- Check server time: `date -u` should match real UTC within a minute.
- Confirm the JSON key is the full unmodified file from Google Cloud — if
  someone copy/pasted it, the `private_key` field's `\n` escapes may be
  broken. The file must be exactly what Google issued.

### "Could not access folder."

The parent folder is not shared with the service account, OR the folder ID
is wrong.

- Re-check the folder ID is the string after `/folders/` in the URL.
- In Drive, right-click the folder → Share → confirm the service account
  email is listed as Editor (not Viewer, not Commenter).
- Service account email is in the JSON key as `client_email`.

### Uploads succeed but go to WordPress media library, not Drive

The fallback path is engaging. Check:
- Upload destination is set to "Google Drive" (not "WordPress Media Library").
- The credentials path field is filled and shows ✓ readable.
- Tail the PHP error log — fallbacks log a `[ATP Drive]` line with the reason.

### "Folder lookup failed: ... insufficientScopes"

The service account JSON has been replaced with one for a different scope,
or the Drive API is not enabled on the project. Re-enable the Drive API in
Google Cloud and regenerate the key if needed.

---

## Rotating the key

If the key is leaked or you need to rotate periodically:

1. In Google Cloud → IAM → Service Accounts → keys: **Create new key** (JSON).
2. `scp` the new file to the server, replacing
   `/var/www/atp-private/atp-drive-key.json`.
3. In Google Cloud, delete the old key.
4. In WP admin, click **Save Settings** (this clears the cached access
   token), then **Test Drive Connection**.

No code changes required.

---

## What never to do

- ❌ Don't commit the JSON key to git. The repo `.gitignore` blocks common
  filename patterns as a safety net, but the only safe location is outside
  the repo entirely.
- ❌ Don't paste the JSON into the WP database (e.g. as a setting value
  rather than a file path). Database backups and exports would carry the
  credential.
- ❌ Don't store the JSON under `wp-content/`, `wp-includes/`, or any path
  served by the web server.
- ❌ Don't share the JSON in chat, email, or screenshots. Use a secrets
  manager or a one-time-link service (Bitwarden Send, 1Password share, etc.).
