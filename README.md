# Focused Schools

Custom WordPress theme and site plugin for the Focused Schools website refresh.
See [Focused-Schools-Project-Plan.md](Focused-Schools-Project-Plan.md) for the plan and [AGENTS.md](AGENTS.md) for development rules.

## Repository layout

The local checkout is a full WordPress install (Local by Flywheel), but Git only tracks project-owned files:

| Path | Purpose |
| --- | --- |
| `wp-content/themes/focused-schools/` | Custom hybrid theme (presentation) |
| `wp-content/plugins/focused-schools-core/` | Site plugin (content models, settings, migration helpers) |
| `docs/` | Audits, architecture, and migration docs |
| `docs/page-specs/` | One spec per page; see the template in its README |
| `docs/design-comps/` | The approved `.dc.html` design sources (reference only; not deployed) |
| `composer.json`, `phpcs.xml.dist` | Dev tooling (WordPress Coding Standards) |
| `.editorconfig` | Editor formatting rules |
| `.github/workflows/` | Staging deployment |

**Never committed:** WordPress core, `wp-config.php`, uploads, cache, backups, `.wpress`/SQL files, secrets,
third-party plugins/themes (including Elementor), `vendor/`, and `node_modules/`. The `.gitignore` is a whitelist,
so anything not listed above stays out of Git by default.

## Local setup

Prerequisites: [Local](https://localwp.com/) (or any WordPress 6.0+ stack), PHP 7.4 or newer, and
[Composer](https://getcomposer.org/) for the lint tooling.

1. Create a WordPress site in Local. Local generates `wp-config.php`; it is ignored by Git and must never be committed.
2. Clone this repository into the site's `app/public` folder, or copy the two project directories into an existing
   install's `wp-content/`. Git only tracks project-owned files, so an existing WordPress install is left alone.
3. In wp-admin, activate the **Focused Schools** theme and the **Focused Schools Core** plugin.
4. Fill in **Focused Schools → Site Settings** (business details, primary CTA, social links, Buzzsprout ID). Nothing
   business-specific is hardcoded in the theme.
5. Optional, for the Podcast page's YouTube videos: define `FOCUSED_SCHOOLS_YOUTUBE_API_KEY` in `wp-config.php` (or as an
   environment variable), then enable the integration under **Focused Schools → Podcast (YouTube)**. The key is never
   stored in the database or Git.
6. Install the dev tooling and run the standards check:

   ```sh
   composer install
   composer lint
   ```

Notes: Elementor Pro is not part of this repository. Local form submissions and notification emails are caught by
Local's bundled Mailpit rather than delivered. Design sources live in `docs/design-comps/`.

## Coding standards

```sh
composer install
composer lint       # phpcs
composer lint:fix   # phpcbf
```

## Staging deployment

Every push to `main` (including merged pull requests) runs `.github/workflows/deploy-staging.yml`, which rsyncs
**only** these two directories to https://grey-lemur-550879.hostingersite.com/ over SSH:

- `wp-content/themes/focused-schools/`
- `wp-content/plugins/focused-schools-core/`

Nothing else on the server is touched, and `--delete` is scoped to those two directories. Docs and dev tooling are not deployed.
Deploying does not activate the theme or plugin. The workflow can also be run manually from the Actions tab.

Required repository secrets (Settings → Secrets and variables → Actions):

| Secret | Value |
| --- | --- |
| `STAGING_SSH_HOST` | Hostinger SSH IP (hPanel → Advanced → SSH Access) |
| `STAGING_SSH_PORT` | Hostinger SSH port (usually `65002`) |
| `STAGING_SSH_USER` | Hostinger SSH username (e.g. `u123456789`) |
| `STAGING_WP_PATH` | Absolute path to the WordPress root, e.g. `/home/u123456789/domains/grey-lemur-550879.hostingersite.com/public_html` |
| `STAGING_SSH_KEY` | Private key whose public half is added in hPanel → SSH Access → SSH keys |
