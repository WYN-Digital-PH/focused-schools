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
├── front-page.php          # Home page (static front page) — see 4.6
├── inc/
│   ├── setup.php           # add_theme_support(), register_nav_menus(), content_width
│   └── enqueue.php         # wp_enqueue_style()/wp_enqueue_script() for base + all components
├── template-parts/
│   └── components/         # 13 reusable components — see docs/component-specs.md
└── assets/
    ├── css/
    │   ├── editor-style.css    # mirrors frontend base typography in the block editor
    │   ├── page-home.css       # Home-page-only spacing, enqueued only via is_front_page()
    │   └── components/         # one CSS file per component + shared card.css/card-grid.css bases
    └── js/
        └── components/         # site-header.js (nav toggle), statistics-counter.js (count-up)
```

There is intentionally no `page.php` and no custom page templates yet, so Elementor's own
page templates (Canvas / Full Width) and existing Elementor-built pages are unaffected —
the theme adds no filters touching `theme_page_templates`, `wp_head`, `wp_footer`, or
content rendering.

### 4.2 Template hierarchy usage

The WordPress-required fallback (`index.php`), `header.php`/`footer.php`, and
`front-page.php` (the Home page — see §4.6) exist. Other page-specific templates are
deferred to future tasks per project scope.

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

13 approved reusable components live in `template-parts/components/`, each invoked as
`get_template_part( 'template-parts/components/{name}', null, $args )`. Full specs
(props/contracts, states, dependencies) are documented in
[`docs/component-specs.md`](component-specs.md) — that is the source of truth, not this
section. In brief: Header & Footer navigation structures, Hero, Section Heading,
Buttons/CTA, Content/Image Split, Service/Team/Impact Story cards (CPT-integrated),
Podcast Card and Partner Strip (generic/args-driven — no CPT yet), a Statistics counter,
a Form styling wrapper for Elementor coexistence, and a CTA Banner (added for the Home
page — §4.6). `assets/css/components/card-grid.css` is a shared responsive grid layout
utility (not a component itself) for arranging repeated cards. All component CSS uses
only `theme.json` custom properties (still the TEMPORARY placeholders from §4.4);
component JS is limited to two small, scoped, vanilla files (nav toggle, count-up
animation), both respecting `prefers-reduced-motion` where relevant.

### 4.6 Page Templates: Home (`front-page.php`)

`front-page.php` is WordPress's native template-hierarchy hook for "whatever page is
currently configured as the static front page" — it never references a specific post ID.
This is deliberate: it's how "preserve the existing front Page ID and its slug/canonical
URL" is satisfied structurally, without any code path that could create, replace, or
touch that page's `post_type`/`post_name`/`ID`. No change was made to the
`show_on_front`/`page_on_front` options as part of building this template — that's a live
Settings → Reading configuration decision, out of scope for a code change.

**Elementor coexistence:** the template checks the front page's own
`_elementor_edit_mode` post meta at render time. If it's `'builder'`, only `the_content()`
is rendered (Elementor's own content filter renders exactly as it does today, untouched).
Otherwise, the full component-based layout renders (Hero, Services/Team/Impact Stories
grids via `card-grid.css`, Statistics, Podcast teaser, Partner Strip, CTA Banner). Any
existing plain (non-Elementor) page content is still shown via `the_content()` in an
intro block, rather than discarded.

**Known limitation:** this detection logic could not be verified against real data. The
local development database has no Elementor plugin active and no page with the real
production front-page ID at all (see §6) — verify the Elementor branch actually fires
correctly against the real staging/production front page before deploying this template.

Statistics, Partner Strip, and Podcast sections on the Home page currently use literal
placeholder content (marked `TODO` inline in `front-page.php`) — no Site Settings fields,
CPT, or media exist yet for real stats, partner logos, or podcast episodes.

**Uniform section widths:** `hero.php` and `content-image-split.php` render their own
container internally (they're "self-contained section" components), independent of
whatever wraps their `get_template_part()` call. Both were fixed to render their internal
container as `.fs-container.fs-container--wide` (1200px) unconditionally, matching
CTA Banner's existing behavior — wrapping them externally in a wide container from the
calling page template has **no effect**, since their own internal markup sets its own
width regardless of any ancestor. `statistics-counter.php` and `podcast-card.php`, by
contrast, render bare with no container of their own, so the calling template must wrap
them explicitly (see the pattern in `front-page.php`). Keep this distinction in mind when
adding new page templates that use these components.

**Card grid: `auto-fit`/`minmax`, not fixed column-count breakpoints.**
`assets/css/components/card-grid.css` uses `grid-template-columns: repeat(auto-fit,
minmax(280px, 1fr))` rather than hardcoded `repeat(2, 1fr)`/`repeat(3, 1fr)` at fixed
breakpoints. This matters in practice, not just aesthetically: a fixed 3-column grid with
only 1 real post in a CPT renders that 1 card in the first column with two empty,
invisible tracks beside it — which visually reads as "broken/narrow" even though the grid
math is correct. `auto-fit` collapses unused tracks and lets existing cards stretch to
fill the row, so sparse content (the normal state while a site is still being populated)
still looks intentional. Revisit the `280px` minimum if card content ever needs more room.

### 4.7 Page Templates: About (`page-about-our-mission-vision.php`)

Spec: [`docs/page-specs/about.md`](page-specs/about.md). Implements the approved About
page: Hero, two Content/Image Split sections (Mission, Vision), a Team grid
(`fs_team_member` CPT query), and a closing CTA Banner.

Uses WordPress's native `page-{slug}.php` template hierarchy rather than
`front-page.php`'s `page_on_front`-option approach — a `page-{slug}.php` template is
always scoped to that exact page via URL routing (no "Settings → Reading" ambiguity like
the site root has), so the standard `have_posts()`/`the_post()` Loop is used directly.
Same Elementor-coexistence detection (`_elementor_edit_mode`) and the same known
limitation: Page 3726 does not exist in this local environment (confirmed via direct
database query), so this could not be verified against real data — verify against
staging/production before deploying. A local-only test page (ID 18, slug
`about-our-mission-vision`) was created directly in this environment's database purely to
QA the template visually; it is not part of the theme/plugin code and has no bearing on
the real site.

### 4.8 Page Templates: Services (`page-services.php`)

Spec: [`docs/page-specs/services.md`](page-specs/services.md), written from the task's
own explicit requirements (no separate design spec existed). Implements Hero, a dynamic
`fs_service` CPT grid (all published services, no teaser limit — every service an editor
publishes renders automatically, nothing manually duplicated), and a closing CTA Banner.
Same `page-{slug}.php` mechanism, Elementor-coexistence detection, and known
local-environment limitation (Page 1187 does not exist here) as About.

**Deep-link anchors:** `service-card.php` now always renders `id="{post_name}"` (the
service's own slug), so `/services/#{slug}` lands on that card, with `scroll-margin-top`
keeping it clear of the header — generic and slug-driven, correct for any current or
future service without a fixed list. This is a small, additive, backward-compatible
extension to the existing component (harmless everywhere else it's used, e.g. the Home
page teaser).

