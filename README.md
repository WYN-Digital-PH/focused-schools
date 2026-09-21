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
| `composer.json`, `phpcs.xml.dist` | Dev tooling (WordPress Coding Standards) |
| `.editorconfig` | Editor formatting rules |
| `.github/workflows/` | Staging deployment |

**Never committed:** WordPress core, `wp-config.php`, uploads, cache, backups, `.wpress`/SQL files, secrets,
third-party plugins/themes (including Elementor), `vendor/`, and `node_modules/`. The `.gitignore` is a whitelist,
so anything not listed above stays out of Git by default.

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
