# Page Spec: Home

Status: **implemented**. Source of truth for `front-page.php`. Design reference:
`https://focused-schools-rebrand.vercel.app/` — an AI-generated rebrand concept whose own
source comment says "Generated comps are north stars only," not a pixel spec. Sections
below note what was taken verbatim vs. adapted, and why.

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
| 2 | Mission / "What we believe" | `content-image-split.php` (extended) | Added an `image_url` prop (theme-static path) alongside the existing `image_id` (attachment) prop — additive, backward compatible. |
| 3 | Three Commitments | `commitment-list.php` (new) | Numbered list + one photo. Generic/args-driven (fixed editorial copy, not repeated CPT content). |
| 4 | Cycle of Excellence | `cycle-of-excellence.php` (new) | The reference shows this idea **twice** (a teaser card, then a fuller stepper) with the same "A Cycle of Excellence" heading repeated — read as an editing artifact, not an intentional repeat, so this was **merged into one section** rather than literally duplicated. Interactive 3-phase stepper; all phase content is always in the markup (JS only toggles emphasis). |
| 5 | Services | `service-list.php` (new) + `fs_service` CPT | Numbered row layout (distinct from `service-card.php`'s grid, used on the Services page). Real dynamic query (`WP_Query`, 4 posts, `menu_order`), graceful empty-state if none exist. Reuses the same `_fs_service_tagline` meta as `service-card.php`. |
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
