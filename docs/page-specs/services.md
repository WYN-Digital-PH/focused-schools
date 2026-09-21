# Page Spec: Services

Status: **approved** — ready for implementation. This document did not exist prior to
this task; it was written from the task's own explicit requirements plus the established
Home/About page structure, since no separate design spec was available. Source of truth
for `page-services.php`.

## 1. Page Identity

- **Page ID:** `1187` (existing WordPress Page — must be preserved, never recreated)
- **Slug / canonical URL:** `/services/`
- **Template file:** `page-services.php` — WordPress's native `page-{slug}.php` template
  hierarchy, same "preserve via mechanism, not by referencing the ID" approach used for
  Home (`front-page.php`) and About (`page-about-our-mission-vision.php`).

## 2. Sections (in order)

1. **Hero** — page title, intro copy, primary CTA button. Data-driven: `get_the_title()`
   / `get_the_excerpt()` of the actual page, not invented copy — same pattern as
   Home/About.
2. **Services grid** — dynamic `fs_service` CPT query, all published services (no teaser
   limit), rendered via the existing `service-card.php` component inside
   `.fs-card-grid`.
3. **CTA Banner** — closing call-to-action, Site Settings-driven CTA label/URL, generic
   heading (not page-specific invented copy, since no approved closing headline exists).

## 3. Dynamic Content Sources

- **Services grid:** `WP_Query` for post_type `fs_service`, `post_status => 'publish'`,
  `posts_per_page => -1`, ordered by `menu_order` ASC. Every published service renders
  automatically — editors manage the list entirely through the CPT admin screen; nothing
  on this page is manually duplicated.
- **Hero:** actual page title/excerpt, with the same fallback-to-literal-copy pattern as
  Home/About for this local environment (where Page 1187 doesn't exist).
- **CTA Banner:** `focused_schools_get_setting( 'cta_label' )` /
  `focused_schools_get_setting( 'cta_url' )`, falling back to "Contact Us" / `#`.

## 4. Anchors

Each service card renders with `id="{service-slug}"` (the CPT post's own `post_name`),
so `/services/#{slug}` deep-links to that card. This is generic and slug-driven —
correct for any current or future service without needing a fixed list. CSS applies
`scroll-margin-top` to card anchors so the anchored card isn't hidden under the header on
jump.

## 5. Individual Service URLs

`fs_service` is registered `public => false` (see `docs/architecture.md` §3.3), so
individual service URLs already do not resolve — this requirement is satisfied
structurally, not by new template code. No redirect logic was added, since there is no
record of legacy single-service page URLs to redirect *from*; if any exist on the real
site, they need to be identified and handled separately (e.g. via the Legacy Page Bridge
pattern or standard WordPress redirects), not guessed at here.

## 6. Empty State

If the `fs_service` query returns zero posts, the grid section renders a plain, friendly
"no services listed yet" message instead of an empty grid — never a blank gap.

## 7. Legacy & Page Preservation

Same approach as Home/About: no code references Page ID 1187 directly; the template
applies via slug match only. Elementor coexistence uses the same
`_elementor_edit_mode === 'builder'` runtime detection, falling back to filtered
`the_content()` only when true. **Known limitation:** Page 1187 does not exist in this
local environment (confirmed via direct database query), so this could not be verified
against real data — verify against staging/production before deploying.

## 8. Container Widths

All sections use the uniform wide container (`.fs-container.fs-container--wide`,
1200px), matching the Home/About fix.
