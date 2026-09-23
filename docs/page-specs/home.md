# Page Spec: Home

Status: **implemented**. Source of truth for `front-page.php`. Design reference:
`https://focused-schools-rebrand.vercel.app/` (initial build) plus
`https://focused-schools-full-site-2f37f011.vercel.app/#/home` (a fuller, later capture of
the same design system, used for a correction pass — see §10). Sections below note what
was taken verbatim vs. adapted, and why.

## 1. Page Identity

- **Front page ID:** production ID 992 (per task); this local environment's actual front
  page is ID 29 (no page 992 exists locally). Neither matters to the code:
  `front-page.php` resolves the front page via `show_on_front`/`page_on_front`, never a
  hardcoded ID — see `docs/architecture.md` §4.6.
- **Template file:** `front-page.php` (not a separate `page-home.php` — see the
  Architecture Decision below).
- **Elementor coexistence:** unchanged from the prior version — if the resolved front
  page is Elementor-built, only its filtered content renders.

## 2. Architecture Decision: front-page.php, not a new page-home.php

The task asked for a new `page-home.php` (`Template Name: Homepage`). `front-page.php`
already existed and is what WordPress actually renders for the site's front page via its
native, ID-agnostic mechanism (`docs/architecture.md` §4.6). In WordPress, if a custom
`Template Name` page template gets assigned to the front page, `get_front_page_template()`
skips `front-page.php` entirely — so introducing `page-home.php` risked silently
orphaning the already-built, documented front-page mechanism the moment anyone assigned
it in wp-admin (locally or in production). **Decision (confirmed with the project
owner):** rebuild the design directly in `front-page.php`. No `page-home.php` was created.

## 3. Design Tokens

Real brand colors (house-teal, coral, cerulean, lime, gold, raspberry) and DM Sans
typography from the reference were promoted into `theme.json` site-wide (confirmed with
the project owner as the first real design-system input this project has had) — see
`docs/design-system.md`. This affects every page, not just Home.

## 4. Sections (in order) and Component Mapping

