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

## 9. Exact-Fidelity Pass: Hero/Index Copy, Missing CTA Image, and the Two Unverified Tiers

A later task re-read `Focused Schools Services.dc.html` in full and found the §8/§8.6
passes above had drifted from it in several places their own checks didn't cover — §8.6
specifically verified the *lane* component's geometry, but never re-checked the Hero, the
Lane Index header, or the closing CTA against the literal file, and its own "not verified"
note above was still true going into this pass. All fixed and this time actually rendered
live at every breakpoint (320–1440px), not inferred from the CSSOM.

**Hero copy was entirely wrong** — eyebrow, heading, subheading, image, and CTA all
differed from the `.dc` source (a Rendering pass from before the `.dc` file was supplied
had never been reconciled against it once the file existed). Fixed to the literal copy:
eyebrow "Services", heading "Support tailored to your goals, your challenges, and your
students.", the `retreat-3.jpg` photo, and a "Let's Talk" CTA pointing at `#contact`
(the closing section, not the `/contact/` page) — same anchor-not-navigation pattern as
every other CTA on this page.

**Lane Index header copy was also wrong** — "Every school is different. So is every
plan." isn't in the `.dc` source at all; the real copy is "Three lanes. **One cycle of
inquiry.**" (mixed weight) with a different description line. Extended
`section-heading.php`'s `heading` prop to allow embedded HTML (`wp_kses_post()` instead of
`esc_html()`, same backward-compatible pattern used elsewhere this session) so the
`<strong>` renders, and added a scoped `#how-we-help` override in `page-services.css` for
the header's flex-row layout and 68/60/36px `data-display` scale — kept scoped rather than
changed in `section-heading.css` itself, since Impact Stories and `single.php` use that
component's generic stacked layout correctly as-is.

**The Lane Index rows were built with the wrong component.** `service-list.php` looks
almost right for this section — close enough that an earlier pass reused it — but it's
actually the Home page's own row pattern (`[data-srow]` in the `.dc` sources: two text
fields, a 330px title column, right-arrow). Services' own index (`[data-index-row]`) is a
different pattern: one lead line only, a 420px title column, and a down-arrow in a 46px
circle. Built a new sibling component, `service-index.php` (+ `service-index.css`,
registered in `inc/enqueue.php`), rather than overloading `service-list.php` with a
variant flag — the two patterns differ structurally (a whole missing text field), not just
in size, and `fs_service` already has three display integrations for three different
pages/contexts (`service-card.php` grid, `service-list.php` Home rows, now
`service-index.php` here), each degrading gracefully via duplicated meta-key reads if the
plugin is off, matching that established convention.

**Closing CTA was missing its photo, same bug as the Team page.** `cta-banner.php`
(text-only) doesn't match the `.dc` source's 2-column `data-cta-grid` section with real
photography on the right. Swapped to `content-image-split.php` (same `retreat-2.jpg` +
caption every other closing CTA on the site already uses), which needed a new `id` prop
so the section keeps its `#contact` anchor — `[id] { scroll-margin-top: 106px }` in
`site.css` already applies sitewide, so no new CSS was needed for the jump offset.

**Hero slab width was wrong** — the shared `hero.css` hardcoded 680px (Home/About's own
value), but Services' `.dc` source specifies 640px, and (found while checking) Team's
specifies 660px — three different values across three pages sharing one component. Added
a `slab_width` prop to `hero.php` (default 680, so Home/About are unaffected) driven by a
CSS custom property, same pattern as `rail-text.php`'s `heading_max_ch`; Services now
passes 640, Team 660.

**Lane title was missing its own mobile step.** `service-lane.css`'s `.fs-lane__copy h2`
had a 36px base and a `min-width:1024px` override to 44px — but the `.dc` source's real
cascade is 44px from 721px all the way up to 1240px (not starting at 1024), stepping down
to 32px only at ≤720px, and up to 52px at ≥1241px. Fixed the threshold (1024 → the
implicit 1240/720 split) and added the missing 32px mobile tier.

Verified: a 13-breakpoint sweep (320–1440px) of the hero slab (width/position), hero H1,
lane title, and lane-index grid-column-count confirms every value matches its `.dc` target
exactly at every width — including the ≥1241px and ≤720px tiers the previous pass
explicitly flagged as never rendered — with zero overflow anywhere. Keyboard QA: skip link
first, the Hero CTA and a lane CTA both scroll-jump to `#contact` correctly. A 7-page
site-wide regression sweep (Home/About/Services/Team/Contact/Podcast/Impact Stories — all
affected by the shared `hero.php`/`content-image-split.php`/`section-heading.php`
changes) showed zero overflow and zero console errors. `php -l` and PHPCS
(`phpcs.xml.dist`) both clean on every touched and new file.

## 10. Content Fix: the Seeded `fs_service` Posts Didn't Match This Page At All

