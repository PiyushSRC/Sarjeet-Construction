# Sarjeet Construction — WordPress Theme

Custom WordPress theme ported from the Claude Design HTML/CSS/JS prototype.

## Required tech stack

| Layer | Required |
|---|---|
| WordPress | **6.4 or newer** |
| PHP | **8.1 or newer** |
| Database | MySQL 5.7+ / MariaDB 10.4+ (default WP) |
| Recommended plugin | **Advanced Custom Fields PRO** (for repeaters: services, clients, credentials) |
| Optional plugin | **Contact Form 7** (drop-in replacement for the built-in AJAX form) |
| Local dev (Windows) | Local by Flywheel · XAMPP · Laragon · DDEV |
| Production hosting | Any LAMP/LEMP host that supports PHP 8.1+ — Hostinger, SiteGround, Bluehost, Cloudways, Kinsta, etc. |

The theme **does not require a build step** — no Node, no Webpack, no Sass. Plain PHP, CSS, JS.

If ACF Pro is not installed:
- Site-wide content (hero, contact, stats, etc.) falls back to the seed values in `inc/defaults.php`.
- The Project CPT still works through a native WP meta box (location, value, year, length, capacity, client, shape).
- The repeater-driven sections (services, clients, credentials) use seeded defaults until ACF Pro is added.

## Install

1. Zip the `sarjeet-construction` folder.
2. WP admin → **Appearance → Themes → Add New → Upload Theme** → select the zip → **Activate**.
3. WP admin → **Settings → Reading** → set **Your homepage displays** to **A static page**, choose any page as Homepage. (`front-page.php` will render automatically.)
4. (Recommended) Install **Advanced Custom Fields PRO** → activate.
5. WP admin → **Site Content** (left sidebar) → fill Brand, Hero, Services, Stats, Trust, About, CTA, Contact. Each maps to a section on the homepage.
6. WP admin → **Projects → Add New** → create up to 8 projects. Set the featured image, fill the project meta box, and assign a **Project category** (Sewerage / Water Supply / Urban). Use **Page attributes → Order** to reorder them on the grid.
7. (Optional) Install **Contact Form 7** → build a form with `your-name`, `your-email`, `your-org`, `your-message` → paste the shortcode into **Site Content → Contact → Contact Form 7 shortcode**. Empty = built-in AJAX form is rendered instead.
8. WP admin → **Appearance → Menus** → optional. The header has a built-in fallback nav (Home / Services / Projects / Clients / About / Contact) so creating a menu is not required.

## Theme structure

```
sarjeet-construction/
├── style.css                 # WP theme header
├── functions.php             # bootstrap (loads /inc/*)
├── header.php                # sticky white nav + hamburger
├── footer.php                # footer
├── front-page.php            # homepage = sum of section template parts
├── single-project.php        # individual project pages
├── page.php · index.php · 404.php
├── inc/
│   ├── theme-setup.php       # menus, theme-supports, image sizes
│   ├── enqueue.php           # styles + JS + Google Fonts
│   ├── cpt.php               # `project` CPT + `project_category` taxonomy
│   ├── defaults.php          # seed content (mirrors data.js)
│   ├── helpers.php           # sarjeet_field() / sarjeet_projects() / icon SVGs
│   ├── acf-fields.php        # ACF field groups + native meta-box fallback
│   └── contact-handler.php   # built-in AJAX form handler
├── template-parts/sections/  # hero, services, projects, stats, clients, about, cta, contact
└── assets/
    ├── css/styles.css        # the prototype's design system, copied verbatim
    └── js/main.js            # vanilla JS port of all the React interactivity
```

## What the JS does

- Sticky header (solid white from page load — per the design decision in the chat)
- Scroll-spy: highlights nav link of the section currently in view
- Reveal-on-scroll for `.reveal` / `.reveal-stagger` (IntersectionObserver)
- Animated counters in the Stats section
- Project filter pills (All / Sewerage / Water Supply / Urban)
- View toggle (Editorial / Uniform / Index)
- Project modal with body-scroll-lock, Esc-to-close, click-outside-to-close
- Hamburger menu (<960px) with body-scroll-lock
- Built-in AJAX contact form (skipped if a CF7 shortcode is configured)

## Customising content

- **Header brand wordmark** — hard-coded as "Sarjeet" + "Construction" suffix (matches the design). Edit `header.php` to change.
- **Compliance line in hero footer** — Site Content → Hero → Compliance footer.
- **Hero photo / About photo** — text URL field today. Swap to a real image upload by editing `inc/acf-fields.php` (`fld_hero_photo` → change `type` to `image` with `return_format` of `url`) and updating the matching template part to `wp_get_attachment_image_url`.
- **Project ordering** — set **Page attributes → Order** on each Project. Lower numbers appear first.

## Notes / gotchas

- The prototype's **Tweaks panel** (palette / density / hero-variant toggles) is intentionally not ported — it was a design-tool affordance, not a public site feature.
- All Unsplash image URLs are placeholders. Replace with real site photos before launch.
- All project values, stats numbers, client names, contact info are placeholders carried over from `data.js`. Confirm with your team before going live.
- The header is forced solid-white from page load (not transparent-over-hero) per the user's last design decision in `_design_bundle/chats/chat1.md`.
