# Focused Schools Theme

Custom hybrid theme (classic PHP templates + `theme.json`) for the `focused-schools` WordPress site. See
[`../../../AGENTS.md`](../../../AGENTS.md) for project-wide rules.

## Status

Foundation scaffolding only. No templates, components, design tokens, or styles are implemented yet.
Do not activate this theme on staging or production until the design system and page templates are approved.

## Ownership

The theme owns presentation: templates, reusable components, CSS and responsive behavior, `theme.json`, and editor styling.
Content models, settings, and migration helpers belong in `focused-schools-core`.

## Structure

- `style.css` — theme header
- `functions.php` — theme bootstrap
- `header.php`, `footer.php`, `index.php` — minimum templates WordPress requires
- `theme.json` — empty until design tokens are extracted from the approved design
