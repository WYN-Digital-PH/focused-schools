# Architecture & Content Model

Status: **placeholder** — baseline structure only, no features implemented yet.

## 1. Overview

This document describes the technical architecture and content model for the
`focused-schools` WordPress site. It should be updated whenever architecture or
content-model decisions are made.

## 2. System Boundaries

- **Theme:** `wp-content/themes/focused-schools/`
- **Plugin (site-specific logic):** `wp-content/plugins/focused-schools-core/`
- **WordPress core, third-party plugins, and uploads** are out of scope for this
  document and must not be modified as part of custom feature work.

## 3. Content Model

### 3.1 Site Settings

Global, singleton business/site data — not a post type. Implemented via the native
WordPress Settings API / Options API in
`wp-content/plugins/focused-schools-core/modules/site-settings/`.

- **Storage:** a single namespaced option, `focused_schools_site_settings`, holding one
  associative array (no custom DB tables, no ACF).
- **Settings group:** `focused_schools_site_settings_group`
- **Admin location:** Focused Schools → Site Settings (`admin.php?page=focused-schools-site-settings`)
- **Field schema source of truth:** `FocusedSchoolsCore\Modules\Site_Settings\Fields::all()`
- **Fields:**

  | Section | Fields |
  | ------- | ------ |
  | Business Information | Business Name, Phone, Email, Address |
  | Primary Call To Action | Primary CTA Label, Primary CTA URL |
  | Social Links | Facebook URL, LinkedIn URL, YouTube URL |
  | Footer | Footer Short Text, Copyright Name |

- **Theme retrieval:** `focused_schools_get_setting( $key, $default = '' )` (defined in
  `includes/functions.php`). Returns the sanitized-at-save-time value; the caller must
  still escape for output context (`esc_html()`, `esc_url()`, etc.).

### 3.2 Team Members

Admin-only content type (no public URLs) for staff/leadership profiles, implemented in
`wp-content/plugins/focused-schools-core/modules/team/`.

- **Post type:** `fs_team_member`
- **Visibility:** `public => false`, `publicly_queryable => false`, `has_archive => false`,
  `rewrite => false` — no frontend URLs or archive; content is surfaced only through
  whatever the theme queries directly (e.g. a "Team" page template pulling these posts).
- **Admin location:** nested under Focused Schools → Team Members
  (`show_in_menu` set to the Site Settings page slug so it appears as a submenu of the
  shared "Focused Schools" top-level menu).
- **Supports:** `title` (member name), `editor` (optional bio), `thumbnail` (headshot),
  `page-attributes` (`menu_order`, used for manual ordering)
- **REST/Gutenberg:** `show_in_rest => true`
- **Meta fields** (schema source of truth: `FocusedSchoolsCore\Modules\Team\Meta::all()`,
  keys prefixed `_fs_team_`):

  | Field | Type | Sanitizer |
  | ----- | ---- | --------- |
  | Position | text | `sanitize_text_field` |
  | Quote | textarea | `sanitize_textarea_field` |
  | LinkedIn URL | url | `esc_url_raw` |

- **Admin columns:** Headshot, Name, Position, Order, Modified Date.

### 3.3 Services

Admin-only content type (no public URLs) for the services list, implemented in
`wp-content/plugins/focused-schools-core/modules/services/`.

- **Post type:** `fs_service`
- **Visibility:** `public => false`, `publicly_queryable => false`, `has_archive => false`,
  `rewrite => false` — no frontend URLs or archive; content is surfaced only through
  whatever the theme queries directly.
- **Admin location:** nested under Focused Schools → Services (same shared top-level menu
  as Site Settings and Team Members).
- **Supports:** `title`, `editor`, `excerpt`, `thumbnail`, `page-attributes` (`menu_order`)
- **REST/Gutenberg:** `show_in_rest => true`
- **Meta fields** (schema source of truth: `FocusedSchoolsCore\Modules\Services\Meta::all()`,
  keys prefixed `_fs_service_`):

  | Field | Type | Sanitizer | Notes |
  | ----- | ---- | --------- | ----- |
  | Tagline | text | `sanitize_text_field` | — |
  | Accent Role | enum | `Meta::sanitize_accent_role()` | Strictly whitelisted to `strategy`, `leadership`, or `capacity` — no hex colors or arbitrary strings. Enforced both in the admin `<select>` and in the REST meta schema (`enum` in `show_in_rest`), plus a manual sanitize pass in the classic save handler. Defaults to `strategy` when unset. |

- **Admin columns:** Tagline, Accent Role, Order, Modified Date.
- No demo content is created on plugin activation.

### 3.4 Impact Stories

Public content type, implemented in
`wp-content/plugins/focused-schools-core/modules/impact-stories/`. Unlike Team Members
and Services, this one has real frontend URLs.

- **Post type:** `fs_impact_story`
- **Visibility:** `public => true`, `publicly_queryable => true`, `show_in_rest => true`
- **URLs:** `has_archive => false` — the existing `/impact-stories/` **Page** remains the
  main landing page. Individual stories use `rewrite => [ 'slug' => 'impact-stories' ]`,
  giving URLs of the form `/impact-stories/{story-slug}/`, which coexist with the Page at
  the bare `/impact-stories/` path since there is no competing archive rule.
- **Admin location:** nested under Focused Schools → Impact Stories (same shared
  top-level menu as Site Settings, Team Members, and Services).
- **Supports:** `title`, `editor`, `excerpt`, `thumbnail`
- **Meta fields** (schema source of truth:
  `FocusedSchoolsCore\Modules\Impact_Stories\Meta::all()`, keys prefixed
  `_fs_impact_story_`):

  | Field | Type | Sanitizer | Notes |
  | ----- | ---- | --------- | ----- |
  | District or School | text | `sanitize_text_field` | — |
  | State | text | `sanitize_text_field` | — |
  | Year | text | `sanitize_text_field` | — |
  | Featured | boolean | `rest_sanitize_boolean` | Rendered as a checkbox; an unchecked box is explicitly saved as `0`, not skipped. Defaults to `false`. |

