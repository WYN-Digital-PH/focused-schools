# Component Specifications

Status: **16 components approved and implemented.** Components are
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

Shared CSS: `assets/css/components/card.css` provides the base `.fs-card` styling reused
by Service/Team/Impact Story/Podcast/Post cards. `assets/css/components/card-grid.css` is
a shared responsive grid layout utility (not a template-part component) for arranging
repeated cards — used by the Home page's Services/Team/Impact Stories sections and the
blog archive/related-posts listings. Component JS:
`assets/js/components/site-header.js` (mobile nav toggle),
`assets/js/components/statistics-counter.js` (count-up animation),
`assets/js/components/podcast-video.js` (YouTube click-to-load).

## 3. Component Specs

### Header navigation structure

- **Location:** `template-parts/components/site-header.php` / `assets/css/components/site-header.css` / `assets/js/components/site-header.js`
- **Purpose:** Site branding, Primary nav, mobile-collapsed menu, optional header CTA.
- **Props / Fields:** none — pulls the `primary` registered nav menu and Site Settings `cta_label`/`cta_url` (gracefully empty if the plugin is inactive).
- **States:** nav collapsed (default, <1024px) / open (`.is-open`, toggled via `aria-expanded`) / always-visible (≥1024px).
- **Dependencies:** `template-parts/components/button.php` (for the optional CTA), `focused_schools_get_setting()` (plugin helper, optional).
- **Related design tokens:** color/spacing/font-size CSS custom properties from `theme.json` (all TEMPORARY placeholders — see `docs/design-system.md`).

### Footer navigation structure

- **Location:** `template-parts/components/site-footer.php` / `assets/css/components/site-footer.css`
- **Purpose:** Footer nav, social links, footer text, and copyright line.
- **Props / Fields:** none — pulls the `footer` registered nav menu and Site Settings (`facebook_url`, `linkedin_url`, `youtube_url`, `footer_text`, `copyright_name`).
- **States:** default only; social list is omitted entirely if no social URLs are set.
- **Dependencies:** `focused_schools_get_setting()` (plugin helper, optional).
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
- **Props / Fields:** `heading` (string), `body` (string, HTML allowed via `wp_kses_post()`), `image_id` (int), `image_position` (`left`\|`right`, default `right`), `cta_label` (string), `cta_url` (string).
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

## 4. Notes

All 17 components/utilities (16 template-part components + the shared `card.css` base;
`card-grid.css` is a layout utility, not a component) use only `theme.json` CSS custom
properties (`var(--wp--preset--...)`) for color/spacing/typography — no hardcoded design
values. Every interactive element inherits the base stylesheet's `:focus-visible`
styling. No external CSS/JS framework is used.
