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

## 9. Exact-Fidelity Pass Against `Focused Schools Team.dc.html`

This entire page (§1–§8 above) predates the literal `.dc` source files — it was built from
"the approved mockup's /#/team route" before `Focused Schools Team.dc.html` was supplied, so
it was never checked against that file directly. This pass read it in full and fixed every
real discrepancy found. `team-card.php`'s quote block + LinkedIn tile + bordered footer row
needed no change here — `Focused Schools Team.dc.html`'s own designer-handoff notes confirm
this richer treatment (not the About page's plain card) is this page's own correct design,
and `bio_modal => true` with no `compact` flag already selects it by default.

**Fixed:**

- **Closing CTA section used the wrong component.** `cta-banner.php` (a text-only,
  centered banner) doesn't match the `.dc` source's closing section at all — it's a
  white-ground, 2-column grid with real photography on the right, identical in structure to
  Home's Mission section and About's Mission Close. Swapped to `content-image-split.php`
  (same component, `image_url` => `retreat-2.jpg`, same "Two education leaders celebrating
  progress together." caption both pages already share), which also needed a new `eyebrow`
  prop this page requires ("Say hello") — added it as a small, backward-compatible addition
  to the shared component, and used it to fix the *same* missing-eyebrow gap on About's
  Mission Close ("Our mission") while touching the file.
- **Team grid used the shared auto-fit `.fs-card-grid`** instead of the `.dc` source's fixed
  3/2/1-column grid (`[data-team-grid]`: 3-up desktop, 2-up ≤1023px with 24px gap, 1-up
  ≤767px with 20px gap) — same underlying issue, and same scoped-ID-override fix, as the
  About page's `#fs-about-team-grid` (see `docs/page-specs/about.md` §16). Added
  `#fs-team-grid` with its own breakpoints, since Team's gap values (32/24/20) genuinely
  differ from About's (flat 32px throughout).
- **Intro heading was missing its mixed-weight emphasis.** The `.dc` source's "We have sat
  in the seat **you are sitting in.**" renders the first clause at 400 weight with the
  second bold — `rail-text.php` was passing/rendering the whole heading as one plain string
  through `esc_html()`. Rather than a one-off fix, extended the shared component: `heading`
  now goes through `wp_kses_post()` (so a caller can embed `<strong>`, matching the pattern
  already used by `commitment-list.php`'s heading), and added a `heading_weight` prop
  (default 700, preserving Home's/About's existing all-bold headings) since Team's base
  weight is 400. Also fixed `heading_max_ch` (15 → 18, the `.dc` source's real value for
  this specific heading) and added a `padding_bottom` prop (Team's section uses 112px, not
  the shared 120px default) — both via the same "per-caller override, shared default"
  pattern already established for `heading_max_ch`.
- **Grid-head layout and type scale were both wrong.** `.fs-team__head` used a CSS Grid
  with a 1024px breakpoint and 52px/36px heading sizes; the `.dc` source's
  `[data-grid-head]` is a flex row (`justify-content: space-between`) that only stacks at
  ≤767px, and its heading (`data-display`) follows the same universal 68px/60px/36px scale
  already established for other `data-display` headings this session. Rewrote both.
  `.fs-team__count`'s letter-spacing was also off by one decimal (0.12em → 0.1em) and it was
  missing `aria-live="polite"` (present in the `.dc` markup, needed so the "Load more"
  script's count update is announced).
- **Roster section id/anchor mismatch**: the section was `id="team-grid"` with the Hero's
  CTA pointing at `#team-grid`; the `.dc` source uses `id="roster"` (`href="#roster"` on the
  Hero CTA, and the same id in a spec-appendix note). Renamed both to match.
- **Roster section padding**: was a flat 3rem/7.5rem (48px/120px) responsive step; the `.dc`
  source's own value is asymmetric 112px/120px desktop with a 64px mobile step (this
  section carries `data-space=""`, same universal mobile rule used across Home/About).

**Known, deliberately unfixed inconsistency**: `Focused Schools Team.dc.html` uses 767px/
1023px as its "mobile"/"tablet" breakpoints, while `Focused Schools Homepage.dc.html` and
`Focused Schools About.dc.html` both use 720px/1024px for the same concepts. Page-specific
CSS in `page-team.css` (added this pass) uses Team's own 767/1023 values throughout, but
`hero.css` and `rail-text.css` are *shared* across Home/About/Team and were already fixed to
720/1024 based on the two files that agree — changing them to 767/1023 would un-fix About.
The practical effect is a ~47px range (721–767px) where a couple of shared-component
elements (e.g. the intro heading) step up to their "desktop-ish" size slightly before
Team's own page-specific elements (e.g. the grid-head heading) do. Narrow, cosmetic, and not
worth a shared-component fork for one page's 1px-different design-file value.

Verified: a 13-breakpoint sweep (320–1440px) confirmed every fixed value matches its `.dc`
target exactly (grid columns/gaps, heading sizes/weights, `<strong>` presence, section
padding) and zero overflow at any width; a 7-page regression sweep (Home, About, Services,
Team, Contact, Podcast, Impact Stories — everything touching `hero.css`, `rail-text.css`,
`content-image-split.php`, or the shared card grid) showed zero overflow and zero console
errors; `php -l` and PHPCS (`phpcs.xml.dist`) both clean on every touched PHP file; keyboard
QA confirmed the skip link and tab order still work (the bio modal's open/Escape-close/
focus-return path itself remains unverified live in this environment, same known limitation
as About — the one real team member has no bio content).
