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

All sections use the uniform shell container (`.fs-container.fs-container--shell`,
1400px) — see §9.

## 9. Precision Pass: Container Width + Card Language Alignment

A later task ("Implement the approved Services + Team page") found this page had never
been revisited after the `.dc`-source token pass that touched Home/About
(`docs/page-specs/home.md` §11) — two concrete, visible drifts were found and fixed:

1. **Container width mismatch.** `hero.php` was updated in that earlier pass to always
   render its own content in the 1400px shell container, but this page's services-grid
   section still wrapped in `.fs-container--wide` (1200px) — a real, visible misaligned
   left/right edge between the hero and the grid below it (confirmed via bounding-box
   measurement before the fix: hero at x=20/width=1400, grid at x=120/width=1200 in a
   1440px viewport). Changed to `.fs-container--shell` to match.
2. **Card language was still the pre-rebrand scaffolding.** `card.css` (the shared base
   for Service/Team/Impact Story/Podcast cards) had a comment literally flagging its own
   accent-color mapping as "TEMPORARY... revisit once docs/design-system.md defines real
   semantic role colors" — colors are now real, but the revisit never happened. Brought
   the shared base up to the exact geometry documented in `Design System v1.dc.html`'s
   "Card language" section (16px radius, 1px translucent border, shadow-1, 32px/24px
   padding, hover = shadow-2 + 2px rise + heading→coral) and fixed the Service card's
   accent treatment: a short 44×4px rounded bar (not a full-width top border) carrying
   the lane color — cerulean/coral/lime — replacing the old
   primary/accent-teal/secondary-gray mapping (`capacity` was wrongly teal, now lime).
   `page-services.css`'s empty-state message also gets a real gray (`ink-soft`) instead
   of `secondary`, which now resolves to House Teal, not gray.

**Seed data fix (not a template change):** the one existing `fs_service` post
("Leadership Development") had its `_fs_service_tagline` meta and `post_excerpt` both
set to the same long paragraph, rendering as an oversized, duplicated uppercase eyebrow.
Fixed to the real short tagline + fuller excerpt from the Home page's own `.dc`-sourced
`services` array, and seeded the 3 other real services named there (Strategic Planning,
Educator Development, Technical Assistance) that had never been created, so this page's
grid — and the Home page's own services teaser — can be QA'd against real content.

Re-verified after this pass: desktop/tablet/mobile screenshots reviewed, keyboard/focus
QA clean (skip link first, zero inaccessible links/buttons), `php -l` + PHPCS clean,
zero new PHP error-log entries, Impact Stories and Podcast pages (other consumers of the
shared `card.css` base) spot-checked live with no regression.

## 8. Rebuild Against the Approved Mockup (2026 rebrand)

The sections in §2 above describe the **pre-rebrand** build (hero → flat service-card
grid → generic CTA banner). That structure did not match the approved mockup
(`/#/services`), so this page was rebuilt against it, the same way Home and About were
rebuilt against their `.dc` design-comp files. Note that **no `.dc` file exists for this
page** — the theme root holds only `Design System v1.dc.html`, `Focused Schools
Homepage.dc.html` and `Focused Schools About.dc.html` — so the source of truth here is
the deployed mockup itself, with copy taken verbatim and geometry measured from its
computed styles.

### 8.1 Sections (in order) and component mapping

| # | Section | Component | Notes |
|---|---------|-----------|-------|
| 1 | Hero | `hero.php` (unchanged) | Eyebrow "Our services", heading "Support shaped around where your district actually is.", literal mockup copy — not `get_the_title()`/`get_the_excerpt()`, same decision as About §12. CTA "See How We Help" jumps to `#how-we-help`. |
| 2 | How we help | `section-heading.php` + `service-list.php` (both unchanged) | "Every school is different. So is every plan." The numbered index rows already existed for the Home page and already link to `/services/#{slug}` — those anchors now resolve to the lanes below. |
| 3–5 | Detail lanes (one per service) | `service-lane.php` (**new**) | One full-width band per published `fs_service`, in `menu_order`. Backgrounds and sides alternate: every second lane gets the paper tint and flips its photo to the left. |
| 6 | A Cycle of Excellence | `cycle-of-excellence.php` (unchanged) | Same component and phases as the Home page, eyebrow "The method underneath". |
| 7 | Testimonial | `testimonial-carousel.php` (unchanged) | Real `fs_testimonial` query; the section is omitted entirely when none exist. |
| 8 | Closing CTA | `cta-banner.php` (**extended**) | "Not sure which lane you need?" with two buttons. |

### 8.2 Component changes

**`service-lane.php` (new)** — the third `fs_service` display integration, alongside
`service-card.php` (grid) and `service-list.php` (index rows). Renders the accent number,
title, tagline, full body, a 2-column Signature Offerings list, a CTA, and a photo with an
optional video control and proof caption. Meta key literals are duplicated rather than
referenced, matching both sibling components, so the template degrades to empty values
instead of fataling if the plugin is deactivated.

**`cta-banner.php` (extended, additive)** — gained optional `eyebrow`, `cta2_label` and
`cta2_url`. Existing single-CTA callers (Team, Podcast, Impact Stories) are unaffected;
the new actions row simply holds one button for them.

### 8.3 Content model additions

The lanes need data the `fs_service` CPT did not carry. Three fields were added to
`FocusedSchoolsCore\Modules\Services\Meta` (keys prefixed `_fs_service_`), all sanitized
on save and again through `register_post_meta`:

- `offerings` (textarea, one per line) — the Signature Offerings list.
  `Meta::offerings_list()` splits it; the theme duplicates that split for the
  plugin-deactivated case.
