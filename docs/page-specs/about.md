# Page Spec: About (Our Mission & Vision)

Status: **approved (2026 rebrand)** — ready for implementation. Do not redesign; this
document is the source of truth for `page-about-our-mission-vision.php`. Design
reference: `https://focused-schools-full-site-2f37f011.vercel.app/#/about` (the
originally-given hash `#/about-our-mission-vision/` is not a route this SPA
recognizes — it silently falls back to the Home route's content; `#/about` is the real
route, confirmed via the app's own nav links and footer links). This spec supersedes the
previous version, which was written from generic placeholder copy before this real
design reference was available.

## 1. Page Identity

- **Page ID:** `3726` (existing WordPress Page — must be preserved, never recreated).
  Does not exist in this local environment (verified via direct database query) — the
  local page with this slug is ID 18, unrelated to the code, which never references
  either ID.
- **Slug / canonical URL:** `/about-our-mission-vision/`
- **Title:** "About Our Mission & Vision"

## 2. Architecture Decision: page-about-our-mission-vision.php, unchanged mechanism

Unlike the Home page task, there was no competing-template conflict here —
`page-about-our-mission-vision.php` already existed and already correctly targets Page
3726 via WordPress's native `page-{slug}.php` hierarchy (no ID ever referenced). This
task rebuilt its content/sections in place; the file identity and preservation mechanism
are unchanged.

## 3. Sections (in order) and Component Mapping

| # | Section | Component | Notes |
|---|---|---|---|
| 1 | Hero | `hero.php` (extended) | Added optional `eyebrow` and `image_url`/`image_alt` props (additive, backward-compatible — every other caller: Services/Team/Podcast/Contact/Thanks/Impact-Stories, is unaffected and was spot-checked live). |
| 2 | Who We Are | `rail-text.php` (new) | Icon + eyebrow rail label beside heading/body/CTA, no photo. Same pattern as the Home page's Beliefs section — confirmed via the reference's own shared `fs-rail`/`fs-rail__label` classes, so this is a real shared design-system pattern, not a one-off. Generic/args-driven, no CPT. |
| 3 | What Guides Us (Mission/Values/Approach) | `commitment-list.php` (existing, unchanged) | Built for the Home page; its contract (eyebrow/heading/intro/photo/numbered items) maps onto this section exactly — zero changes needed. |
| 4 | Impact Stats | `statistics-counter.php` (existing, unchanged) + `section-heading`-style header composed directly in the template | **Identical figures to the Home page** (2M+ students / 20+ years / 25+ states) — real, current numbers, not a placeholder. Wrapped in a dark (house-teal) band (`page-about.css`'s `.fs-about__stats-band`) with an eyebrow/heading/CTA header above it. |
| 5 | Partner Districts | `partner-districts.php` (new) | State-grouped district chip list + Chamber of Commerce badge + MA DESE approved-provider note + closing CTA. Generic/args-driven, real approved content (5 states, real district names) hardcoded in the template — page-specific content, not global business info, same treatment as the Home page's stats numbers. |
| 6 | Meet Our Team | `team-card.php` (extended) + `team-bio-modal.php`/`.js` (new) | Real `fs_team_member` `WP_Query` (not the reference's literal "Name Surname" placeholder people — those are themselves design placeholders, not real content to copy). "Read bio" opens the member's full `editor` content in a shared modal dialog — see §5 below. "Load more" reveals cards beyond the first 6 (see §6). |
| 7 | Mission Close | `content-image-split.php` (extended) | Added an optional second CTA (`cta2_label`/`cta2_url`) — this section has two buttons ("Get to Know Us" + "Explore Our Services"), unlike every other `content-image-split.php` usage which has one. Additive/backward-compatible; Home page's Mission section (single CTA) is unaffected. |

Any additional Gutenberg/native content an editor adds directly to Page 3726's content
editor still renders in a reading-width block between the Hero and "Who We Are" (same
"preserve existing plain content" pattern used throughout this project).

## 4. Copy / Content

All copy below is taken verbatim from the approved design reference (not paraphrased),
except where noted as an adaptation:

- **Hero eyebrow:** "About Focused Schools". **Heading:** sourced from the page's own
  title (`get_the_title()`), falling back to "From the boardroom to the classroom, every
  educator has a cape that they wear." **Subheading:** sourced from the page's excerpt
  (`get_the_excerpt()`), falling back to the reference's intro copy. **CTA:** "Meet Our
  Team" → `/team/`.
- **Who We Are:** heading "Every student, every classroom, every day — no exceptions.",
  two paragraphs (partnership history + scale), CTA "Explore Our Services" → `/services/`.
- **What Guides Us:** heading "How we work **matters as much as the outcomes.**"
  (emphasis via `sprintf()`, not embedded markup in the translatable string — matches the
  Home page's established i18n pattern), intro line, 3 items (Our Mission / Our Values /
  Our Approach) each with its own CTA.
- **Impact Stats:** eyebrow "Two decades of partnership", heading "The measure of our
  work is what changed because of it.", CTA "View Impact Stories" → `/impact-stories/`,
  same 3 stats as Home.
- **Partner Districts:** eyebrow "Our partners", heading "We're proud to partner with
  **these districts.**", intro naming the 2024–2025 school year and 5 states, full
  district list per state (California/Connecticut/Illinois/Massachusetts/Vermont), MA
  DESE approved-provider note, closing line "Let's put you on the map." + CTA "Let's
  Talk" → `/contact/`.
- **Meet Our Team:** eyebrow "Meet our team", heading "We're educators first. That's
  what makes us different.", intro paragraph, "See the full team" link → `/team/`.
- **Mission Close:** heading "Support the educators who shape students' lives.", body
  paragraph, two CTAs ("Get to Know Us" → `/contact/`, "Explore Our Services" →
  `/services/`).

## 5. Team Bio Modal

`team-card.php` gained an opt-in `bio_modal` prop (default `false` — page-team.php's
existing `show_bio` usage is untouched). When `bio_modal` is true and a member has
non-empty `post_content`, a "Read bio" button renders alongside a hidden
`<template data-fs-bio-content>` holding that member's full bio (name, position, full
`post_content` run through `wpautop()`/`wp_kses_post()`). `team-bio-modal.php` (included
once per page) is the shared, empty dialog shell; `assets/js/components/team-bio-modal.js`
populates it from the clicked card's `<template>` on click — same
open/close/focus/scroll-lock pattern already established by the Home page's video modal
(`home-hero.js`), reusing the same `fs-modal-open` body class.

**Verified live:** the one real `fs_team_member` post in this environment (Kerry
Purcell) has no `post_content` set, so no "Read bio" button rendered — correctly proving
the opt-in, empty-bio-hides-the-button behavior. The modal's open/close interaction
itself could not be exercised live in this environment for lack of a member with a bio
to click — its code path is structurally identical to the already-verified video modal,
but this specific interaction is unverified pending real bio content.

## 6. Team "Load More"

The first 6 team members render normally; any beyond that render with a real `hidden`
attribute. A "Load more" button (`data-fs-team-load-more`, `aria-controls` pointing at
the grid's `id`) appears only when there are more than 6 — `team-load-more.js` removes
the `hidden` attribute from every remaining card on click and hides the button. Without
JS, extra cards simply stay hidden (matching the reference's own click-to-reveal
behavior — not a functionality loss). **Verified live:** only 1 real team member exists
in this environment, so the button correctly did not render (count ≤ 6) — the
reveal-on-click path itself is unverified pending more than 6 real team members.

## 7. Images

All `assets/img/*.jpg`/`.svg` references are placeholder paths
(`get_template_directory_uri() . '/assets/img/...'`), reusing the same filenames as the
Home page's placeholders (`retreat-1.jpg`, `retreat-3.jpg`, `retreat-2.jpg`,
`chamber-badge.jpg`) since both designs share one photo set, plus one new
`mark-icon.svg` for the rail-text label icon. No binary image files were added. Each
`<img>` 404s harmlessly until real files are dropped into those paths — confirmed live
(only these exact paths fail, nothing else).

## 8. Legacy & Page Preservation

- **Template file:** `page-about-our-mission-vision.php` — unchanged mechanism, see §2.
- **Elementor coexistence:** unchanged — `_elementor_edit_mode === 'builder'` on the
  queried page renders only its filtered `the_content()` output; otherwise the seven
  structured sections render.
- **Known limitation:** identical to the Home page — Page 3726 does not exist in this
  local environment, so the Elementor-detection branch could not be verified against
  real data. Verify against staging/production before deploying.
- **Container widths:** all sections use the uniform wide container
  (`.fs-container.fs-container--wide`, 1200px), matching the Home page.

## 9. QA Performed

Desktop (1280px), tablet (834px), and mobile (390px) viewports screenshotted and
reviewed — layout reflows correctly at every width, no horizontal overflow. Keyboard QA:
Tab lands on the skip link first; the mobile nav toggle opens via Enter, closes via
Escape (`aria-expanded` toggles correctly), and focus returns to the toggle button on
close. Console/network: zero JavaScript errors; the only failed requests are the
documented placeholder-image 404s (§7). PHP: full-project `php -l` and PHPCS both clean,
zero new PHP error-log entries. Regression check: Home, Team, Services, Podcast, and
Contact pages (all consumers of the 3 extended shared components) spot-checked live —
all HTTP 200, Team page (heaviest user of `team-card.php`) confirmed zero console errors.

## 10. Precision Pass Against the `.dc` Design-Comp Source Files

A later task supplied `Focused Schools About.dc.html` (plus the shared
`Design System v1.dc.html` and the Home page's `.dc` file) as the exact source of truth,
superseding the "full site" SPA capture used for §1–§9 above — see
`docs/page-specs/home.md` §11 for the full account of the token/component-contract changes
this pass made site-wide (button contract, header/footer rewrite, `hero.php` overlap
pattern, no-scroll-jacking reaffirmation). Two changes are specific to this page:

**Partner Districts became genuinely dynamic.** §3 above describes the original build as
"real approved content ... hardcoded in the template." The About `.dc` file's own
"Designer handoff" developer notes explicitly name a `fs_partner` CPT and a `state`
taxonomy as the intended mechanism — so the hardcoded 5-state array was replaced with a
real `fs_partner` CPT (title + page-attributes only, admin-only, no frontend URL — same
pattern as Team/Services/Testimonials) and a hierarchical `fs_partner_state` taxonomy
(`wp-content/plugins/focused-schools-core/modules/partners/class-partners.php`).
`page-about-our-mission-vision.php` now builds its state groups from `get_terms()` +
a per-term `WP_Query`, with a graceful empty-state message
("Our partner district directory is being populated...") verified live — this environment
has no seed data for the new CPT yet.

**Impact Stats moved to Site Settings, shared with Home.** The same developer notes state
"Stat values are shared with the homepage — store them once ... so the two pages cannot
drift." Added `impact_students`/`impact_years`/`impact_states` fields (Site Settings →
new "Impact Stats" section, `class-fields.php`/`class-site-settings.php`) with the
existing real figures (2/20/25) as defaults; both this page and `front-page.php` now read
`focused_schools_get_setting('impact_students', ...)` etc. instead of each hardcoding its
own copy of the numbers, closing the drift risk the notes called out.

Re-verified after this pass: `php -l` + PHPCS clean across every touched/new file
(including the new `partners` module), desktop/tablet/mobile screenshots reviewed again,
zero unexpected console errors (only the same documented placeholder-image 404s).

## 11. Exact-Fidelity Pass Against the `.dc` Source

A later task ("Implement approved About page (exact design match)") re-audited every
section's typography/spacing/structure against the literal pixel values in
`Focused Schools About.dc.html`, after the client reported earlier passes didn't match
closely enough. Found a systemic pattern — several section headings were using
significantly smaller sizes than the `.dc` source specifies, evidently built against an
approximate scale rather than the literal numbers — plus a few smaller gaps. **Fixed:**

- **Rail heading max-width and paragraph sizing** (`rail-text.php`/`.css`, shared with
  Home): the component now accepts a `heading_max_ch` prop, since Home and About specify
  different values (13ch / 15ch) for the same shared heading — previously hardcoded to
  Home's value only. The second-and-later body paragraph now renders at 17px vs. the
  first paragraph's 19px, per the `.dc` source (was uniform).
- **Commitment List heading** (`commitment-list.php`/`.css`, shared with Home): was
  missing the ≥1240px breakpoint entirely, so the heading stayed at 60px instead of
  scaling to 84px on desktop — affected Home too, now fixed for both. Same
  `heading_max_ch` pattern added (9ch Home / 11ch About).
- **Partner Districts heading**: 40px → 68px at desktop (was never given a responsive
  step-up).
- **Impact Stats band** (`page-about.css`): heading 36px → 58px; section padding 48px →
  120px vertical (`--wp--preset--spacing--section`); header margin-bottom 48px → 72px;
  added the `position:relative; overflow:hidden` + faint background mark watermark
  (`mark-white.svg`, right:-60px/top:40px/height:420px/opacity:.06) that this section has
  in the source and previously lacked entirely.
- **Mission Close** (`content-image-split.php`/`.css` — used only by this page):
  previously still generic pre-rebrand scaffolding (a 50/50 flex split, `--wide` 1200px
  container, theme.json's generic `x-large` heading token). Rewritten to the exact spec:
  `--shell` 1400px container, `1fr / 460px` grid with 100px gap, 68px heading (max-width
  15ch), 17px body (max-width 62ch), and the same reserved-space image-wrapper pattern
  used elsewhere (aspect-ratio 4:5 on the wrapper, `position:absolute` fill on the `img`)
  so a missing photo degrades to a solid placeholder block instead of collapsing.
- **Icon filename bug**: the "Who We Are" rail icon (and Home's equivalent "What We
  Believe" rail) was requesting `mark-icon.svg`, a filename that appears nowhere in
  either `.dc` source — the source uses `mark-1.svg`. Fixed on both pages.
- **Team grid pagination**: was capped at 6 visible cards before "Load more"; the `.dc`
  source's own spec says "Cards paginate at nine." Fixed to 9.

**Deliberate, noted deviation:** the `.dc` source renders several images (guide/close
photos) as `<div role="img" aria-label="...">` with a CSS background-image, not a native
`<img>`. Kept real `<img>` elements with proper `alt` text instead — a native image with
alt text is the more broadly-supported accessible pattern, and the visual result
(aspect-ratio, object-fit: cover, rounded corners, shadow) is identical either way.

Re-verified: every changed value confirmed via computed-style/bounding-box measurement
(not just visual screenshot review) — e.g. rail heading 84px/887px max-width, commitment
heading 84px/632px max-width, Mission Close image exactly 460×575px (4:5). Desktop/
tablet/mobile screenshots reviewed clean, no overflow at any width. Home page spot-
checked for regression on the two shared components (`rail-text`, `commitment-list`) —
confirmed its own values (13ch/9ch, 84px desktop heading) are preserved as the defaults.
`php -l` + PHPCS clean, zero new PHP error-log entries.

Real binary assets (photography, marks, decorative shapes) were fetched from the approved
mockup source in a follow-up step and now live at the theme-relative paths every
component already expected — zero placeholder-image 404s remain on Home or About.
`inc/setup.php` also gained `add_theme_support('custom-logo')`; `site-header.php`/
`site-footer.php` render a real logo image when one is set via Site Identity, falling
back to the site title text (identical to prior behavior) until then — the downloaded
`full-logo.svg` is staged at `assets/img/full-logo.svg` but not yet activated, since doing
so means writing into `wp-content/uploads/`, which `AGENTS.md` restricts without explicit
confirmation.

## 12. Second Fidelity Pass: "Meet Our Team" Layout + Hero Copy

A follow-up review caught two more gaps the first fidelity pass missed:

- **"Meet Our Team" header** was using `section-heading.php` — a generic, single-column,
  stacked component (correct for Services/Team pages) — instead of the `.dc` source's
  2-column grid (heading left, intro paragraph right) with a mixed-weight heading
  ("We're educators first." regular + "That's what makes us different." bold), the same
  pattern already correctly built for "What Guides Us" and "Partner Districts" on this
  same page. `section-heading.php` itself was left unchanged (it's correct where it's
  used elsewhere); replaced with bespoke markup + a new `.fs-about__team-intro` block in
  `page-about.css`, matching the established sibling pattern. Section padding was also
  wrong (48px, should be 120px like every other main section) — fixed via a new
  `.fs-about__team` class.
- **Hero copy**: `get_the_title()`/`get_the_excerpt()` were silently overriding the `.dc`
  source's literal hero copy whenever the real WordPress page had a title/excerpt set
  (which it does) — so the on-page H1 never actually showed the approved
  "From the boardroom to the classroom..." headline. **Explicitly confirmed with the
  project owner**: the Hero always renders the `.dc` source's literal copy now,
  regardless of the page's title/excerpt field. This does not touch the `<title>` tag —
  that's still driven independently by the real page title via `add_theme_support(
  'title-tag')`, verified live (page `<title>` still reads "About Our Mission & Vision",
  on-page H1 now reads the mockup's copy).
- **Hero eyebrow color**: the `.dc` source specifies lime (`#a7cc14`) for this specific
  eyebrow, not the shared `.fs-eyebrow--on-dark` default (gold) other dark-ground
  eyebrows on the site correctly use. Scoped an override to `.fs-hero__slab
  .fs-eyebrow--on-dark` in `hero.css` rather than changing the shared class (which other
  contexts still need at gold).

## 13. Responsive Fidelity Pass Against `Focused Schools About.dc.html`'s Literal Breakpoints

Re-read `Design System v1.dc.html` in full first (per explicit request), then re-read the
About `.dc` file's embedded `<style>` block line-by-line to extract its exact `max-width`
breakpoint rules (1240/1024/720px), and audited every current WP value against them via
live `getComputedStyle` checks across 320–1440px. Found the six `data-space`-marked main
sections (Who We Are, What Guides Us, Impact Stats, Partner Districts, Meet Our Team,
Mission Close — Hero is not one of these, it has its own breakpoint treatment) were all
flat ~120px top/bottom padding at every width, with no mobile step-down at all, plus
several smaller gap/breakpoint mismatches. **Fixed:**

- **Mobile section padding (≤720px)**: added `padding-block: 4rem` (64px, matches the
  `.dc` source's `[data-space]` rule exactly) to all six sections — `rail-text.css`
  (`.fs-rail-text`), `commitment-list.css` (`.fs-commitments`), `page-about.css`
  (`.fs-about__stats-band`, `.fs-about__team`), `partner-districts.css`
  (`.fs-partner-districts`), and `content-image-split.css` (`.fs-content-split`).
- **Global shell padding at ≤720px**: `.fs-container`'s responsive padding scale in
  `style.css` jumped straight from 1rem to 2rem at the 640px breakpoint, giving 32px at
  720px width — the `.dc` source (Home's and About's files independently agree) specifies
  20px at ≤720px. Added a `@media(max-width:720px){padding-inline:1.25rem}` override.
  Site-wide fix (affects every page), verified via a 5-page regression sweep (Home/About/
  Services/Team/Contact at 320–1440px) — zero overflow anywhere.
- **Hero slab breakpoint**: the absolute-overlap desktop treatment was triggering at
  `min-width:1024px`; the `.dc` source's cutoff is 1241px (`max-width:1240px` for the
  static state). Rewrote `hero.css`'s base `.fs-hero__slab` rule to the `.dc` source's
  721–1240px static values (`margin:-2.8125rem 0 0` i.e. -45px, `width:auto`,
  `max-width:none`, `padding:2.5rem` i.e. 40px — dropping the previous ad hoc `width:90%;
  max-width:560px` values, which also turned out to be silently leaking into the already-
  correct ≤720px override, since that block never set its own width/max-width) and moved
  the absolute-overlap rule to `@media(min-width:1241px)`. `position:relative` (not the
  source's literal `static`) is kept deliberately — the slab needs a stacking context for
  its `z-index`, and produces an identical visual result with no offsets set.
- **Commitment List gaps**: `.fs-commitments__intro` gap 32px→44px and
  `.fs-commitments__body` gap 40px→56px below 1240px (matching `[data-intro]`/
  `[data-guide-body]`); `.fs-commitments__photo` gained `max-width:430px` (was uncapped,
  matching `[data-guide-media]`); `.fs-commitments__row` gained a
  `@media(max-width:1024px)` split from its flat 34px gap to `row-gap:14px;
  column-gap:24px` (matching `[data-srow]`).
- **Partner Districts row gap**: `.fs-partner-districts__row` base gap 16px→48px below
  1024px (matching `[data-map-grid]`) — not live-verifiable in this environment since no
  `fs_partner` terms/posts are seeded yet (see §10), so the row markup doesn't render;
  fixed from direct source comparison instead.
- **Content/Image Split gap**: `.fs-content-split__inner` gap 40px→48px below 1024px
  (matching `[data-cta-grid]`).

Verified via a scripted multi-width sweep (13 breakpoints, 320–1440px) reading live
`getComputedStyle` values for every changed property plus `scrollWidth` vs `innerWidth` —
all values matched their `.dc`-specified targets exactly at every width, zero overflow
anywhere. Regression-swept Home, Services, Team, and Contact (all affected by the global
container-padding fix; Home and Contact/Services/Team also share `hero.css`) — zero
overflow, zero console errors on any page.

## 14. "Meet Our Team" Grid + Heading Fidelity, and a Footer Tablet-Grid Bug

The client reported the "Meet Our Team" section still didn't match. Re-reading the `.dc`
source's team-grid markup (`[data-team-grid]`) found it uses a fixed 3-column grid
(`repeat(3, minmax(0,1fr))`, 2-up ≤1024px, 1-up ≤720px), not the shared `.fs-card-grid`
utility's `repeat(auto-fit, minmax(280px,1fr))` used elsewhere on the site — the `.dc`
source's own designer-handoff note is explicit: "a trailing row of one or two cards is
left-aligned, never stretched," which only a fixed column count produces. Scoped a
`#fs-about-team-grid` override in `page-about.css` (fixed 3/2/1-up columns,
`justify-items:stretch`, no per-card `max-width` cap) rather than changing the shared
`.fs-card-grid` class, since Services/Impact-Story/Podcast grids elsewhere still use the
auto-fit behavior and weren't in scope. Verified live and via screenshot at 1440/900/320px
— the one real team member now sits left-aligned at natural column width instead of
centered and width-capped, matching the source's stated intent.

This also surfaced a **systemic breakpoint bug**: every `data-display`-tagged heading on
this page is supposed to follow one shared 3-tier scale (own literal desktop size ≥1241px
→ 60px at 721–1240px → 36px at ≤720px, per the `.dc` source's `[data-display]` rule, applied
uniformly regardless of each heading's own desktop size) and every `data-intro`-tagged
2-column header (heading + paragraph) is supposed to collapse to 1 column with a 44px gap
below 1241px. The first fidelity pass (§11) only applied this to Rail Text and Commitment
List; Partner Districts, "Meet Our Team," Impact Stats, and Mission Close were still using
ad hoc sizes and the wrong breakpoint (1024px instead of 1241px) — so "Meet Our Team"'s
heading was 44px at tablet widths and 44px at mobile (larger than the target 36px), instead
of the correct 68px → 60px → 36px steps. Fixed all four:

- `.fs-partner-districts__intro` gained the missing 2-column desktop state (was single-column
  at every width) and correct base gap (44px); its heading now steps 68px → 60px → 36px
  instead of a flat 40px below 1024px with no mobile step at all.
- `.fs-about__team-intro` (`page-about.css`): gap corrected to 44px, heading now steps
  68px → 60px → 36px instead of 44px → 68px with no mobile step.
- `.fs-about__stats-head h2`: gained the missing 60px/36px steps (was flat 58px at every
  width).
- `.fs-content-split__heading` (Mission Close): now steps 68px → 60px → 36px (was
  44px → 68px → 34px — the 34px mobile value belonged to a different token, `[data-h1]`,
  not this heading's `[data-display]`).
- Also completed the same 1240px middle tier for `.fs-hero__heading` (`[data-h1]`, was
  missing entirely — jumped straight from 52px to 34px with no 46px step) and tightened the
  Rail Text / Commitment List desktop breakpoint from `min-width:1240px` to `1241px` so it
  doesn't overlap the `.dc` source's own `max-width:1240px` rule by one pixel.

**Footer bug found while checking "identical to Home" per the client's request**: since the
footer is one shared template-part (`site-footer.php`/`site-footer.css`) with no
About-specific override, it was already structurally identical between the two pages — but
both were wrong the same way. `.fs-site-footer__grid` had no tablet state at all: single
column from 0 all the way to 1023px, jumping straight to the 4-column desktop layout at
1024px. The `.dc` source's `[data-footer-grid]` rule specifies 2-up at ≤1024px, 1-up only at
≤720px. Restructured to a proper 3-tier grid (1-up ≤720 / 2-up 721–1024 / 4-up ≥1025) —
confirmed via live computed-style checks that Home and About now report identical column
counts at every tested width.

Re-verified end-to-end: a 13-breakpoint sweep (320–1441px) of every heading/grid listed
above on both About and Home confirms all values match their `.dc` targets exactly and the
two pages are pixel-identical wherever they share a component; zero overflow on About, Home,
Services, Team, or Contact at any width; zero console errors or failed requests (aside from
Home's own pre-existing YouTube-embed autoplay probe, unrelated to this pass); `php -l`
clean on every PHP file touched by today's verification (no PHP was changed — this pass was
CSS-only).

## 15. Footer Content-Accuracy Pass (shared component, affects every page)

The client compared a live screenshot directly against the `.dc` design and flagged several
real content/markup bugs in `site-footer.php`/`.css` — since the footer has no About-specific
override, every fix here applies site-wide, not just to About. All verified live and via
screenshot before/after; `php -l` clean on every touched file.

- **Footer CTA showed the wrong label** ("Get to Know Us" instead of "Contact Us"): the
  footer's brand-column button was reusing the header's/hero's shared Site Settings CTA
  fields, but the `.dc` source wants fixed, footer-specific copy ("Contact Us" → `/contact/`)
  independent of whatever the admin sets the global CTA to. Hardcoded the footer's own CTA
  copy, same convention already used for every other component's fixed button text.
- **"Explore" column rendered completely empty**: no menu is assigned to the theme's `footer`
  nav location in this environment, and `wp_nav_menu()` was given `fallback_cb => false` with
  no other fallback. Added a `has_nav_menu('footer') ? 'footer' : 'primary'` fallback so the
  column is never a blank space just because a menu wasn't assigned — the approved design's
  Explore list mirrors the primary nav's items anyway.
- **Social icons wrong**: order was Facebook/LinkedIn/YouTube (should be Facebook/YouTube/
  LinkedIn per the `.dc` source's relative order — there's no X/Twitter Site Settings field
  yet, so that tile is still omitted) and YouTube rendered as a literal letter "Y" instead of
  the design's play-triangle glyph (`▶`). Replaced the fragile inline ternary that derived each
  tile's letter from `substr($label, 0, 1)` with an explicit `glyph` key per social entry.
- **Logo showed as plain site-name text** instead of the real mark+wordmark image, in both the
  header and the footer, whenever no Custom Logo is set in Site Identity (true in this local
  environment). Both `else` branches now render the theme's own bundled
  `assets/img/full-logo.svg` as a sane default — a theme asset, not an `wp-content/uploads/`
  file, so this doesn't touch anything `AGENTS.md` restricts. The footer's existing CSS filter
  (`brightness(0) invert(1)`) automatically renders it in white on the teal ground; the header
  needs no filter since it already sits on a white ground matching the logo's teal ink.
- **"Resources" column links were underlined**, unlike every other footer link. Root cause:
  the sitewide link reset (`style.css`'s `.fs-nav a { text-decoration: none }`) is scoped to
  descendants of an element carrying the `.fs-nav` class — the "Explore" `<nav>` has it,
  "Resources" `<nav>` didn't (a plain `.fs-site-footer__col` with no `.fs-nav`), so its raw
  `<a>` tags fell through to the browser's default underline. Added the missing class.
- Also fixed a latent bug this pass's fallback surfaced: the "Resources" column's `<div
  class="fs-nav__list">` links weren't stacking vertically at all (rendered as one inline
  line) — `.fs-site-footer__col .fs-nav__list` set `flex-direction: column` and `gap` but
  never set `display: flex`, so it was a no-op. It happened to look correct for "Explore"
  only because `wp_nav_menu()`'s `<ul>/<li>` output stacks via the browser's own block/
  list-item defaults regardless. Added the missing `display: flex`.

Two more discrepancies visible in the same client screenshot were confirmed to be **content
data**, not code, and intentionally left for the client to correct directly in Site Settings:
the `footer_text` field holds the Partner Districts intro copy instead of the intended
tagline, and `copyright_name` holds a whole pre-formatted "©2026 Focused Schools All Rights
Reserved." line instead of just "Focused Schools" (the template already prepends its own
"©[year]" and appends its own "All Rights Reserved.", so the stored value doubles both).

## 16. Team Card: About's Card Was Silently Inheriting the Team Page's Extended Design

The client compared the live "Meet Our Team" section against `Focused Schools About.dc.html`
directly and flagged a real structural mismatch: the live card showed a quote block ("Every
Student. Every Day. No Exceptions.") that has no equivalent anywhere in About's own `.dc`
source. Tracing it: `team-card.php` is a shared component, and its quote block + LinkedIn
tile + bordered footer row turn out to belong to a *different* page's design —
`Focused Schools Team.dc.html`'s own designer-handoff notes explicitly call this "DS v1's
team card gains a quote block... and a card footer row holding Read bio and LinkedIn," an
intentional *extension* for the Team page specifically. About's `.dc` source's card is the
plain base version: portrait, name, role, and a "Read bio" inline link — no quote, no
LinkedIn tile, no footer hairline at all.

Since both pages call the same `team-card.php` with the same args (`bio_modal => true`, no
distinguishing flag), About's cards were inheriting Team's richer design wholesale. Added a
`compact` prop (default `false`, so the Team page's existing correct output is untouched) that
About now passes: it suppresses the quote and LinkedIn tile, renders "Read bio" as a plain
11px/700/uppercase inline link (`.fs-team-card__read-bio`) instead of inside the bordered
`.fs-team-card__foot` row, and applies the fixed 28/28/30px padding + 6px/18px name/position
margins `Focused Schools About.dc.html`'s card literally specifies, via a scoped
`.fs-team-card--compact` modifier rather than changing the shared card's defaults.

Verified live: About's card now shows no quote and no footer row (confirmed via DOM query,
not just visual inspection); the Team page's card was re-checked and still has
`compact` unset and renders exactly as before (no regression). Full 5-page overflow sweep
stayed clean; `php -l` clean on `team-card.php`, `page-about-our-mission-vision.php`.

## Partner Map (built)

The Partner Districts section now carries the interactive map from the approved mockup. It
had been flagged twice as needing a decision before building — `AGENTS.md` §3 gates new
libraries — and was parked until that decision came.

**Leaflet 1.9.4 is vendored into the theme** (`assets/vendor/leaflet/`) rather than loaded
from unpkg, so the site makes no third-party request for the library and nothing breaks if
a CDN is blocked or disappears. It is enqueued **only where the Partner Districts block is
actually on the page** — `focused_schools_has_partner_map()` checks with `has_block()` — so
no other page pays for it. Map *tiles* are still fetched from Esri at view time, which is
inherent to any web map; that is the one remaining third-party call and the attribution is
rendered.

### Data model

| Where | Field | Purpose |
|---|---|---|
| `fs_partner_state` term meta | `_fs_partner_state_lat` / `_lng` | One edit moves every pin for that state; nothing repeated per district. A state with no coordinates is simply not mapped. |
| `fs_partner` post meta | `_fs_partner_status` | `current` or `previous`, strictly whitelisted, drives the pin colour and the popup grouping. Defaults to `current`. |

Both are editable in wp-admin — coordinates on the state term screen, status in a side box
on the partner — so adding a district or a state updates the map with no code change.

### Progressive enhancement

The district chip list underneath is the real content and is always server-rendered. The map
is a way of reading that list, never the only copy of it: with JavaScript off, Leaflet
failing to load, or the tile service unreachable, the container stays empty and the reader
loses nothing. The canvas carries `role="img"` with a label pointing at the list below.

`?state=California` opens that state's popup on load, so a filtered view is shareable —
matching the mockup's own URL behaviour.

### QA performed

Leaflet loads, the map initialises at 1150×420, **5 pins render, 12 of 12 tiles load**, both
legend keys show, and the district list is still present below. A pin popup reads
"California · 3 DISTRICTS · CURRENT: Covina-Valley, Downey · PREVIOUS: San Marino", so the
status split works. `?state=Illinois` opens the Illinois popup on load. Leaflet is served
from the theme (200, 147,552 bytes) and is **absent from `/services/`**, confirming the
scoping. Zero console errors, full-project PHPCS clean.

**Not verified:** tablet and mobile were not rendered — the browser here will not resize —
and screenshot capture failed repeatedly, so the visual check is by measurement only.