#### Legacy Page Bridge (migration utility)

A WP-CLI command tags approved, pre-existing legacy Pages as impact stories without
migrating them into the new post type. See
[`docs/migration-qa-rules.md`](migration-qa-rules.md) §9 for full usage, guardrails, and
the dry-run/`--write` workflow.

- **Meta key:** `_fs_legacy_impact_story` (set to `1` on tagged Pages)
- **Class:** `FocusedSchoolsCore\Modules\Impact_Stories\Legacy_Bridge` — the only write
  it can ever perform is `update_post_meta()` for this one key; it has no code path that
  touches `post_type`, `post_name`, content, or any other meta (Yoast included).

### 3.5 Other Content Types

_To be defined._ This section will describe, as they are built:

- Custom post types (e.g. Podcast)
- Custom taxonomies
- Custom fields / field groups
- Relationships between content types

## 4. Theme Architecture

The `focused-schools` theme (`wp-content/themes/focused-schools/`) has a foundation
(bootstrap, global styles, accessibility defaults) plus a set of reusable components (see
§4.5). No individual page templates exist yet — components are built and documented, but
not yet assembled into pages.

### 4.1 File structure

```
wp-content/themes/focused-schools/
├── style.css              # required WP theme header + mobile-first base stylesheet
├── theme.json              # global settings/styles (see 4.4 — placeholder tokens)
├── functions.php           # bootstrap: requires inc/ files only
├── index.php               # minimal required fallback template (no page design yet)
├── header.php              # doctype, skip link, wp_head(), delegates nav to site-header component
├── footer.php              # delegates nav to site-footer component, wp_footer()
├── inc/
│   ├── setup.php           # add_theme_support(), register_nav_menus(), content_width
│   └── enqueue.php         # wp_enqueue_style()/wp_enqueue_script() for base + all components
├── template-parts/
│   └── components/         # 12 reusable components — see docs/component-specs.md
└── assets/
    ├── css/
    │   ├── editor-style.css    # mirrors frontend base typography in the block editor
    │   └── components/         # one CSS file per component + shared card.css base
    └── js/
        └── components/         # site-header.js (nav toggle), statistics-counter.js (count-up)
```

There is intentionally no `page.php` and no custom page templates yet, so Elementor's own
page templates (Canvas / Full Width) and existing Elementor-built pages are unaffected —
the theme adds no filters touching `theme_page_templates`, `wp_head`, `wp_footer`, or
content rendering.

### 4.2 Template hierarchy usage

Only the WordPress-required fallback (`index.php`) plus `header.php`/`footer.php` exist.
Page-specific templates are deferred to future tasks per project scope.

### 4.3 Enqueued assets strategy

- The theme's own stylesheet is enqueued via `get_stylesheet_uri()` (i.e. `style.css`
  itself is the single frontend stylesheet — no separate build step or bundler).
- Block editor: `add_theme_support( 'editor-styles' )` + `add_editor_style()` loads
  `assets/css/editor-style.css`, which mirrors the frontend's base typography/layout
  variables so the editor preview roughly matches the frontend.
- No external CSS/JS framework (Tailwind, Bootstrap, etc.) is used.

### 4.4 Block editor (Gutenberg) / `theme.json`

`theme.json` (schema v2) defines global settings and styles: `appearanceTools`, a
`layout.contentSize`/`wideSize`, a color palette, font family/sizes, and a spacing scale.

**Every token in `theme.json` is a TEMPORARY structural placeholder**, not an approved
design decision — see [`docs/design-system.md`](design-system.md), which has no real
tokens defined yet. Palette and font-family entries are deliberately named
`"Placeholder – …"` so they're identifiable as such directly in the block editor's color
picker. A `custom.tokenStatus` key (`"placeholder-pending-design-system-md"`) is set
specifically so this status is traceable in generated CSS
(`--wp--custom--token-status`), not just in documentation. When real tokens are approved,
update `docs/design-system.md` first, then bring `theme.json` in line with it.

Theme supports registered in `inc/setup.php`: `title-tag`, `align-wide`,
`responsive-embeds`, `post-thumbnails`, `editor-styles`, and an `html5` markup list
(search form, comment form/list, gallery, caption, script, style). Two nav menus are
registered: `primary` and `footer`.

### 4.5 Reusable Components

12 approved reusable components live in `template-parts/components/`, each invoked as
`get_template_part( 'template-parts/components/{name}', null, $args )`. Full specs
(props/contracts, states, dependencies) are documented in
[`docs/component-specs.md`](component-specs.md) — that is the source of truth, not this
section. In brief: Header & Footer navigation structures, Hero, Section Heading,
Buttons/CTA, Content/Image Split, Service/Team/Impact Story cards (CPT-integrated),
Podcast Card and Partner Strip (generic/args-driven — no CPT yet), a Statistics counter,
and a Form styling wrapper for Elementor coexistence. All component CSS uses only
`theme.json` custom properties (still the TEMPORARY placeholders from §4.4); component
JS is limited to two small, scoped, vanilla files (nav toggle, count-up animation), both
respecting `prefers-reduced-motion` where relevant.

## 5. Plugin Architecture

_To be defined._ This section will describe:

- Plugin responsibilities and boundaries relative to the theme
- Hooks/filters registered
- Data storage (custom tables, options, post meta) decisions

## 6. Environments

_To be defined._ Local, staging, and production environment differences, if any.

## 7. Open Questions

- (none yet — add items here as they arise)
