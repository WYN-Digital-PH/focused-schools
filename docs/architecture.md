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

### 3.2 Other Content Types

_To be defined._ This section will describe, as they are built:

- Custom post types (e.g. Team, Services, Impact Stories, Podcast)
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