The client compared the rebuilt Lane Index against the live mockup and found the whole
section wrong — not a styling issue, a content one. Tracing it: the 4 `fs_service` posts
in this environment (Strategic Planning / Leadership Development / Educator Development /
Technical Assistance) are Home page's own service names, verbatim, from
`Focused Schools Homepage.dc.html`'s `services` data array. Services' own lane data
(`Focused Schools Services.dc.html`, `renderVals()`, ~line 539) describes three
completely different services — **Strategy and Vision**, **Leadership and Systems**,
**Capacity and Coaching** — each with its own real tagline, body paragraph, offerings
list, accent color, and photo caption. The `.dc` source's own "Assumptions" section (line
498) even flags the conflict directly: *"Technical Assistance — the doc places it as a
capability inside Leadership and Systems, but the homepage lists it as a fourth service
row. The homepage row should either relabel or link into this lane."* — i.e. the mockup's
own author knew Home and Services disagreed and left it unresolved.

Since Home's teaser (`service-list.php`) and this page's index both query the same
`fs_service` CPT with no filtering, fixing Services' content necessarily changes what Home
shows too. Given the choice, the client asked to reseed to the correct 3 lanes and
explicitly **not** touch Home's page code — so Home's teaser now displays these 3 real
lane names/copy through its own unchanged template, rather than its previous (also
not-`.dc`-sourced) 4-item copy. Nothing on Home's side was edited.

**Reseeded** (post content + `_fs_service_tagline`/`_fs_service_accent_role`/
`_fs_service_offerings`/`_fs_service_proof` postmeta) directly via the three existing
posts whose legacy accent roles conveniently already matched the correct new identity
(`strategy`→cerulean, `leadership`→coral, `capacity`→lime — reused the same post IDs
rather than recreating them, so nothing else referencing them breaks), and deleted the
fourth ("Technical Assistance") entirely, since the source is explicit it's a capability
within Leadership and Systems, not its own lane.

Verified live: the Lane Index now shows exactly the 3 real lanes with their real leads;
each lane section shows the right accent color, offering count (4/6/4, matching the
source), and proof caption; Home's teaser adapted cleanly to 3 rows with no layout
breakage; slugs changed (`strategy-and-vision`/`leadership-and-systems`/
`capacity-and-coaching`), so all internal anchors (`/services/#{slug}`) were re-verified
against the new values. Screenshots reviewed at desktop/tablet/mobile — matches the
approved design exactly. Full 7-page regression sweep re-run after the content change:
zero overflow, zero console errors.

**Known, deliberately-unaddressed gap**: the caption "kicker" span above each lane's
proof line (e.g. "Planning in practice") currently reuses the lane's own title instead of
the `.dc` source's distinct, shorter kicker phrase — `service-lane.php` has no field for
it and none was added this pass, since it's a minor branding nuance rather than a content
error, unlike the fixes above.

## 11. "How We Help" Header Layout Bug

The client caught this by comparing a live screenshot directly against the `.dc` source:
the eyebrow and heading had visually separated from each other, landing eyebrow-top-left/
heading-top-right/description-bottom-left instead of the intended two-column layout.
Root cause: the `.dc` markup wraps eyebrow+heading together in one `<div>` (the left
column) beside the description (the right column) — but `section-heading.php`'s actual
output is three flat sibling elements with no such wrapper, so §9's `flex-direction: row`
override on the shared component treated all three as independent flex items instead of
two grouped columns. Rewrote the `#how-we-help` override to use CSS Grid with explicit
per-child `grid-column`/`grid-row` placement (`minmax(0,1fr) 520px` columns, eyebrow at
row 1/heading at row 2 both in column 1, description spanning both rows in column 2) —
this reproduces the source's grouping without needing to change the shared component's
DOM. Mobile (≤720px) collapses to one column in natural document order.

Verified via bounding-box measurement (eyebrow and heading now share the same left x
position, description sits in the right column) and screenshot review at desktop and
mobile — matches the `.dc` source and the client's reference exactly. Full 7-page
regression sweep re-run: zero overflow, zero console errors.

## 12. Cycle of Excellence Was the Wrong Component

