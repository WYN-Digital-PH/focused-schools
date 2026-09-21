# Focused Schools

Custom WordPress theme and site plugin for the Focused Schools website refresh.
See [Focused-Schools-Project-Plan.md](Focused-Schools-Project-Plan.md) for architecture and rules.

## What is tracked

The local checkout is a full WordPress install (Local by Flywheel), but Git only tracks project-owned code:

- `wp-content/themes/focused-schools/`
- `wp-content/plugins/focused-schools-core/`
- `.github/workflows/` and project docs

WordPress core, uploads, third-party plugins/themes, `wp-config.php`, `.wpress`/SQL files, and secrets are ignored.

## Staging deployment

Every push to `main` (including merged pull requests) runs `.github/workflows/deploy-staging.yml`, which rsyncs the tracked theme and plugin to
https://grey-lemur-550879.hostingersite.com/ over SSH. Only those two directories are touched on the server; `--delete` is scoped to them.
It can also be run manually from the Actions tab.

Required repository secrets (Settings → Secrets and variables → Actions):

| Secret | Value |
| --- | --- |
| `STAGING_SSH_HOST` | Hostinger SSH IP (hPanel → Advanced → SSH Access) |
| `STAGING_SSH_PORT` | Hostinger SSH port (usually `65002`) |
| `STAGING_SSH_USER` | Hostinger SSH username (e.g. `u123456789`) |
| `STAGING_WP_PATH` | Absolute path to the WordPress root, e.g. `/home/u123456789/domains/grey-lemur-550879.hostingersite.com/public_html` |
| `STAGING_SSH_KEY` | Private key whose public half is added in hPanel → SSH Access → SSH keys |
