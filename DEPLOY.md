# Sarjeet Construction — Production Deployment Guide

The deployable theme is pre-built as **`sarjeet-construction.zip`** in this folder (570 KB, 51 files). Upload it to any WordPress 6.4+ host running PHP 8.1+.

---

## Step 1 — Buy hosting

After purchasing the GoDaddy domain, get WordPress hosting from one of these (any will work):

| Host | Plan | Why |
|---|---|---|
| **Hostinger** | Premium WordPress (~₹149–399/mo) | Cheapest, has LiteSpeed |
| **SiteGround** | StartUp (~₹599+/mo) | Faster, better support |
| **Bluehost** | Choice Plus | GoDaddy-friendly setup |

You'll receive: cPanel/hPanel login + two **nameservers** (e.g. `ns1.hostinger.com`).

## Step 2 — Point the domain

In **GoDaddy → My Products → DNS → Nameservers**, change to the host's nameservers. DNS propagates in 1–24 hours.

## Step 3 — Install WordPress

In your host's panel: **Auto Install WordPress** on your domain. Set admin user/password. Done in ~2 minutes.

## Step 4 — Upload the theme

1. Log into `https://yourdomain.com/wp-admin`
2. **Appearance → Themes → Add New → Upload Theme**
3. Choose `sarjeet-construction.zip` → Install Now → Activate
4. **Settings → Permalinks → Post name → Save** (required for the virtual `?view=` routes to work cleanly)
5. **Settings → Reading → Your homepage displays → A static page → Front page = Sample Page** (any page; the theme's `front-page.php` overrides it)

## Step 5 — Enable HTTPS

In your host's panel: turn on **Free SSL (Let's Encrypt)**. Then in WP **Settings → General**, ensure both `WordPress Address` and `Site Address` are `https://yourdomain.com`.

## Step 6 — Add production `.htaccess` rules

Open **File Manager → public_html → .htaccess** in cPanel. Paste the contents of [wp-content/themes/sarjeet-construction/inc/htaccess-snippet.txt](wp-content/themes/sarjeet-construction/inc/htaccess-snippet.txt) **above** the `# BEGIN WordPress` block. Save.

This adds: 1-year cache for static assets, gzip, hidden server signature, `wp-config.php` protection, blocked directory listing, blocked PHP execution in `/uploads/`.

## Step 7 — Install required plugins

| Plugin | Why | Settings |
|---|---|---|
| **WP Mail SMTP** | Contact form delivery (default `wp_mail()` is unreliable) | Connect to Brevo / SendGrid free tier (300 emails/day) |
| **LiteSpeed Cache** (only if host runs LiteSpeed — most cheap WP hosts do) | Auto-handles minify CSS, critical CSS, unused CSS removal — closes the remaining Lighthouse gaps | Page Optimization → CSS → enable "CSS Minify", "CSS Combine", "Generate Critical CSS" |
| **UpdraftPlus** | Backups | Schedule daily, store in Google Drive |
| **Wordfence** *(optional)* | Login protection, malware scan | Default settings fine |

If your host doesn't run LiteSpeed, use **WP Rocket** (paid) or **W3 Total Cache** (free) instead.

## Step 8 — Configure the contact email destination

The theme has a fallback "contact" email defined in [inc/defaults.php](wp-content/themes/sarjeet-construction/inc/defaults.php). If you want to change it without editing code, install **Advanced Custom Fields (ACF)** plugin — the theme already has the bridge wired up (`acf-fields.php`). All site content (hero text, projects, clients, contact info) becomes editable in **Site Content** in the WP admin.

Alternatively, just edit the `contact.email` value in [inc/defaults.php](wp-content/themes/sarjeet-construction/inc/defaults.php) before zipping.

## Step 9 — Smoke test

1. Open your live domain in an incognito browser.
2. Click each nav item: Home, About, Projects, Clients, Contact, plus the footer legal links.
3. Submit the contact form with a real email — it should arrive within a few minutes (check spam).
4. Run **Lighthouse mobile** on the live URL. Performance should be 85–95 (host gzip + LiteSpeed close the remaining items).

## Step 10 — Post-launch

- Submit your sitemap to **Google Search Console**: `https://yourdomain.com/wp-sitemap.xml`
- Add **Google Analytics 4** snippet (paste in `header.php` before `</head>`, or use a plugin like "Site Kit")
- Set up **Google Business Profile** if you want local SEO (the theme already emits LocalBusiness JSON-LD)

