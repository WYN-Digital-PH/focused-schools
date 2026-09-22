# Page Spec: Team

Status: **approved** — ready for implementation. This document did not exist prior to
this task; it was written from the task's own explicit requirements plus the established
Home/About/Services page structure, since no separate design spec was available. Source
of truth for `page-team.php`.

## 1. Page Identity

- **Page ID:** `1198` (existing WordPress Page — must be preserved, never recreated)
- **Slug / canonical URL:** `/team/`
- **Template file:** `page-team.php` — WordPress's native `page-{slug}.php` template
  hierarchy, same mechanism as Home/About/Services.

## 2. Sections (in order)

1. **Hero** — page title, intro copy, primary CTA button. Data-driven
   (`get_the_title()`/`get_the_excerpt()`), not invented copy.
2. **Team grid** — dynamic `fs_team_member` CPT query, all published members, rendered
   via `team-card.php` (extended with `show_bio => true`) inside `.fs-card-grid`.
3. **CTA Banner** — closing call-to-action, Site Settings-driven.

## 3. Dynamic Content Sources

- **Team grid:** `WP_Query` for post_type `fs_team_member`, `post_status => 'publish'`
  (excludes `auto-draft` rows WordPress creates automatically), `posts_per_page => -1`,
  ordered by `menu_order` ASC. Every published member renders automatically.
- **Hero / CTA Banner:** same pattern as Services.

## 4. Taxonomy / Grouping

**Not implemented.** `fs_team_member` has no taxonomy registered (see
`docs/architecture.md` §3.2) — there is no role/department data to group or filter by.
Adding one would be a plugin/content-model change (a new taxonomy registration), not a
page-template change, and wasn't requested with enough specificity to invent safely. If
grouping is genuinely needed, it should be scoped as its own task defining the taxonomy
(or reusing the existing free-text `position` meta field, which isn't structured for
filtering).

## 5. Edge Cases

- **Missing photo:** card omits the media block entirely (existing `has_post_thumbnail()`
  check) rather than showing a broken image.
- **Missing/short bio:** `team-card.php` shows a bio excerpt (`get_the_excerpt()` of the
  member's `editor` content) only on this page (`show_bio` arg, opt-in); the block is
  omitted entirely when there's no bio content, rather than showing an empty excerpt.
- **Variable name/title length:** handled by CSS (card body already stacks vertically
  with no fixed-height truncation), not a markup concern.
- **Empty grid:** friendly "no team members listed yet" message instead of a blank gap.

## 6. Legacy & Page Preservation

Same approach as Services: no code references Page ID 1198 directly; the template
applies via slug match only. Elementor coexistence uses the same
`_elementor_edit_mode === 'builder'` runtime detection. **Known limitation:** Page 1198
does not exist in this local environment (confirmed via direct database query) — verify
against staging/production before deploying.

## 7. Container Widths

All sections use the uniform shell container (`.fs-container.fs-container--shell`,
1400px) — see §8.

## 8. Precision Pass: Container Width + Card Language Alignment

Same fix, same cause, as `docs/page-specs/services.md` §9 (read that for the full
account) — this page shares the same drift since both were built before the `.dc`-source
token pass and never revisited: the services-grid-equivalent team-grid section was still
`.fs-container--wide` (1200px) against the hero's now-1400px shell, and `card.css`'s base
geometry was still pre-rebrand scaffolding. Both fixed identically. `team-card.css`
itself needed no change — it was already using current tokens (`ink-soft`, `contrast`,
`accent`) and its 4:5 portrait `aspect-ratio` was already rendering correctly (verified
via bounding-box measurement: 378×472.5px, an exact 4:5 match), unlike some Home-page
image contexts fixed in an earlier pass — this one sits in a CSS Grid track with a
definite width, which is what that fix depended on.