| # | Section | Component | Notes |
|---|---|---|---|
| 1 | Hero (video broadcast) | `home-hero.php` (new) | Distinct from the shared `hero.php` (used by every other page) — not modified, so no other page is affected. Poster image + click-to-load YouTube modal, zero network requests to YouTube before the click (verified live). |
| 2 | Beliefs / "What we believe" | `rail-text.php` (built for the About page, now also used here — see §10) + a 3-photo documentary strip | Icon + eyebrow label beside heading/body/CTA, no image split. |
| 3 | Three Commitments | `commitment-list.php` (new) | Numbered list + one photo. Generic/args-driven (fixed editorial copy, not repeated CPT content). |
| 4a | Cycle of Excellence (teaser) | `cycle-teaser.php` (new — see §10) | Decorative card: heading, body, portrait image. |
| 4b | Cycle of Excellence (diagram) | `cycle-of-excellence.php` (new) | Interactive 3-phase stepper; all phase content is always in the markup (JS only toggles emphasis). Originally merged with 4a — un-merged, see §10. |
| 5 | Services | `service-list.php` (new) + `fs_service` CPT, plus an intro paragraph and companion photo (added — see §10) | Numbered row layout (distinct from `service-card.php`'s grid, used on the Services page). Real dynamic query (`WP_Query`, 4 posts, `menu_order`), graceful empty-state if none exist. Reuses the same `_fs_service_tagline` meta as `service-card.php`. |
| 6 | Testimonials | `testimonial-carousel.php` (new) + `fs_testimonial` CPT (new) | See §5. |
| 7 | Impact stats | `statistics-counter.php` (existing, reused as-is) | Real current figures (2M+ students, 20+ years, 25+ states) confirmed against the reference — not a TODO placeholder, unlike the prior draft of this template. |
| 8 | Contact | `contact-info.php` (existing, reused as-is) + a CTA button | See §6 — no second contact form was built. |

## 5. New CPT: Testimonials (`fs_testimonial`)

`wp-content/plugins/focused-schools-core/modules/testimonials/`. Same admin-only,
no-frontend-URL pattern as Team/Services. No custom meta fields: `post_title` is the
attribution (e.g. "Superintendent"), `post_content` (editor) is the quote itself — this
needed no meta box, just the standard post editor. Empty by default in this environment;
`testimonial-carousel.php` renders a graceful "on the way" message until entries exist
(verified live).

## 6. Contact Section: Adapted, Not Copied Verbatim

The reference embeds a full second contact form (Name/Phone/Email/Message, submitting via
a `mailto:` form action — not a real backend). This project has an explicit, repeated
constraint from an earlier task: **do not build a custom form engine**, and the real
Elementor-driven contact form already lives at `/contact/`. Building a second, differently
-behaved "form" on the homepage would violate that constraint and fragment the one real
form. **Adaptation:** the Contact section shows real business info via `contact-info.php`
(Site Settings-driven — address/phone/email, requirement 3) plus a "Contact Us" button
linking to the real `/contact/` page, instead of a duplicate form.

## 7. Header & Footer (site-wide, not Home-only)

The task listed `header.php`/`footer.php` as files to update — these changes are
site-wide (every page), not Home-specific:

- **Header:** the existing accessible toggle (`site-header.js`) now also opens a
  contact/social "aside" panel (email + Facebook/LinkedIn/YouTube from Site Settings) —
  one enhanced, already-tested toggle mechanism, rather than a second parallel off-canvas
  dialog system. Added: focus trap (Tab/Shift+Tab wrap), body-scroll lock, focus-return
  to the toggle button on close.
- **Footer:** 3-column layout (Explore / Resources / Connect). All three reuse
  infrastructure that already existed before this task — the `footer` nav menu location,
  the `footer-widgets` sidebar (added in a prior task), and Site Settings — no new nav
  menu location was registered.
- **`id="top"`** moved from the Home hero specifically onto the site-wide `<header>`
  (`header.php`), since the footer's "Back to top" link needs a target that exists on
  every page, not just Home.

## 8. Images

Every `assets/img/*.jpg`/`.webp` reference in `front-page.php` is a placeholder path
(`get_template_directory_uri() . '/assets/img/...'`), per the task's own instruction. No
binary image files were added — this session has no rights to the reference site's actual
photography. Each `<img>` 404s harmlessly until real files are dropped into those exact
paths (confirmed live: only these 3 requests fail, nothing else). Alt text is already
final, written from the reference's own alt text where descriptive.

## 9. Accessibility

- Skip link, semantic `<section aria-labelledby="...">` per section, unchanged
  `<main id="content">` skip-link target.
- Video modal: `role="dialog"`, `aria-modal`, closes on Escape and backdrop click, no
  focus trap yet inside the modal itself (only the nav panel has one) — a reasonable
  follow-up if this needs full WAI-ARIA dialog compliance.
- Testimonial carousel: `role="tablist"`/`role="tab"` dots, `aria-live="polite"` status
  text, all slides always in the DOM (`aria-hidden` toggled, not `display:none`d via JS
  alone — verified no-JS-equivalent content is present).
- Cycle of Excellence stepper: `role="group"`, `aria-current="step"`, all phase
  descriptions always present regardless of JS.
- Verified live (browser automation): video modal open/close (via Escape), cycle stepper
  click, all with zero new console errors beyond the 3 expected placeholder-image 404s.

## 10. Correction Pass Against a Fuller Reference Capture

A later task ("Implement the approved Home and About pages") supplied a more complete
capture of the same design system (the "full site" prototype already used to build the
About page — `docs/page-specs/about.md`), which surfaced three real gaps against the
original build:

1. **Beliefs section used the wrong component.** The original build reused
   `content-image-split.php` (image beside text) as an approximation. The fuller
   reference confirmed this section actually uses the icon+eyebrow "rail" pattern (no
   image split) — the same `rail-text.php` component built for the About page's "Who We
   Are" section — plus a 3-photo documentary strip below it, which had been dropped
   entirely. Both are now correct.
2. **Cycle of Excellence was wrongly merged.** The original build treated the
   reference's two-section structure (teaser card, then interactive stepper) as a likely
   duplication artifact and merged them into one. The fuller reference showed the same
   two-section structure again, independently — stronger evidence it's intentional, not
   an artifact. Un-merged into `cycle-teaser.php` (new, decorative card) followed by the
   existing `cycle-of-excellence.php` (interactive stepper, unchanged).
3. **Services section was missing its intro paragraph, companion photo, and had the
   "View all services" link in the wrong place.** Added the intro body copy (was
   entirely absent), the "Focused facilitation" photo with caption, and moved the link
   to directly follow the intro copy (was after the row list) — composed directly in
   `front-page.php` (`.fs-home__services-head`), not a new component, since
   `section-heading.php` doesn't support an adjacent photo.

**Explicitly re-confirmed, not changed:** the Contact section's "no inline form" decision
(§6) — the fuller reference also shows an inline form here, but the project owner
re-confirmed keeping contact info + a CTA button only, per the same "no custom form
engine" constraint. The header's use of real registered WP nav menus and Site Settings
(instead of the reference's hardcoded `#/about` etc. links and hardcoded "Let's Talk"
copy) was also re-confirmed as correct, not a gap — those were deliberate translations
of the design into real WordPress mechanisms, not omissions.

## 11. Precision Pass Against the `.dc` Design-Comp Source Files

A later task supplied three literal design-comp files at the theme root —
`Design System v1.dc.html`, `Focused Schools Homepage.dc.html`,
`Focused Schools About.dc.html` — as the **exact** visual/structural/token source of
truth, superseding the Vercel prototype captures used for the builds in §1–§10 above.
These are static exported markup + inline styles (a "DCLogic" component-gallery format),
not a live app, so every color, size, and spacing value could be read directly rather than
approximated from a screenshot.

**Token changes (`theme.json` + `style.css`, site-wide):** added `eyebrow`/`small` (was
`0.9rem`, now `0.9375rem`)/`body`/`lead`/`display` font sizes, `card-gap`/`section-tablet`/
`section`/`section-teal` spacing sizes, and `teal-deep`/`rule` colors — all *additive*;
every pre-existing slug (`small`/`medium`/`large`/`x-large`/`2x-large` spacing,
`medium`/`large`/`x-large` typography, the original color slugs) keeps its exact original
value, since every component CSS file references these by slug via
`var(--wp--preset--*--slug, fallback)` and the fallback only applies when a property is
*undefined*, not when its value changes — renaming or re-valuing an existing slug would
have been a silent, site-wide breaking change. Radius/shadow/motion/shell-width tokens
have no `theme.json` equivalent, so they're plain `:root` custom properties in
`style.css` (`--fs-r-sm/md/lg/pill`, `--fs-shadow-1/2`, `--fs-dur-fast/base/slow`,
`--fs-ease`, `--fs-shell: 1400px`), named to match the `.dc` source's own token names.

**Component contract changes (site-wide, not Home-only):**

- `button.php`: `style` prop's accepted values changed from `primary|secondary|outline`
  to `primary|secondary|white|text` (the `.dc` source has no "outline" pill — it has a
  white-ground and a plain-text-link variant instead); added an `arrow` bool (default
  `true`) for the trailing `→` glyph. Every existing caller was updated at the same time,
  so no caller was left passing the now-invalid `outline` value.
- `hero.php` (used by About plus 6 other pages): now always renders in the shell
  container; media (when present) gets a teal slab overlapping its bottom-left corner —
  same treatment as `home-hero.php`. Pages that call it without an image (Contact/Impact
  Stories/Podcast/Services/Team/Thanks) get a plain in-flow block instead, via the
  `.fs-hero__media + .fs-hero__slab` adjacent-sibling selector, so nothing floats or
  collapses on those pages — confirmed via `grep` that all 6 pass no `image_id`/`image_url`.
- **Header:** fully rewritten to the `.dc` pattern — sticky 74px white pill bar, a
  horizontal desktop-only (≥1024px) nav rendered from the same registered `'primary'` WP
  menu, a Site-Settings-driven CTA, and an always-visible "Menu" button that opens a new
  `#fs-site-menu` overlay: the *same* `'primary'` menu rendered again as a large two-column
  grid beside a teal aside (tagline/email/socials). One real nav menu, two presentations —
  no second nav menu location registered. Replaces the earlier "off-canvas aside" pattern
  from §7. Added a scroll-progress bar tied to a `--fs-nav-progress` custom property
  (rAF-throttled, skipped entirely under `prefers-reduced-motion`).
  **Mobile fix (not in the `.dc` source, which only shows the header at desktop width):**
  porting the source's fixed-pixel `gap`/`padding` verbatim caused the CTA button and Menu
  toggle to overflow the pill bar's `overflow: hidden` box below ~600px — the Menu toggle
  became completely invisible and unreachable (confirmed via bounding-box measurement,
  not just a screenshot). Fixed with a `max-width: 599px` rule in `site-header.css` that
  drops the CTA and right-aligns the toggle, verified clean from 375px up.
- **Footer:** rewritten to a 4-column grid (Brand/Explore/Resources/Connect); Resources
  is now hardcoded `/podcast/`/`/blog/`/`/contact/` links (was the `footer-widgets`
  sidebar); Connect socials render as single-letter glyphs; added a watermark image.

**Motion — no scroll-jacking (reaffirmed, not new):** the `.dc` files' own embedded JS
literally implements scroll-scrubbed/sticky-pin sections for the Beliefs and Cycle of
Excellence sections. Both the Design System doc's own binding rule ("No parallax, no
scroll-jacking") and this project's established practice say otherwise. Kept the
accessible equivalents already in place: `min-height: 100vh` flex-centered (not
sticky-pinned) for Cycle of Excellence, a continuously-rotating (not scroll-tied) CSS mark,
existing click/keyboard-driven stepper — all disabled/simplified under
`prefers-reduced-motion` as before.

**Impact Stats now Site-Settings-driven, shared with About:** the About `.dc` file's own
developer notes state the stat values must be stored once so Home and About cannot drift —
see `docs/page-specs/about.md` §10 for the new `impact_students`/`impact_years`/
`impact_states` Site Settings fields both pages now read.

## 12. Placeholder-Image Collapse Fix + Missing Prop Wiring

A follow-up pass found several Home sections rendering empty/collapsed in this
placeholder-image environment, traced to two distinct causes (not a §11 regression —
same root causes existed before that pass, just newly visible once compared section by
section against the `.dc` reference):

1. **`aspect-ratio` on a bare `<img>` doesn't reliably reserve layout space for a 404'd
   image** in this environment — confirmed via direct bounding-box measurement (a
   `width:100%; aspect-ratio:1/1` image collapsed to a few px tall instead of reserving
   its box). Fixed by switching every affected image to the same wrapper-reserves-space +
   `position:absolute; inset:0` fill pattern already proven correct by `hero.css`/
   `home-hero.css`: `.fs-home__strip` figures, `.fs-home__services-photo`,
   `.fs-commitments__photo` (shared with About — same latent bug existed there too, now
   also fixed), and `.fs-cycle-teaser__person` (new wrapper div added around the image in
   `cycle-teaser.php`). Each wrapper also gets a `teal-deep` fallback background, so a
   missing photo now reads as an intentional placeholder block instead of blank space.
2. **Two components' image/motif props were never wired up from `front-page.php`** despite
   existing in the component contract: `cycle-teaser.php`'s `watermark_url`/`shape_urls`
   (the faint background mark + hover shapes) and `cycle-of-excellence.php`'s `mark_url`
   (the rotating wheel graphic — its `if ( $fs_mark )` guard meant the whole element,
   including the CSS-drawn disc that doesn't depend on the image loading, silently
   didn't render at all). Both now pass real theme-relative paths
   (`assets/img/mark-cycle.svg`, `assets/shapes/star.svg` etc., matching the `.dc`
   source's own filenames) — the wheel's white disc is pure CSS, so it now renders
   correctly regardless of whether the SVG file itself exists yet.

**Impact Stories carousel:** the component itself was already complete (quote card,
attribution, pagination dots); it rendered its empty-state message because the
`fs_testimonial` CPT had zero posts in this environment. Seeded the 3 real quotes from
the `.dc` source's own `quotes` array (local dev content only, not a code change) so the
carousel demonstrates correctly.

**Stats data:** re-checked against the `.dc` source's `stats` array — the existing values
(2+ Million Students Impacted / 20+ Years Partnering with Schools / 25+ States Served)
match exactly. No change made.

## Hero Ambient Video and the Scroll-Scrubbed Cycle (mockup-source pass)

Built against the mockup folder's own `site/index.html`, `site/assets/home.css` and
`site/assets/home.js`, which are the literal source rather than the deployed capture.

### Hero ambient video — was missing entirely

The hero had only a poster and a "Watch with sound" modal. The mockup also runs a **muted
ambient loop** behind the poster, which is what "the hero video autoplay" refers to; it had
never been implemented, so there was nothing to autoplay.

Implemented to the mockup's own approach: `home-hero.js` creates the iframe rather than the
markup printing it, so it is **never requested under `prefers-reduced-motion`** and a no-JS
visitor simply keeps the poster. The embed is youtube-nocookie with
`autoplay=1&mute=1&controls=0&loop=1&playlist={id}&playsinline=1&enablejsapi=1`, is
`tabindex="-1"`, `aria-hidden`, `pointer-events: none`, and is sized to 178% and centred so
YouTube's letterboxing is cropped out of the 16:9 frame.

A playback console sits over the frame: a teal Pause/Play pill whose glyph flips from two
bars to a triangle via `aria-pressed`, and the existing "Watch with sound" button. Playback
is driven through the YouTube iframe API by `postMessage`. Opening the sound modal pauses
the ambient loop so two soundtracks never compete, and closing it resumes — unless the
visitor had paused it themselves, which is remembered.

### Cycle of Excellence — scroll-scrubbed, not a spinner

Previously a decorative CSS spin (40s linear infinite), documented as deliberately
not-scroll-scrubbed. The mockup's own source shows the opposite: this section is the
homepage's one piece of scroll-driven motion.

- The section is **230vh** and its stage is **`position: sticky; top: 0; height: 100vh`**,
  so the diagram holds still on screen while the page scrolls past it.
- Scroll progress through the extra height drives three things in step: the mark rotates
  through **exactly 360°** across the section; a **10px lime dot orbits** at the mark's
  radius on the same angle; and the phase rows advance one per equal slice.
- The orbit radius is a custom property (`--fs-cycle-orbit-r`: 183px at 340px, 129px at
  240px, 108px at 200px) read by the script, so the dot tracks the mark at every
  breakpoint instead of being pinned to the desktop value.
- Hover, focus or click on a phase row takes over from the scroll position, so the diagram
  is usable without scrolling.
- Under `prefers-reduced-motion` nothing rotates and the stage stops pinning; the rows
  still step, so no content is gated behind motion. Same on <=720px, where there is too
  little height to pin against.

### "Our Impact" heading restored

The stats band rendered without its `<h2>`, so the homepage had 8 headings against the
mockup's 9. Added at 52px desktop / 36px below with a 56px gap to the figures.

### QA performed

`php -l` and full-project PHPCS clean. In-browser: the ambient iframe is created with the
expected autoplay/mute/loop/controls/jsapi parameters against youtube-nocookie, `tabindex`
-1 and `aria-hidden` true; the Pause/Play toggle flips `data-paused`, `aria-pressed` and
the label both ways. Cycle verified under genuine scroll at 78% progress: rotor
`rotate(282.27deg)` (0.784 x 360), orbit on the same angle measured **183px from the mark
centre** (dx 39, dy -179), phase 3 active, orbit filled lime `#a7cc14`. All 9 homepage
headings now match the mockup in order.

**Not verified:** programmatic scrolling in the automation context does not reliably emit
scroll events, so intermediate scrub positions were confirmed from one genuine wheel scroll
plus computed progress rather than a full sweep. Reduced-motion and mobile were not
rendered. The rest of the homepage beyond these three items has not yet been audited
section by section against `Focused Schools Homepage.dc.html`.

## Beliefs Section — Scroll-Held Statement

The mockup's `#why` section is also scroll-driven, and was not implemented. `.hm-hold` is
190vh with a sticky inner block, so the belief statement holds on screen while the reader
scrolls past it, and a coral rule under the opening phrase **"Every student"** grows across
that travel (progress x 2.2, clamped, so it completes near the midpoint rather than still
creeping as the reader leaves).

Implemented as `.fs-home__hold` / `.fs-home__hold-inner` on the Home template plus
`home-hold.js`. `rail-text.php` gained an optional **`emphasis`** arg: when the first
paragraph really starts with that phrase it is wrapped in `<em class="fs-emph">` with the
rule span, otherwise the copy renders plain — so a content edit can never produce
mismatched markup. The About page's use of the component is unaffected.

Below 1024px, and under `prefers-reduced-motion`, the section stops holding and the rule is
simply shown complete, so the emphasis still reads without motion.

## Header and Menu Panel

The header's "Menu" button opened a full-screen `role="dialog"` overlay with a numbered
two-column grid and a teal aside carrying tagline/email/socials. The mockup has none of
that: it is a **dropdown panel anchored under the bar**.

| | Mockup | Was |
|---|---|---|
| Presentation | Panel, `position: absolute; top: calc(100% + 10px)`, 16px radius, 22px padding | Full-screen modal dialog |
| Contents | Primary nav items **plus Contact**, each a 52px row at 18px/700 with a coral arrow and a hairline | Numbered cells + tagline/email/socials aside |
| Footer | Full-width primary CTA | none |
| Button | Logo mark + "Menu" + two lines | "Menu" + two lines |
| Open state | Lines pinch `±3.5px` and tilt `±12°` — a shallow bowtie, not a 45° cross | no change |
| Dismissal | Escape **or a click outside the header** | Escape or close button |
| <=1023px | Inline nav and the button's text label both hide | inline nav hid |

Because it is a dropdown rather than a modal, it deliberately does **not** trap focus or
lock body scrolling; it toggles `data-open` alongside `aria-expanded` and swaps the
button's `aria-label` between "Open menu" and "Close menu". `.fs-site-header__bar` also
lost its `overflow: hidden`, which would have clipped the panel — the mockup's bar has no
such clip either.

### QA performed

Full-project PHPCS clean, all routes 200, header renders on inner pages too. In-browser:
the panel is `display: none` closed and `block` open with `position: absolute`, 16px
radius, 22px padding on white; `aria-expanded` and `aria-label` flip both ways; the seven
rows read About / Team / Services / Impact Stories / Podcast / Blog / Contact, each with a
coral arrow; the footer CTA spans the panel (1125px inside 1169px, less 2x22px padding);
and the burger's lines visibly pinch into the shallow bowtie when open (confirmed by zoomed
screenshot — `getComputedStyle` reported an identity matrix in the automation context, so
the visual check is the reliable evidence here).

**Not verified:** below-1023px behaviour, where the inline nav and button label drop, was
not rendered.

**Still not carried over from the mockup:** the header keeps a scroll-progress bar
(`.fs-site-header__progress`) that the mockup's header does not have. Left in place rather
than removed unasked.