---

## What's NOT in the zip (intentional)

- Docker dev stack (`docker-compose.yml`) — local-only
- `assets/partners/` and `assets/uploads/` at project root — those are the Docker-mounted source assets; the theme already has its own copies under `wp-content/themes/sarjeet-construction/assets/images/partners/`
- WordPress core itself — provided by your host's auto-installer

## Hotfix workflow (recommended for bug fixes after launch)

Once the theme is installed, **don't rebuild the zip and re-upload through wp-admin for every fix**. Push only the changed files via SFTP — it's a 5-second loop instead of 2 minutes.

### One-time FileZilla setup

1. Get **SFTP credentials** from your host panel (FTP / SFTP Accounts). Use **port 22 (SFTP)**, never port 21 (plain FTP).
2. FileZilla → **File → Site Manager → New Site** → "Sarjeet Production":
   - Protocol: **SFTP – SSH File Transfer Protocol**
   - Host / Port (22) / User / Password from the host
   - Logon Type: **Ask for password**
3. **Advanced tab:**
   - Default local directory: the local `wp-content/themes/sarjeet-construction/` folder
   - Default remote directory: `/public_html/wp-content/themes/sarjeet-construction/` (path varies per host — verify after first connect)
4. **Edit → Settings:**
   - **Transfers → File Types → Default = Binary** (prevents line-ending corruption on PHP/CSS)
   - **Transfers → File exists action → Overwrite if source newer** (both directions)
   - **Interface → File editing → Use custom editor** → point at VS Code / Notepad++ so right-click → View/Edit on a remote file auto-uploads on save

### Daily loop

1. Edit the file locally (e.g. `style.css`, `header.php`, `inc/defaults.php`).
2. In FileZilla, navigate the left pane to the file, right pane to the matching remote folder.
3. Right-click the file → **Upload** (or double-click).
4. Hard-refresh the live site (`Ctrl+Shift+R`).

Turn on **View → Synchronized browsing** so the two panes stay mirrored — stops you from uploading to the wrong directory. **View → Directory comparison** color-codes which files differ.

### Optional: auto-upload on save (VS Code)

Install the **SFTP** extension by Natizyskunk (`natizyskunk.sftp`). Create `.vscode/sftp.json`:

```json
{
  "name": "Sarjeet Production",
  "host": "ftp.yourdomain.com",
  "protocol": "sftp",
  "port": 22,
  "username": "YOUR_FTP_USER",
  "remotePath": "/public_html/wp-content/themes/sarjeet-construction",
  "context": "wp-content/themes/sarjeet-construction",
  "uploadOnSave": true,
  "ignore": [".vscode", ".git", "node_modules", "*.zip"]
}
```

Add `.vscode/sftp.json` to `.gitignore` — it contains credentials. Now `Ctrl+S` pushes to production in ~1 second.

### Safety rules

- **Never upload `wp-config.php`** from local — your Docker copy has different DB creds and will white-screen the live site.
- **Never upload `wp-content/uploads/`** — that's live user media, not part of the theme.
- **Take a backup before risky changes.** Right-click remote theme folder → Download to `theme-backup-YYYY-MM-DD/`. UpdraftPlus daily backups (Step 7) cover the rest.
- **PHP syntax error = white screen.** Re-upload the previous version of the file (or `git checkout` and re-upload).
- **Purge cache after upload** if LiteSpeed Cache / WP Rocket is active, otherwise the old version is served from cache.

## Rebuilding the zip (only for first-time install or major version bumps)

Use this only when shipping the theme to a fresh host or handing it to someone else — not for bug fixes (use SFTP instead, above).

```powershell
Compress-Archive -Path 'wp-content\themes\sarjeet-construction' -DestinationPath 'sarjeet-construction.zip' -Force
```

Then re-upload via **Appearance → Themes → Add New → Upload Theme** (it will replace).

## Troubleshooting

- **White screen after activation** → check PHP version is 8.1+ in host panel. PHP error log is in cPanel → "Errors".
- **Contact form returns "Mail server unavailable"** → SMTP plugin not configured. See Step 7.
- **404 on `/?view=privacy` etc.** → Permalinks not flushed. Re-save in **Settings → Permalinks**.
- **Images broken** → check `wp-content/uploads/` exists and is writable (host should handle this).
- **Site at `http://` instead of `https://`** → SSL not active or WP General Settings still has http. Fix both.
