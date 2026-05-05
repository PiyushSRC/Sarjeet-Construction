# Local dev environment

## Stack

Docker Compose runs everything — no Local by Flywheel, XAMPP, or system PHP needed.

| Service | Image | Port |
|---|---|---|
| WordPress | `wordpress:6.7-php8.2-apache` | 8080 |
| Database | `mariadb:11` | (internal) |
| phpMyAdmin | `phpmyadmin:5` | 8081 |
| WP-CLI | `wordpress:cli-php8.2` | (on demand) |

## URLs

| Where | URL | Login |
|---|---|---|
| Site front-end | http://localhost:8080 | — |
| WP Admin | http://localhost:8080/wp-admin | `admin` / `admin` |
| phpMyAdmin | http://localhost:8081 | `root` / `root` |
| Prototype (static) | http://127.0.0.1:8000/Sarjeet%20Construction.html | — |

## Workflow

1. Edit theme files in VS Code under [wp-content/themes/sarjeet-construction/](wp-content/themes/sarjeet-construction/).
2. Refresh http://localhost:8080 — changes are live (the folder is mounted into the container).
3. For content (Hero copy, Projects, Site Content options), use http://localhost:8080/wp-admin.

## Common commands

```bash
# Start the stack
docker compose up -d

# Stop the stack (keeps data)
docker compose down

# Stop and wipe the database (fresh install)
docker compose down -v

# Tail WordPress logs
docker compose logs -f wordpress

# Run a WP-CLI command
docker compose run --rm cli wp plugin list
docker compose run --rm cli wp post list --post_type=project
docker compose run --rm cli wp cache flush

# Install Advanced Custom Fields (free)
docker compose run --rm cli wp plugin install advanced-custom-fields --activate

# Install Contact Form 7
docker compose run --rm cli wp plugin install contact-form-7 --activate
```

## Notes

- The container ships with PHP 8.2, satisfying the theme's PHP 8.1+ requirement.
- The theme folder is bind-mounted, but WordPress core lives in a Docker volume (`wp_core`) so updates survive restarts.
- `wp-content/uploads` lives inside the `wp_core` volume — to back up images, use `docker compose run --rm cli wp media export`.
- Permalinks are set to `/%postname%/`. Visit `/projects/<slug>/` to see project detail pages.
