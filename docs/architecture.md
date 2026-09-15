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

_To be defined._ This section will describe:

- Template hierarchy usage
- Template partials / components structure
- Enqueued assets (CSS/JS) strategy
- Block editor (Gutenberg) usage, if any

## 5. Plugin Architecture

_To be defined._ This section will describe:

- Plugin responsibilities and boundaries relative to the theme
- Hooks/filters registered
- Data storage (custom tables, options, post meta) decisions

## 6. Environments

_To be defined._ Local, staging, and production environment differences, if any.

## 7. Open Questions

- (none yet — add items here as they arise)
