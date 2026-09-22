# Component Specifications

Status: **21 components approved and implemented.** Components are
invoked via `get_template_part( 'template-parts/components/{name}', null, $args )`, using
WordPress's native `$args` support as the prop/contract mechanism — see each spec below
for its `$args`.

## 1. Purpose

This document holds specifications for reusable theme/plugin components (template
partials, blocks, or UI patterns) used across `focused-schools`.

## 2. Component Index

| Component | Location | Description | Status |
| --------- | -------- | ----------- | ------ |
| Header navigation structure | `template-parts/components/site-header.php` | Site branding, Primary nav menu, mobile toggle, optional Site Settings CTA | Implemented |
| Footer navigation structure | `template-parts/components/site-footer.php` | Footer nav menu, Site Settings social links/footer text/copyright | Implemented |
| Hero block | `template-parts/components/hero.php` | Heading/subheading/image/CTA hero section | Implemented |
| Section Heading | `template-parts/components/section-heading.php` | Eyebrow + heading + description, adjustable heading level | Implemented |
| Buttons & CTA blocks | `template-parts/components/button.php` | Single link-button, 3 style variants | Implemented |
| Content/Image Split | `template-parts/components/content-image-split.php` | Two-column content + image, either order | Implemented |
| Service Card | `template-parts/components/service-card.php` | `fs_service` display integration | Implemented |
| Team Card | `template-parts/components/team-card.php` | `fs_team_member` display integration | Implemented |
| Impact Story Card | `template-parts/components/impact-story-card.php` | `fs_impact_story` display integration | Implemented |
| Podcast Card | `template-parts/components/podcast-card.php` | Generic/args-driven (no CPT yet) | Implemented |
| Statistics counter block | `template-parts/components/statistics-counter.php` | Animated count-up, real value always in markup | Implemented |
| Partner Strip / Logo grid | `template-parts/components/partner-strip.php` | Generic/args-driven logo list | Implemented |
| Form styling wrappers | `template-parts/components/form-wrapper.php` | CSS-only Elementor form coexistence wrapper | Implemented |
| CTA Banner | `template-parts/components/cta-banner.php` | Highlighted-band closing call-to-action section | Implemented (added for the Home page task) |
| Contact Info block | `template-parts/components/contact-info.php` | Site Settings-driven business contact details | Implemented (added for the Contact page task) |
| Post Card | `template-parts/components/post-card.php` | Native `post` display (archive listings + related posts) | Implemented (added for the Blog coexistence task) |
| Home Hero | `template-parts/components/home-hero.php` | Video-poster broadcast hero, click-to-load YouTube modal | Implemented (added for the 2026 rebrand Home page task) |
| Commitment List | `template-parts/components/commitment-list.php` | Numbered commitments list + photo (generic/args-driven) | Implemented (added for the 2026 rebrand Home page task) |
| Cycle of Excellence | `template-parts/components/cycle-of-excellence.php` | 3-phase interactive stepper (generic/args-driven) | Implemented (added for the 2026 rebrand Home page task) |
| Service List | `template-parts/components/service-list.php` | `fs_service` display integration, numbered row layout | Implemented (added for the 2026 rebrand Home page task) |
| Testimonial Carousel | `template-parts/components/testimonial-carousel.php` | `fs_testimonial` display integration, prev/next + dots carousel | Implemented (added for the 2026 rebrand Home page task) |

Shared CSS: `assets/css/components/card.css` provides the base `.fs-card` styling reused
by Service/Team/Impact Story/Podcast/Post cards. `assets/css/components/card-grid.css` is
a shared responsive grid layout utility (not a template-part component) for arranging
repeated cards — used by the Team/Impact Stories sections and the blog archive/related
-posts listings (the Home page's own Services section now uses Service List's row layout
instead — see `docs/page-specs/home.md`). Component JS:
`assets/js/components/site-header.js` (mobile nav toggle, off-canvas contact/social
panel, focus trap, body-scroll lock),
`assets/js/components/statistics-counter.js` (count-up animation),
`assets/js/components/podcast-video.js` (YouTube click-to-load, inline facade),
`assets/js/components/home-hero.js` (YouTube click-to-load, modal dialog),
`assets/js/components/cycle-of-excellence.js` (phase stepper),
`assets/js/components/testimonial-carousel.js` (prev/next + dots, no auto-advance).

## 3. Component Specs

### Header navigation structure

