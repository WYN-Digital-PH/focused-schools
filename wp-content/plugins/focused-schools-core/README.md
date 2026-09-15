# Focused Schools Core

Site-specific core plugin for the `focused-schools` WordPress project. See
[`../../../AGENTS.md`](../../../AGENTS.md) and [`../../../docs/architecture.md`](../../../docs/architecture.md)
for project-wide rules and architecture context.

## Status

Foundation scaffolding only. No content types, settings, or REST endpoints are
implemented yet.

## Structure

- `focused-schools-core.php` — plugin bootstrap (header, constants, hook registration)
- `includes/` — core classes: autoloader, `Plugin` singleton, `Activator`, `Deactivator`, `Module_Interface`
- `modules/` — one directory per feature area (`site-settings`, `team`, `services`, `impact-stories`, `podcast`), each implementing `Module_Interface`
- `uninstall.php` — intentionally a no-op; the plugin does not delete data on uninstall

## Conventions

- Namespace: `FocusedSchoolsCore` (modules under `FocusedSchoolsCore\Modules`)
- File naming follows WordPress Coding Standards: `class-{name}.php`, `interface-{name}.php`
- No custom database tables, no ACF dependency, no custom REST endpoints
- Activation/deactivation hooks are non-destructive by design

## Adding a module

1. Add a class under `modules/{module-slug}/class-{module-slug}.php` implementing `FocusedSchoolsCore\Module_Interface`.
2. Register it in `FocusedSchoolsCore\Plugin::init()`.
3. Update `docs/architecture.md` with the content-model decisions the module introduces.
