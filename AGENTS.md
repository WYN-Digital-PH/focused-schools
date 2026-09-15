# AGENTS.md

Operating rules for AI coding assistants (Claude Code, Codex, Copilot, etc.) working on
**focused-schools**, a WYN-owned WordPress project.

## 1. Project Scope

This repository is the WordPress site root for `focused-schools`. Active development is
scoped to:

- `wp-content/themes/focused-schools/` — the custom theme
- `wp-content/plugins/focused-schools-core/` — the custom core plugin
- `docs/` — project documentation
- Root-level tooling config (`composer.json`, `phpcs.xml.dist`, `.editorconfig`, `.gitignore`)

## 2. Do Not Touch

AI assistants must **never** create, modify, or delete:

- WordPress core files (`wp-admin/`, `wp-includes/`, root `wp-*.php`, `index.php`, `license.txt`, `readme.html`)
- Third-party/vendor plugins or themes not listed above (including default themes such as
  `twentytwenty*`)
- `wp-content/uploads/`
- `wp-config.php` or any file containing credentials/secrets
- SQL dumps or database exports
- `.env` files

If a task appears to require changes in one of these areas, stop and ask for explicit
confirmation before proceeding.

## 3. Coding Standards

- Follow WordPress Coding Standards (WPCS) as configured in `phpcs.xml.dist`.
- PHP, JS, and CSS should match the conventions already established in the theme/plugin
  once they exist; do not introduce new frameworks or build tools without discussion.
- No commented-out code, debug `var_dump`/`print_r`, or TODOs left in committed code.

## 4. Documentation

Consult and keep in sync with:

- [`docs/architecture.md`](docs/architecture.md) — architecture and content-model rules
- [`docs/migration-qa-rules.md`](docs/migration-qa-rules.md) — migration & QA guidelines
- [`docs/design-system.md`](docs/design-system.md) — design system reference
- [`docs/component-specs.md`](docs/component-specs.md) — component specifications
- [`docs/audit/audit-report.md`](docs/audit/audit-report.md) — sanitized audit findings

When a change affects architecture, content modeling, migrations, or components, update the
relevant doc in the same change set.

## 5. Git & Workflow Rules

- Never commit secrets, credentials, `wp-config.php`, database dumps, or uploads.
- Never force-push, rewrite history, or delete branches without explicit user approval.
- Keep commits scoped to the theme/plugin/docs/tooling described in Section 1.
- Do not run destructive database or file operations (`wp db drop`, `rm -rf`, etc.)
  without explicit user approval.

## 6. Placeholder Status

This documentation set is baseline scaffolding. No custom features, content models, or
components are implemented yet. Do not treat placeholder text in `docs/` as finalized
requirements — confirm with the project owner before building against it.
