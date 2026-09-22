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