- `video_url` (url, optional) — adds the play control over the photo. Omitted ⇒ no control.
- `proof` (text, optional) — the short result shown as the photo caption.

The Service Details meta box now renders every field from `Meta::all()` in a type-driven
loop (text/textarea/url), so adding a field to that schema is all it takes to surface it
in wp-admin. `accent_role` is still handled separately: it is the only field with a
constrained REST enum schema and a default.

### 8.4 Photos

Lane photos use each service's **featured image** when set. Until then the template passes
theme-bundled fallbacks (`retreat-1.jpg`, `retreat-3.jpg`, `retreat-2.jpg` — the same
photo set Home and About use) via the component's `image_url` arg, the same pattern
`hero.php` already uses. Nothing is written to `wp-content/uploads/`.

### 8.5 QA performed

`php -l` and PHPCS clean on every touched file (theme and plugin). Rendered at 1280px and
measured via computed styles: 3 lane containers with the middle one carrying
`fs-lane--reverse`, lane grid `1fr / 500px` with a 96px gap matching the mockup, 52px lane
headings, 2-column offerings, zero broken images, and no horizontal overflow
(`scrollWidth` 1265 < `innerWidth` 1280). The index-row anchors were confirmed to resolve
to the lane ids. **Not verified:** screenshot capture failed repeatedly in this
environment (browser extension timeouts), so tablet/mobile widths were not visually
reviewed — the responsive rules collapse the lane grid to one column below 1024px and the
offerings list to one column below 640px, but that has not been seen rendered.

## 8.6 Exact-Fidelity Pass Against the Mockup Folder

§8 above was built from the **deployed** mockup SPA by measuring computed styles, because
the `.dc` source for this page was believed not to exist. It does: the
`Focused Schools Mockup/` folder carries `Focused Schools Services.dc.html`, its own
authoritative `docs/page-specs/services.md`, and the mockup's real `site/assets/site.css`
and `data.js`. This pass rebuilt the lane against those literal sources.

### Accent became a design-system token (approved change)

The mockup's spec §2 and its own `data.js` agree, and the theme disagreed with both:

| Lane | Mockup | Was |
|---|---|---|
| Strategy and Vision | cerulean `#0A96CB` | coral ❌ |
| Leadership and Systems | coral `#DD6237` | cerulean ❌ |
| Capacity and Coaching | lime `#A7CC14` | lime ✅ |

Strategy and Leadership were swapped. Rather than swap two CSS rules, the field now stores
what the spec says it stores — a **token key** (`cerulean`/`coral`/`lime`/`teal`), not a
lane name. Colour is no longer coupled to lane identity, so a fourth service picks any
accent with no CSS or template change. The fallback is teal. `Meta::LEGACY_ACCENTS` maps
the three old lane names forward so existing records resolve correctly rather than
silently going teal.

### Accent placement is now enforced

The accent appears in exactly three places and nowhere else: the 26×3px `.fs-rule` bar
beside the lane numeral, the 6×6px square offering bullets, and the caption kicker. It
never sets body copy, headings, taglines, buttons or section grounds. **The lime
exception** is codified in the component, not left to the editor: lime fails contrast for
type, so in the Capacity lane the kicker falls back to cerulean while bar and bullets stay
lime.

### Geometry taken from the mockup's own site.css

Lane grid `1fr / 500px` at 96px gap (≥1241px); title 52px / 1.04 / −0.03em capped at 18ch,
stepping to 44px at 1024–1240px; tagline 21px at 30ch; body 17px at 62ch; offerings rule
at 30px padding-top with 16px/40px gaps and a 22px label gap; photo 4:5 with an 8px radius
and `0 20px 44px rgba(0,92,109,.14)`; `scroll-margin-top: 106px` on each lane anchor.

### Structural corrections

- **Floating caption chip.** The proof caption was a static block under the photo; it is
  now an absolutely-positioned white card breaking the photo's outer edge
  (`right: -16px; bottom: 32px`, 252px max), mirrored to the left when the lane flips.
- **Square bullets.** Were 6px discs, now 6×6px squares.
- **Accent bar + numeral row.** Did not exist. The numeral is 11px/500/0.1em in ink, not a
  display figure.
- **Offerings columns.** Two columns only from four items up; one column below that.
- **Missing featured image.** The figure is now omitted entirely and the copy column spans
  full width at a 70ch measure, instead of rendering a grey placeholder block.
- **Flip modifier** renamed `fs-lane--reverse` → `fs-lane--flip`, matching the mockup.
- **`.fs-rule` utility** added to `style.css` with cerulean/coral/lime/teal/gold/rasp
  modifiers, since the design system uses it beyond this page.

### QA performed

`php -l` and PHPCS clean. Rendered output verified: accent bars cerulean/coral/lime in
order, lane 2 carrying `fs-lane--flip` and its caption mirrored left, 6 bullets per lane in
the right accent, and the Capacity lane's kicker resolving to cerulean while its bullets
stay lime. Computed styles at 1163px confirmed the 1024–1240px tier exactly: 44px title,
26×3px rule, 6×6px square bullets at `border-radius: 0`, caption `absolute` at
`right:-16px / bottom:32px / max-width:252px`, photo `4/5` at `8px`, `scroll-margin-top:
106px`, no horizontal overflow.

**Not verified:** the browser window in this environment would not grow past 1163px, so
the ≥1241px desktop tier (1fr/500px at 96px gap, 52px title) was confirmed by reading the
media block out of the CSSOM rather than by rendering it. Mobile (<768px) was not
rendered either.