- **Location:** `template-parts/components/site-header.php` / `assets/css/components/site-header.css` / `assets/js/components/site-header.js`
- **Purpose:** Site branding, Primary nav, mobile-collapsed menu (with a contact/social aside below 1024px), optional header CTA.
- **Props / Fields:** none — pulls the `primary` registered nav menu and Site Settings `cta_label`/`cta_url`/`email`/`facebook_url`/`linkedin_url`/`youtube_url` (gracefully empty if the plugin is inactive).
- **States:** nav collapsed (default, <1024px) / open (`.is-open`, toggled via `aria-expanded`, focus-trapped, body-scroll-locked) / always-visible (≥1024px, aside hidden — the footer's Connect column covers that content in the persistent desktop layout).
- **Dependencies:** `template-parts/components/button.php` (for the optional CTA), `focused_schools_get_setting()` (plugin helper, optional).
- **Related design tokens:** color/spacing/font-size CSS custom properties from `theme.json` (approved 2026 rebrand tokens — see `docs/design-system.md`).

### Footer navigation structure

- **Location:** `template-parts/components/site-footer.php` / `assets/css/components/site-footer.css`
- **Purpose:** 3-column footer (Explore / Resources / Connect) + brand block + copyright/back-to-top base row.
- **Props / Fields:** none — pulls the `footer` registered nav menu (Explore), the `footer-widgets` sidebar (Resources, only rendered if a widget is assigned), and Site Settings (`email`, `phone`, `facebook_url`, `linkedin_url`, `youtube_url`, `footer_text`, `copyright_name`, `cta_label`/`cta_url`).
- **States:** default only; the Resources column and social list are each omitted entirely when empty.
- **Dependencies:** `template-parts/components/button.php` (brand CTA), `focused_schools_get_setting()` (plugin helper, optional). "Back to top" targets `id="top"` on `header.php`'s `<header>` element (site-wide, not Home-only).
- **Related design tokens:** same as above.

### Hero block

- **Location:** `template-parts/components/hero.php` / `assets/css/components/hero.css`
- **Purpose:** Page/section hero with heading, optional subheading, image, and CTA.
- **Props / Fields:** `heading` (string, required), `subheading` (string), `image_id` (int), `cta_label` (string), `cta_url` (string), `alignment` (`left`\|`center`, default `left`).
- **States:** stacked (mobile) / side-by-side (≥1024px); with/without image; with/without CTA.
- **Dependencies:** `template-parts/components/button.php`.
- **Related design tokens:** typography scale, spacing scale.

### Section Heading

- **Location:** `template-parts/components/section-heading.php` / `assets/css/components/section-heading.css`
- **Purpose:** Consistent eyebrow/heading/description block above a section.
- **Props / Fields:** `heading` (string, required), `eyebrow` (string), `description` (string), `heading_level` (int 2–4, default 2), `alignment` (`left`\|`center`, default `left`), `heading_id` (string, optional — added for the Services page so an ancestor `<section>` can use `aria-labelledby`).
- **States:** left/center alignment; with/without eyebrow/description.
- **Dependencies:** none.
- **Related design tokens:** typography scale.

### Buttons & CTA blocks

- **Location:** `template-parts/components/button.php` / `assets/css/components/buttons.css`
- **Purpose:** Single reusable link-styled-as-button.
- **Props / Fields:** `label` (string, required), `url` (string, required), `style` (`primary`\|`secondary`\|`outline`, default `primary`), `target` (e.g. `_blank`, auto-adds `rel="noopener noreferrer"`).
- **States:** default, `:hover`, `:focus-visible`; 3 visual style variants.
- **Dependencies:** none — composed into Hero, Content/Image Split, header CTA, podcast card.
- **Related design tokens:** color palette, spacing scale.

### Content/Image Split

- **Location:** `template-parts/components/content-image-split.php` / `assets/css/components/content-image-split.css`
- **Purpose:** Two-column content + image section, image on either side.
- **Props / Fields:** `heading` (string), `body` (string, HTML allowed via `wp_kses_post()`), `image_id` (int, attachment), `image_url` (string, theme-static URL — used only when `image_id` is not set; added for the 2026 rebrand Home page task, additive/backward-compatible), `image_alt` (string, for `image_url`), `image_position` (`left`\|`right`, default `right`), `cta_label` (string), `cta_url` (string).
- **States:** stacked (mobile) / side-by-side (≥1024px), image left or right.
- **Dependencies:** `template-parts/components/button.php`.
- **Related design tokens:** typography scale, spacing scale.
- **Note:** always renders in the wide (`.fs-container--wide`, 1200px) container, same as Hero — this is a self-contained section component, so its width is fixed internally and unaffected by whatever wraps its `get_template_part()` call.

### Service Card

- **Location:** `template-parts/components/service-card.php` / `assets/css/components/service-card.css` (+ shared `card.css`)
- **Purpose:** Display a single `fs_service` post.
- **Props / Fields:** `post` (`WP_Post`\|int, required).
- **States:** with/without featured image; 3 accent-role modifiers (`fs-card--accent-strategy|leadership|capacity`).
- **Dependencies:** `focused-schools-core` plugin's `fs_service` post type + `_fs_service_*` meta (theme reads meta key literals directly so it degrades gracefully, not fatally, if the plugin is inactive).
- **Related design tokens:** **TEMPORARY** accent-role → color mapping (strategy/leadership/capacity → primary/accent/secondary placeholder tokens); revisit once `docs/design-system.md` defines real semantic role colors.
- **Anchor:** always renders with `id="{post_name}"` (the service's own slug), so `/services/#{slug}` deep-links land on the card; `scroll-margin-top` in `service-card.css` keeps it clear of the header on jump. Added for the Services page — see `docs/page-specs/services.md` §4.

### Team Card

- **Location:** `template-parts/components/team-card.php` / `assets/css/components/team-card.css` (+ shared `card.css`)
- **Purpose:** Display a single `fs_team_member` post.
- **Props / Fields:** `post` (`WP_Post`\|int, required), `show_bio` (bool, optional, default `false` — renders a bio excerpt from the member's `editor` content; opt-in so existing usages like the Home page teaser are unaffected. Added for the Team page.).
- **States:** with/without headshot, bio, quote, or LinkedIn URL (each section omitted if empty).
- **Dependencies:** `focused-schools-core` plugin's `fs_team_member` post type + `_fs_team_*` meta.
- **Related design tokens:** typography, spacing, accent color (quote border).

### Impact Story Card

- **Location:** `template-parts/components/impact-story-card.php` / `assets/css/components/impact-story-card.css` (+ shared `card.css`)
- **Purpose:** Display a single `fs_impact_story` post, linking to its real permalink.
- **Props / Fields:** `post` (`WP_Post`\|int, required).
- **States:** featured (`fs-impact-story-card--featured`, with visible "Featured" badge, not color-only) vs. standard.
- **Dependencies:** `focused-schools-core` plugin's `fs_impact_story` post type + `_fs_impact_story_*` meta.
- **Related design tokens:** accent color (featured badge/border).
- **Dual post-type support:** also renders legacy Impact Story Pages unchanged (used on the Impact Stories landing page for a unified grid) — every function it calls is post-type-agnostic, so this required no code changes, only a docblock update. See `docs/page-specs/impact-stories.md` §3.

### Podcast Card

- **Location:** `template-parts/components/podcast-card.php` / `assets/css/components/podcast-card.css` (+ shared `card.css`)
- **Purpose:** Display a podcast episode. **Generic/args-driven — no `fs_podcast` post type exists yet** (see `docs/architecture.md` §3.5); ready to wire to real data once that module is built.
- **Props / Fields:** `title` (string, required), `description` (string), `embed_html` (string, e.g. a Buzzsprout `<iframe>` — sanitized via `wp_kses()` with an iframe-extended allowlist, not trusted verbatim; lightweight, so renders immediately), `youtube_id` (string, bare 11-char YouTube video ID, validated by regex — renders a click-to-load facade instead of an iframe; see below), `episode_number` (string\|int), `duration` (string), `cta_label` (string), `cta_url` (string).
- **States:** with/without embed, video, episode number, duration, CTA; video facade (unplayed) vs. loaded iframe (post-click).
- **Dependencies:** `template-parts/components/button.php`; `assets/js/components/podcast-video.js` for the video facade's click-to-load behavior.
- **Related design tokens:** typography, spacing.
- **YouTube performance:** `youtube_id` never renders a `<iframe>` on initial load — only a lightweight `<img loading="lazy">` (YouTube's own thumbnail URL) plus a real `<button>` facade (native keyboard support, no custom role/keydown handling needed). The real iframe is created client-side only after a genuine click/Enter/Space. The facade and the eventual iframe both live inside one `aspect-ratio: 16/9` container (`.fs-podcast-card__video`) and fill it identically, so the swap causes zero layout shift — verified via document-relative position (not viewport-relative, which is affected by auto-scroll-into-view on click) before/after the swap. See `docs/page-specs/podcast.md` §4.

### Statistics counter block

- **Location:** `template-parts/components/statistics-counter.php` / `assets/css/components/statistics-counter.css` / `assets/js/components/statistics-counter.js`
- **Purpose:** Animated count-up statistics grid.
- **Props / Fields:** `stats` (array, required) of `{ value, suffix, label }`.
- **States:** default; animation is entirely skipped under `prefers-reduced-motion` (final value is always present in the HTML source regardless of JS/motion state).
- **Dependencies:** none.
- **Related design tokens:** typography scale, spacing scale, primary color.

### Partner Strip / Logo grid

- **Location:** `template-parts/components/partner-strip.php` / `assets/css/components/partner-strip.css`
- **Purpose:** Row/grid of partner or client logos. Generic/args-driven — no CPT.
- **Props / Fields:** `logos` (array, required) of `{ image_id (required), name (required, used as alt text), url (optional) }`, `heading` (string).
- **States:** logo linked vs. unlinked.
- **Dependencies:** none.
- **Related design tokens:** spacing scale.

### Form styling wrappers

- **Location:** `template-parts/components/form-wrapper.php` / `assets/css/components/form-wrapper.css`
- **Purpose:** Consistent visual wrapper for embedded forms (primarily Elementor form widgets), without altering their markup or behavior.
- **Props / Fields:** `inner` (string, required — pre-rendered HTML, e.g. `do_shortcode( '[elementor-template id="123"]' )`), `heading` (string).
- **States:** default only.
- **Dependencies:** none — CSS specifically targets `.elementor-form`, `.elementor-field-group`, `.elementor-button` in addition to generic form elements so it coexists with, rather than overrides, Elementor's own output.
- **Related design tokens:** color palette, spacing scale, typography.
- **Error/success messaging:** best-effort defensive styling for `.elementor-message`/`.elementor-message-danger`/`.elementor-message-success` — could not be verified against a live form since Elementor isn't installed in this local environment; verify against staging/production. First real consumer: the Contact page (`docs/page-specs/contact.md`).

### Contact Info block

- **Location:** `template-parts/components/contact-info.php` / `assets/css/components/contact-info.css`
- **Purpose:** Structured business contact details — name, address, phone, email — inside a semantic `<address>` element.
- **Props / Fields:** none — pulls directly from `focused_schools_get_setting()` (`business_name`, `phone`, `email`, `address`), same no-args pattern as `site-footer.php`.
- **States:** omits itself entirely if the plugin is inactive or all four fields are empty; each of phone/email is independently optional.
- **Dependencies:** `focused_schools_get_setting()` (plugin helper).
- **Related design tokens:** typography, spacing, primary color (link color).
- **Notes:** `tel:` href is built by stripping everything except digits and a leading `+` from the display phone number; address line breaks use `nl2br()` after `esc_html()` (escape first, then reintroduce only the one safe tag).

### CTA Banner

- **Location:** `template-parts/components/cta-banner.php` / `assets/css/components/cta-banner.css`
- **Purpose:** Highlighted-band closing call-to-action section (e.g. "Ready to get started?"), distinct from the single-link Button component it composes internally.
- **Props / Fields:** `heading` (string, required), `description` (string), `cta_label` (string, required), `cta_url` (string, required).
- **States:** default only.
- **Dependencies:** `template-parts/components/button.php` (rendered with the `secondary` style for contrast against the banner's primary-colored background).
- **Related design tokens:** primary color background, base color text — same TEMPORARY placeholder tokens as everywhere else.

### Post Card

- **Location:** `template-parts/components/post-card.php` / `assets/css/components/post-card.css` (+ shared `card.css`)
- **Purpose:** Display a single native `post` as an excerpt card — used in `home.php`/`archive.php`'s listing loops and `single.php`'s related-posts section.
- **Props / Fields:** `post` (`WP_Post`\|int, required).
- **States:** with/without featured image; with/without excerpt (omitted if empty, not shown blank).
- **Dependencies:** none — no plugin dependency, since native posts need no custom meta.
- **Related design tokens:** typography, spacing.
- **Notes:** always renders an excerpt-style card regardless of whether the post's own content was authored with Elementor — see `docs/blog-coexistence.md` §5 for why that's correct, and its documented limitation (thin/empty excerpts possible on Elementor-authored posts with no manual excerpt set).

### Home Hero

- **Location:** `template-parts/components/home-hero.php` / `assets/css/components/home-hero.css` / `assets/js/components/home-hero.js`
- **Purpose:** Home page's own video-poster hero — distinct from `hero.php` (used by every other page), so no other page is affected by this component's markup.
- **Props / Fields:** `heading` (string, required), `subheading` (string), `poster_url` (string, theme-static image URL), `youtube_id` (string, bare 11-char video ID — same strict allowlist as `podcast-card.php`'s), `cta_label` (string), `cta_url` (string).
- **States:** poster only (no `youtube_id`) / poster + "Watch with sound" button; video modal closed (default, `hidden`) / open (real `<iframe>` injected only on click — zero YouTube network requests before that, verified live).
- **Dependencies:** `template-parts/components/button.php`.
- **Related design tokens:** color/spacing/typography scale.
- **Note:** also renders the shared `.fs-video-modal` dialog markup (only when `youtube_id` is set) — `home-hero.js` destroys the iframe on close so audio never keeps playing in the background.

### Commitment List

- **Location:** `template-parts/components/commitment-list.php` / `assets/css/components/commitment-list.css`
- **Purpose:** "How we partner" — numbered commitments list (fixed editorial copy) + one photo.
- **Props / Fields:** `eyebrow` (string), `heading` (string, required, HTML allowed via `wp_kses_post()`), `intro` (string), `image_url` (string), `image_alt` (string), `items` (array, required — each `{heading (required), body, cta_label, cta_url}`).
- **States:** stacked (mobile) / photo-beside-list (≥1024px).
- **Dependencies:** `template-parts/components/button.php`.
- **Related design tokens:** typography, spacing.

### Cycle of Excellence

- **Location:** `template-parts/components/cycle-of-excellence.php` / `assets/css/components/cycle-of-excellence.css` / `assets/js/components/cycle-of-excellence.js`
- **Purpose:** Dark (house-teal) band with an interactive 3-phase stepper.
- **Props / Fields:** `eyebrow` (string), `heading` (string, required), `body` (string), `phases` (array, required — each `{label (required), description}`).
- **States:** one phase `.is-active`/`aria-current="step"` at a time (JS-toggled visual emphasis only — every phase's full label + description is always in the markup, not hidden, so no-JS/screen-reader users get everything).
- **Dependencies:** none.
- **Related design tokens:** color (house-teal background, gold accent), typography, spacing.

### Service List

- **Location:** `template-parts/components/service-list.php` / `assets/css/components/service-list.css`
- **Purpose:** `fs_service` display integration, numbered row layout — distinct from `service-card.php`'s grid layout (used on the Services page itself).
- **Props / Fields:** `posts` (`WP_Post[]`, required).
- **States:** renders nothing if `$posts` is empty (caller decides the empty-state).
- **Dependencies:** none directly (duplicates the `_fs_service_tagline` meta key literal, same reasoning as `service-card.php` — never fatals if the plugin is deactivated). Links to `home_url('/services/#{post_name}')`, since `fs_service` has no public URL of its own.
- **Related design tokens:** typography, spacing.

### Testimonial Carousel

- **Location:** `template-parts/components/testimonial-carousel.php` / `assets/css/components/testimonial-carousel.css` / `assets/js/components/testimonial-carousel.js`
- **Purpose:** `fs_testimonial` display integration — one-slide-at-a-time carousel with prev/next arrows and dots.
- **Props / Fields:** `posts` (`WP_Post[]`, required — `post_title` = attribution, `post_content` = quote).
- **States:** renders nothing if `$posts` is empty; controls (arrows/dots/status) only render when there's more than 1 post. All slides always in the DOM (`aria-hidden` toggled, not injected/removed by JS), so no-JS/screen-reader users get every testimonial. No auto-advance timer, by design.
- **Dependencies:** none.
- **Related design tokens:** typography, spacing.

## 4. Notes

All 22 components/utilities (21 template-part components + the shared `card.css` base;
`card-grid.css` is a layout utility, not a component) use only `theme.json` CSS custom
properties (`var(--wp--preset--...)`) for color/spacing/typography — no hardcoded design
values. Every interactive element inherits the base stylesheet's `:focus-visible`
styling. No external CSS/JS framework is used.