**`section-heading.php` gained an optional `heading_id` arg** (same kind of additive
extension) so the Services grid's `<section>` can use a real `aria-labelledby` reference
instead of a dangling one — worth reusing this pattern rather than hardcoding a heading
`id` inline wherever a section needs one.

**Empty state:** if the `fs_service` query returns zero posts, the grid section renders a
friendly message rather than an empty gap — see `page-services.css`.

### 4.9 Page Templates: Team (`page-team.php`)

Spec: [`docs/page-specs/team.md`](page-specs/team.md), written the same way as Services'
(no separate design spec existed). Implements Hero, a dynamic `fs_team_member` CPT grid
(`post_status => 'publish'` — deliberately excludes the `auto-draft` rows WordPress
creates automatically when someone starts and abandons a new post in wp-admin), and a
closing CTA Banner. Same `page-{slug}.php` mechanism, Elementor-coexistence detection,
and known local-environment limitation (Page 1198 does not exist here) as Services/About.

**Taxonomy grouping (requirement in the task) was not implemented.** `fs_team_member`
has no taxonomy registered at all (see §3.2) — there's no role/department data to group
by. Registering one would be a plugin/content-model change, not a page-template change,
and wasn't specified with enough detail to invent safely; documented as an open gap in
`docs/page-specs/team.md` §4 rather than guessed at.

**`team-card.php` gained an opt-in `show_bio` arg** (same additive-extension pattern as
the Services anchor/heading_id work) rendering a bio excerpt from the member's `editor`
content, enabled only from the Team page — the Home page teaser's existing look is
unaffected. When a member has no bio content (confirmed locally with the one real test
member, who has empty `post_content`), the block is correctly omitted rather than
rendering empty.

## 5. Plugin Architecture

_To be defined._ This section will describe:

- Plugin responsibilities and boundaries relative to the theme
- Hooks/filters registered
- Data storage (custom tables, options, post meta) decisions

## 6. Environments

**Local development** (this Local by Flywheel environment) is currently a **fresh/default
WordPress install**, not a copy of the real site:

- Only 17 total posts exist (WordPress's default sample content — "Sample Page,"
  "Privacy Policy," etc.). There is no page with the real production front-page ID
  (referenced elsewhere as Page 992), and no legacy/migrated content of any kind.
- Active plugins here are only `all-in-one-wp-migration` and `focused-schools-core`.
  **Elementor and Elementor Pro are not active in this environment**, despite being
  referenced as active on the real site in earlier audit documentation
  (`docs/audit/audit-report.md`).
- `show_on_front` is `posts` (no static front page configured), unlike the real site.

Practical effect: any work here that depends on real content, a real Elementor build, or
the real front-page ID (e.g. `front-page.php`'s Elementor-detection branch, §4.6) is
built defensively/generically and **cannot be verified end-to-end locally**. Before
deploying such work, verify it against the actual staging/production database — a
discrepancy discovered here should be treated as "this environment lacks the data to
test," not as evidence the real site differs from what was originally understood.

**Staging/production** environment specifics beyond the above are still _to be defined_.

## 7. Open Questions

- (none yet — add items here as they arise)
