# Page Spec: Impact Stories

Status: **approved** — ready for implementation. Written from the task's own explicit
requirements plus the established page structure, since no separate design spec was
available. Source of truth for `page-impact-stories.php` and
`single-fs_impact_story.php`.

## 1. Page Identity

- **Landing page ID:** `3785` (existing WordPress Page — must be preserved, never
  recreated)
- **Landing page slug / canonical URL:** `/impact-stories/`
- **Landing template file:** `page-impact-stories.php` — WordPress's native
  `page-{slug}.php` hierarchy, same mechanism as Home/About/Services/Team.
- **CPT single template:** `single-fs_impact_story.php` — WordPress's native
  `single-{post_type}.php` hierarchy, renders individual `fs_impact_story` posts at
  `/impact-stories/{slug}/` (per `docs/architecture.md` §3.4's rewrite configuration).

## 2. Landing Page Sections (in order)

1. **Hero** — page title, intro copy, primary CTA button. Data-driven, not invented
   copy.
2. **Unified stories grid** — see §3.
3. **CTA Banner** — closing call-to-action, Site Settings-driven.

## 3. Unified Dynamic Content Query

Two separate queries, merged and sorted in PHP (a single `WP_Query` can't apply a
`meta_query` to only one post type within a multi-post-type query):

1. `fs_impact_story`, `post_status => 'publish'`, `posts_per_page => -1`.
2. `page`, `post_status => 'publish'`, `posts_per_page => -1`, `meta_key =>
   '_fs_legacy_impact_story'`, `meta_value => '1'` — the exact meta key the Legacy Page
   Bridge WP-CLI command (`docs/migration-qa-rules.md` §9) tags approved legacy Pages
   with. This page is that command's intended consumer.

**Sort order:** featured `fs_impact_story` posts first (by `post_date` DESC within that
group), then every remaining item (CPT and legacy Page alike) by `post_date` DESC — the
only field both post types share in common. This is a stated design decision, not
something separately specified.

Both are rendered through the existing `impact-story-card.php` component unchanged —
it already works correctly for both post types (every function it calls —
`get_the_title()`, `get_permalink()`, `has_post_thumbnail()`, `get_the_excerpt()` — is
post-type-agnostic; `_fs_impact_story_*` meta lookups simply return empty for a Page,
which correctly omits the district/state/year line and the Featured badge rather than
showing anything broken).

**New CPT entries populate automatically** — no manual page editing required, by
construction (the query is dynamic).

## 4. Legacy Preservation

- No code path here alters a legacy Page's `post_type`, `post_name`/slug, content, or
  `_yoast_wpseo_*` (or any other) metadata — this page only *reads* legacy Pages via
  `WP_Query` and links to their real, existing `get_permalink()`.
- No legacy content is bulk-migrated or converted into the CPT.
- The landing page itself (3785) is targeted only by slug match, never by ID.

## 5. Yoast SEO Compatibility

Yoast is not installed in this local development environment, so this could not be
verified live — verify against staging/production. Structural compatibility is
preserved by construction:

- The landing page (3785) never has its own title/content/meta touched — Yoast's
  per-page SEO fields for that Page are completely independent of the grid content added
  by this template.
- `single-fs_impact_story.php` uses the standard Loop and relies on `wp_head()` (already
  called in `header.php`) plus `title-tag` theme support (already registered) rather
  than any custom `<title>`/meta output — this is what Yoast's own hooks integrate with.

## 6. Edge Cases

- **Missing featured image:** `impact-story-card.php` already omits the media block
  entirely via `has_post_thumbnail()` (existing behavior, unchanged).
- **Long titles / variable excerpt lengths:** no fixed height or truncation is applied
  anywhere in `card.css` — text wraps naturally, so cards grow as needed rather than
  overflowing or clipping awkwardly.
- **Non-featured items:** the "Featured" badge is already conditional (existing
  behavior) — most items, and all legacy Pages, simply won't show it.
- **Empty grid:** friendly message instead of a blank gap, same pattern as
  Services/Team.

## 7. Container Widths

All sections use the uniform wide container (`.fs-container.fs-container--wide`,
1200px), matching the established pattern.

## Implementation Notes (build against the approved mockup)

Built from the mockup folder's own `impact-stories.md`. Section order, card hierarchy,
degradation rules, pagination and the empty state follow that spec.

### Components

- **`impact-story-card.php` (rewritten)** — one presentation for both sources. 16:9 ratio box
  so rows never jag, year badge, place line with a source dot, 3-line clamps on title and
  excerpt, footer pinned with `margin-top: auto`, and a Story/Legacy pill.
- **`story-filters.php` (new)** — the filter bar. 120px label rail + chip area, one row per
  taxonomy, `role="group"` with an accessible name, chips clearing 44px.
- **`story-spotlight.php` (new)** — the featured split, full-bleed teal.

### Degradation, as built

A legacy Page with no photograph gets the teal mark tile at 12% rather than a broken frame;
its year badge is **omitted entirely** (no "Undated"); its state is omitted so the place line
shortens; its auto-excerpt is trimmed to 180 characters on a word boundary. The dot and pill
mark the source. As a Page is backfilled and converted, it gains a badge and drops the pill
with no template change.

### Two deviations from the spec, both deliberate

1. **`story_year`, not `year`, in the URL.** `year` is a reserved WordPress query var for
   date archives — `?year=2025` on a Page URL resolves as a date archive and returns **404**.
   Confirmed in testing before the rename. `state` is not reserved and is used as specified.
2. **Filter chips are built from meta values, not taxonomy terms.** The CPT stores state and
   year as post meta; the spec assumes taxonomies. Chips are still generated only from values
   that exist on published stories, so the contract — never offer an option that returns
   nothing — holds. Moving to real taxonomies would be a content-model change needing
   approval.

### Filtering behaviour

Server-rendered on first paint: every chip is a real link carrying the filter in the URL, so
a filtered view is shareable, back-button safe, and works with JavaScript off. A narrowing
filter correctly excludes legacy Pages, which carry no structured state or year; the empty
state says so in plain language rather than showing a dead grid.

### QA performed

16 stories (13 new + 3 legacy) seeded to exercise real behaviour.

| Check | Result |
|---|---|
| Grid mixes both sources | 16 cards, 3 legacy |
| Year badges | 13 — new records only |
| Source pills | 16, all cards |
| Dot colour | cerulean for new, gray for legacy |
| Spotlight | present, newest Featured only |
| Filters | `state=Illinois` → 3; `story_year=2025` → 3; `Vermont+2021` → 1 |
| No-match combination | empty-state panel, not a dead grid |
| Load more | 9 → 16, count "Showing 9 of 16" → "Showing 16 of 16", button hides |
| Card geometry | 16:9 media, 3-line clamp |
| New story URLs | `/impact-stories/{slug}/` → 200 |
| Legacy URLs and post types | unchanged, all 200, still `page` |
| Console / PHPCS | zero errors, full project clean |

**Not verified:** tablet and mobile widths were not rendered — the browser in this
environment will not resize, so the responsive rules are written but unseen. Production
Pages 3785 and the real legacy Stories do not exist locally, so the merged grid has only
been proven against seeded fixtures.

## Single Story Template

Built from the mockup's `impact-story-single.md`. Narrative order is what happened → what
changed → how: breadcrumb, hero with meta chips, At a glance, story body beside a sticky
detail rail, related stories.

### Content model additions

Three fields were needed and added to `FocusedSchoolsCore\Modules\Impact_Stories\Meta`:

| Field | Type | Purpose |
|---|---|---|
| Service Lane | text | Detail rail row; also matches related stories |
| Partnership Length | text | Detail rail row |
| At a Glance Results | textarea | One per line, `value \| unit \| label`, capped at four |

`Meta::results_list()` parses the results; the template duplicates that split so it degrades
rather than fatals with the plugin off. The metabox gained textarea and description support,
which it previously lacked.

### Degradation, verified

| Story | Glance | Rail rows | Prose | Chips |
|---|---|---|---|---|
| Full metadata | shown, 3 results | 5 | 66ch | 3 |
| One detail only | omitted | rail dropped | 72ch wide | 1 |
| No metadata at all | omitted | rail dropped | 72ch wide | 0 |

A missing featured image leaves the slab on a plain teal band at the same height — never a
stretched upscale. Related stories match state → service lane → recency, always excluding the
current story, and the section is omitted when nothing matches.

Each result carries a full-sentence `aria-label` because the values are abbreviated:
`"400+ Educators Coached across eight campuses"`. The numerals never animate.

### A bug this surfaced

The Impact Stories meta class had **no `textarea` sanitizer**, so the new multi-line results
field fell through to `sanitize_text_field` and every newline was silently stripped — three
results collapsed into one. Added `'textarea' => 'sanitize_textarea_field'` to the map.
Worth knowing because any future multi-line field on this CPT would have hit the same thing.

### Legacy

Legacy Pages keep their own root-level URLs, post type, slugs and content. Nothing in this
work writes to them; the diff contains no `wp_update_post`, `set_post_type` or `post_name`
change. They appear in the listing only through the existing additive
`_fs_legacy_impact_story` flag.

**Not built:** the spec's optional `page-impact-story-legacy.php`, which would give legacy
Pages the new breadcrumb and Related grid on their own URLs. It needs either a template
assignment written to each Page or a `template_include` filter; neither was in scope here,
and legacy singles currently render through the normal page template.

### QA performed

23 records seeded (16 new + 7 legacy), including a legacy Page with a 160-character title, a
story with no image/state/year/results, and a story with a single detail value.

Landing: 23 cards, 7 legacy, count "Showing 9 of 23". Singles: all URLs 200 at
`/impact-stories/{slug}/`; all seven legacy URLs still 200 at root level as `page`. Rail
sticky at `top: 160px`, body grid `300px / 908px`, results 3-up, one `h1`, zero interactive
elements without an accessible name, no broken images, no horizontal overflow, zero console
errors, no new PHP log entries, full-project PHPCS clean.

**Not verified:** tablet and mobile were not rendered — the browser in this environment will
not resize.
