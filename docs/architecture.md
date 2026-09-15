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

_To be defined._ This section will describe:

- Custom post types
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