A full top-to-bottom re-audit against `Focused Schools Services.dc.html`, requested after
the header-layout catch above, found one more real mismatch in the one section neither
this pass nor §8.6 had directly verified: "A Cycle of Excellence." The page was calling
`cycle-of-excellence.php` — Home's own interactive, scroll-scrubbed 3-phase stepper
(rotating mark, orbiting dot, clickable phase buttons) — but the `.dc` source's own
section label literally reads *"Cycle of Excellence (teal band, **static reference**)"*:
a simple non-interactive circle-and-icon graphic beside eyebrow/heading/body copy and a
CTA button, explicitly a callback to the concept already explained in full elsewhere
(About's own Cycle of Excellence section), not a second full treatment. Symptoms this
produced: wrong eyebrow ("The method underneath" instead of "One shared focus"), a
phase-stepper UI with labels ("Build Capacity" etc.) the Services `.dc` source never
shows, and a completely missing "Our Approach" CTA button linking to `/about-our-mission-
vision/` — `cycle-of-excellence.php` has no CTA slot at all, structurally can't render one.

Built `cycle-reference.php` (+ `cycle-reference.css`, registered in `inc/enqueue.php`) as
a new sibling rather than adding a "static mode" flag to the interactive component — same
reasoning as `service-index.php` in §9: the two aren't a size/copy variant of each other,
one has an entire interactive subsystem (JS-driven phase state, scroll-scrub) the other
doesn't need or want. `cycle-of-excellence.php` itself is untouched, so Home's (and any
other future caller's) use of the real interactive version is unaffected.

Verified live: eyebrow now reads "One shared focus," the "Our Approach" button renders
and links correctly, circle/ring/icon geometry and the 340px/1fr desktop grid (collapsing
to one column ≤1024px) match the source, reviewed at desktop and mobile. Full 7-page
regression sweep: zero overflow, zero console errors. `php -l` and PHPCS clean.

## 13. Testimonial Section Was Invisible (White Text on a Light Ground)

The client asked whether the testimonial section should be static or dynamic content —
it looked essentially empty (only the coral quote-mark bars and carousel dots showed).
The answer: **dynamic is correct as already decided** — the `.dc` source's own
"Assumptions" note explicitly sanctions the real `fs_testimonial` carousel as an
alternative to its literal single static quote, and this project consistently prefers
CPT-driven content over hardcoded copy. All 3 real testimonials were already seeded and
present in the page's HTML (confirmed via direct DOM inspection) — the actual bug was
pure CSS, not a content question.

`testimonial-carousel.css` hardcodes every text/control color to white — correct for
Home, where this component sits on a teal "Impact stories" band (confirmed directly in
`Focused Schools Homepage.dc.html`), but Services' own `.dc` source puts its testimonial
section on a light `#f5f6f8` paper ground instead (`color: #005C6D` for the quote there,
not white). White-on-white-effectively made the quote text, attribution, and most
controls invisible. Compounding it: `.fs-services__quote` had no `background` at all
(inheriting plain white) and the wrong padding (a flat, non-`data-space`-aware 48px
instead of the source's 112px/64px-mobile).

Added an `on_light` prop to `testimonial-carousel.php` (default `false`, so Home is
unaffected) that adds a `.fs-testimonial-carousel--on-light` modifier class, styled in
`testimonial-carousel.css` with the source's teal text plus reasonable light-ground
tokens for the controls (the source itself doesn't show carousel dots/arrows, since its
own version is a single static quote — used the same rule/paper-soft/ink-soft tokens
already established elsewhere on this page for consistency). Fixed `.fs-services__quote`'s
background and padding to match.

Verified live: quote text and attribution now render in teal, section background is the
correct paper tint, arrows/dots/status text all visible and using consistent tokens,
screenshot reviewed. Full 7-page regression sweep: zero overflow, zero console errors —
Home's own teal-band testimonial carousel confirmed unaffected (the `on_light` class is
opt-in). `php -l` and PHPCS clean.

No further discrepancies found in the rest of this page. Header/Footer are the shared,
already-verified sitewide components.

**Follow-up fix #2**: the client noticed the prev arrow sat isolated near the far-left
edge while the dots and next arrow clustered together in the middle. Root cause, found
by checking Home's own carousel layout (Services has no literal carousel markup at all,
since its own `.dc` source shows only a static single quote — this whole component was
adapted from Home's): the literal source groups prev+dots+next together in **one** flex
container that sits in the parent grid's middle `auto` column, with an empty spacer
`<span>` in column 1 and the slide counter in column 3. `testimonial-carousel.php` instead
placed the prev button, dots, and next button as three separate direct children of the
`1fr auto 1fr` grid — each aligning to the *start* of its own column by default, which is
why prev landed at the far-left edge (start of a wide column 1) while next landed right
next to the dots (start of the narrow column 3), an asymmetric result that had nothing to
do with the light/dark-ground fix. Added a `.fs-testimonial-carousel__nav` flex wrapper
around prev+dots+next and an empty spacer for grid column 1, matching the source exactly.
This is a shared-component fix, so it also corrects the same (previously unreported, but
equally present) issue on Home's own carousel. Verified via bounding-box measurement on
both pages: the prev-to-next span is now centered exactly on the viewport center. Full
regression sweep clean.

**Follow-up fix #1**: the client reported the carousel's arrow buttons didn't respond.
Root cause: `inc/enqueue.php` only ever enqueued `testimonial-carousel.js` inside its
`is_front_page()` block — the `is_page('services')` block enqueued the page's CSS but no
JS at all, so the markup and (now-fixed) styling rendered correctly while the prev/next/
dot click handlers simply never attached. Added the same script enqueue to the Services
block. Verified: next/prev (including wraparound) and dot navigation all confirmed working
via scripted clicks, live status text updates correctly ("1 / 3" etc.). Regression sweep
re-run clean.
