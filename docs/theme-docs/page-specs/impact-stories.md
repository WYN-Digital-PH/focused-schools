# Impact Stories — Landing Page Design Specification

**Page:** Impact Stories (landing)
**Template:** `page-impact-stories.php` (a Page with a template, **not** a CPT archive)
**Design system:** Focused Schools DS v1
**Source mockup:** `Focused Schools Impact Stories.dc.html` (view: *Landing page*)
**Companion spec:** `impact-story-single.md`
**Status:** Approved for development
**Last updated:** September 17, 2026

> **Naming note:** the review mockup used the working CPT name `fs_story`. The canonical name for
> development is **`fs_impact_story`**, used throughout this document and its companion. Rename in
> one place at registration; nothing in the design depends on it.

---

## 1. Overview

The hard problem on this page is not layout — it is that **new CPT stories and legacy Pages have to
sit in the same grid without looking like two systems.** The card is specified so that missing
structured data reads as an editorial choice, not a gap.

Thirteen blocks across this page and the single template. Eleven reused, one extended (the story
card), one new (the filter bar).

**The one new component** is the filter bar: DS v1 chips on the existing 120px label rail, one row
per taxonomy. **The extension** is the story card — the team card's geometry with a place/meta line,
a year badge on the image, and a source pill.

### Section hierarchy

| # | Section | Ground | Purpose |
|---|---|---|---|
| 00 | Sticky header | White card | Global nav, Impact Stories active |
| A1 | Hero | White + teal slab | The claim, not the word "Stories". CTA jumps to the grid. |
| A2 | Featured spotlight | Teal `#005C6D` | Newest Featured record. Full-bleed teal split so it reads as a statement, not the biggest card. |
| A3 | Filters + grid | Paper `#f5f6f8` | Two chip rows, live count, 3-up card grid mixing both sources, Load more. |
| A4 | Closing CTA | White | "Your district could be the next story." Primary to Contact, secondary to Services. |
| 05 | Footer | Teal | Homepage footer, verbatim |

### Component map

| Component | Source | Status |
|---|---|---|
| Sticky header / footer | Homepage, verbatim; Impact Stories active | Reused |
| Page hero (media + teal slab) | Homepage hero; serves both templates | Reused |
| Featured spotlight split | Homepage teal content/image band | Reused |
| **Filter chip bar** | **DS v1 chips on the 120px label rail** | **New** |
| **Impact Story card** | **DS v1 card + place line, year badge, source pill** | **Extended** |
| Live result count | Homepage carousel counter type | Reused |
| Load more button | DS v1 secondary pill + pagination rule | Reused |
| Empty state panel | DS v1 paper panel + secondary button | Reused |
| Closing CTA split | Homepage contact split, form replaced by buttons | Reused |

---

## 2. Hero (A1)

- Media 21:9, 8px radius, teal `#004855` fallback, 44×44 white corner bracket at top-right
  (`aria-hidden`).
- Teal slab, **660px wide**, square, `left: 24px; bottom: -56px`, 44px padding, `z-index: 2`.
- Eyebrow "Impact stories" — 12px / 700 / 0.14em / uppercase / lime `#A7CC14`.
- H1 52px / 0.98 / −0.025em / 700 white: "The measure of our work is what changed because of it."
- Support paragraph 16px / 1.55, `rgba(255,255,255,.92)`, max 500px.
- Primary coral pill "Browse Stories →" anchoring to `#stories`.
- Section padding 14px top / 128px bottom (absorbs the slab overhang).

---

## 3. Featured spotlight (A2)

### Layout

- Section label: 26×3px gold `#F3C145` rule + eyebrow "Featured story", 34px below to the card.
- Article grid `minmax(0,1fr) 560px`, ground teal `#005C6D`, 8px radius, `overflow: hidden`.
- Copy panel padding **64px 68px 68px**, vertically centered.
- Meta chips: `rgba(255,255,255,.14)`, 8px radius, 8/14px padding, 11px / 700 / 0.1em uppercase
  white — District, State, Year, in that order.
- H2 52px / 1.04 / −0.03em / 700 white, max 22ch.
- Excerpt 17px / 1.7, `rgba(255,255,255,.92)`, max 58ch.
- CTA: **white** pill "Read the Story →" (inverted primary on teal).
- Media panel: the card's own 16:9 file cropped to **4:5** in the side panel — Featured needs no
  second upload.

### Featured story rules

- **Featured** is a single true/false field on the CPT.
- Only the **newest Featured story** is spotlighted — sorted by Year DESC, then post date. No
  editor-facing ordering.
- **Two or more Featured** → newest wins; the rest stay normal cards in the grid.
- **No Featured story** → the spotlight section is **omitted entirely** and the grid moves up.
- **Legacy Pages can never be Featured**, so the spotlight always has full structured data
  (district, state, year, image, excerpt). This is deliberate: the most prominent slot on the page
  can never render a degraded record.
- The Featured story also still appears in the grid below; it is not removed from the loop.

---

## 4. Filters + grid (A3)

### 4.1 Grid header

Flex row, `align-items: flex-end`, `space-between`, 40px gap, 40px bottom margin.
Eyebrow "All stories" + H2 "Browse by state or year." at 68px / 1.0 / 700, max 18ch.
Live count at right: 11px / 700 / 0.1em uppercase `#51536a`, format **"Showing 6 of 9"**, wrapped in
`aria-live="polite"`.

### 4.2 Filter bar (new component)

- Container: 1px `#d3dde1` top and bottom rules, 28px top / 40px bottom padding, 48px to the grid.
- One row per taxonomy: `120px` label rail + `1fr` chip area, 24px gap, 18px row gap.
- Label 11px / 700 / 0.14em uppercase `#51536a`, 12px top padding to align with the first chip.
- Chip area is `role="group"` with an accessible name ("Filter stories by state").
- Chips are real `<button>`s, 12/18px padding, 999px radius, 11px / 700 / 0.08em uppercase,
  `min-height: 44px`.
- Two groups ship: **State** and **Year**. Each carries an "All" chip first.
- Chips are generated from **terms that actually exist on published stories**. An empty option is
  never offered.

### 4.3 Card metadata hierarchy

Reading order on the card, top to bottom:

| Order | Element | Treatment |
|---|---|---|
| 1 | **Image** | 16:9 at the top of the card, card clips it |
| 2 | **Year** | Badge, top-right of the image — `rgba(0,72,85,.72)`, 999px, 10px / 700 / 0.1em white, inset 16px |
| 3 | **District / School · State** | Place line, 11px / 700 / 0.1em uppercase `#51536a`, preceded by a 6×6px source dot; `min-height: 16px` reserved |
| 4 | **Title** | 24px / 1.18 / −0.02em / 700 teal, **3-line clamp** |
| 5 | **Excerpt** | 15px / 1.6 `#51536a`, **3-line clamp** |
| 6 | **Read story** | Footer left — 11px / 700 uppercase teal + coral arrow, `min-height: 44px` |
| 7 | **Source pill** | Footer right — "Story" (teal on `#eef1f3`) or "Legacy" (gray on `#f0f2f4`), 10px / 700 / 0.12em, static |

Card shell: white, 16px radius, 1px `#e3e9eb`, shadow-1, `display: flex; flex-direction: column`;
body `flex: 1 1 auto`, padding 28px / 28px / 30px; footer row pinned with `margin-top: auto` above a
1px `#eef1f2` hairline with 18px top padding.

### 4.4 Two sources, one card — field-by-field degradation

| Field | Rule |
|---|---|
| `featured_image` | New: 16:9 photograph. Legacy without one: teal tile, mark at 12%. Same card height either way — the ratio box is fixed, so rows never jag. |
| `title` | Both sources have it. 24px / 3-line clamp. **The only truly required field.** |
| `excerpt` | New: authored excerpt. Legacy: auto-excerpt trimmed to **180 characters** at a word boundary. |
| `district` / school | New: meta field. Legacy: falls back to the post title's leading phrase if parseable, otherwise the place line shows only what exists. |
| `state` | New: taxonomy term, shown after a middot. Legacy: **omitted** — the place line shortens rather than showing a dash or "N/A". |
| `year` | New: badge top-right of the image. Legacy: badge **omitted entirely**. No "Undated" label — absence is quieter than a placeholder. |
| `source` | Every card carries a pill: **Story** or **Legacy**. Neutral gray for legacy, teal for new. The dot in the place line matches (`#0A96CB` new, `#51536a` legacy). |
| `featured` | New only. Legacy records can never take the spotlight. |

### 4.5 The query & filter contract

- One `WP_Query` with `post_type: ['fs_impact_story', 'page']`, the Pages side scoped by a
  `legacy-impact-story` category or a parent-page ID.
- Ordered by **`meta year DESC, post_date DESC`** so legacy and new interleave chronologically
  rather than clumping.
- A legacy story with no State term simply does not appear under a State filter — correct behavior,
  and the empty state explains it in plain language rather than showing a dead grid.
- Chips update the URL (`?state=ma&year=2024`) so a filtered view is shareable and back-button safe.
- Filtering is progressive enhancement: **server-rendered on first paint**, then client-side via
  REST with an `aria-live` count.
- **Migration path:** as each legacy Page is backfilled with State / Year / District and converted to
  `fs_impact_story`, it gains a year badge and drops the Legacy pill **with no template change.**
  The design is built to make migration invisible.

### 4.6 Long-content & count behavior

- **Title** 24px / 700, 3-line clamp. Long district names are common, so the card is specified for
  three lines, not two.
- **Excerpt** 15px / 1.6, 3-line clamp; auto-excerpt trimmed to 180 characters when no manual
  excerpt exists.
- **Place line** reserves 16px so cards with no district still align; District and State collapse to
  a single line joined with a middot.
- **Footer row** pinned with `margin-top: auto` — Read story and the source pill line up across a
  row regardless of copy length.
- **Trailing row** of one or two cards stays left-aligned at column width. Cards never stretch.
- **Fewer than 4 stories** → the filter bar is hidden (nothing to filter) and the grid renders at
  its natural width.
- **Pagination:** nine on first paint; **Load more** (secondary pill, centered, 56px above) appends
  the next nine, retains focus, announces the new count.

### 4.7 Empty state

White panel, 1px `#e3e9eb`, 16px radius, 56/48px padding, max 62ch, left-aligned:

- Heading 24px / 700: "No stories match that combination yet."
- Body 15px / 1.7: explains that legacy stories carry less structured data than new ones, so a
  narrow filter can come back empty.
- Secondary pill: "Clear all filters ×".

Never an empty grid, and never a dead-end.

---

## 5. Closing CTA (A4)

`minmax(0,1fr) 460px`, 100px gap, vertically centered. Eyebrow "Write the next one", H2 68px / 700
max 14ch ("Your district could be the next story."), body 17px / 1.75 max 62ch, primary coral pill
to Contact + secondary pill to Services. Figure: 4:5 image, 8px radius, photo shadow.

---

## 6. Responsive behavior

Breakpoints are identical to the Team page, so both card grids on the site behave the same way:
**1240 / 1023 / 900 / 767**.

| Breakpoint | Columns | Rules |
|---|---|---|
| **Desktop ≥1280** | **3-up** | 1400px shell, 24px gutter · 32px grid gap · spotlight 1fr + 560px · card title 24px / excerpt 15px |
| **Laptop 1024–1279** | **3-up** | Spotlight stacks, media 16:9 · filter rail label moves above chips · hero slab static |
| **Tablet 768–1023** | **2-up** | 24px grid gap · hamburger nav from 1023 · card padding 28px |
| **Mobile <768** | **1-up** | 20px gutter, 20px grid gap · chip rows scroll horizontally · card padding 24/22px |

### ≤1240px

Display headings 60px, H1 46px; label rails and two-column headers collapse to one column (40/44px
gap); hero slab static with `margin-top: −45px`, 40px padding; spotlight collapses to one column
with the media at 16:9 and copy padding 44/44/48px.

### ≤1023px

Primary nav + Menu label hide; story grid → 2-up at 24px gap; CTA split → one column; footer → 2-up.

### ≤767px

- Gutters 20px; section padding 64px; header CTA hides.
- Hero media goes **4:5**; the teal slab drops its −56px offset and sits flush beneath, full-bleed
  to the 20px gutter.
- Type step: H1 34px / H2 36px / card title 22px / excerpt 14px — DS v1 mobile step, no new sizes.
- **Featured spotlight** stacks: image 16:9 on top, teal copy panel below at 28px padding. It stays
  visually distinct from the grid because it is full-bleed teal, not a card.
- **Filter bar** becomes one horizontally scrollable chip row per taxonomy with the label above it.
  Chips keep 44px tap height. No dropdowns, no accordion.
- Story cards 1-up with 20px gaps; image stays 16:9, so one file serves every breakpoint.
- Card footer keeps Read story; the source pill moves under the place line to save width.
- Load more is full-width 46px; focus stays on the button after appending, count announced via
  `aria-live`.
- Grid header stacks: eyebrow, heading, then the count on its own line.

---

## 7. Spacing specification

| Measure | Value |
|---|---|
| Container / gutter | 1400 / 24px |
| Section padding, light ground | 112–120px |
| Hero slab offset | bottom −56px, left 24px |
| Spotlight: copy / media | 1fr / 560px |
| Spotlight copy padding | 64px 68px 68px |
| Filter bar padding | 28px top / 40px bottom |
| Filter label rail | 120px + 24px gap |
| Chip gap / row gap | 10 / 18px |
| Filter bar → grid | 48px |
| Grid gap (desktop / tablet / mobile) | 32 / 24 / 20px |
| Card padding (desktop / mobile) | 28 / 24px |
| Card title → excerpt | 14px |
| Card footer padding-top | 18px |
| Grid → Load more | 56px |
| Mobile section padding | 64px |
| Anchor scroll-margin | 106px |

---

## 8. Visual styles

**Type:** DM Sans only (400 / 500 / 700 + italics).

**Color:** teal `#005C6D` · teal deep `#004855` · coral `#DD6237` · lime `#A7CC14` · cerulean
`#0A96CB` (new-source dot, focus ring) · gold `#F3C145` (featured rule) · paper `#f5f6f8` ·
hairlines `#d3dde1` / `#e3e9eb` / `#eef1f2` · body gray `#51536a` (legacy dot, legacy pill ink).

**Shape & elevation:** 8px radius on photography and the spotlight; 16px on cards, header, panels;
999px on buttons, chips, pills, badges. Hero slab square. Shadow-1 `0 1px 2px rgba(0,92,109,.08)`,
shadow-2 `0 12px 28px rgba(0,92,109,.12)`, photo `0 20px 44px rgba(0,92,109,.14)`.

---

## 9. Image guidance

- **Card / featured image 16:9**, min 1200×675. One ratio at every breakpoint — no art direction, no
  `<picture>`. Register `story-card` at 1200×675 hard crop.
- **Spotlight** reuses the same file, cropped to 4:5 in the desktop side panel and 16:9 when stacked
  — so Featured needs no second upload.
- **Story hero** 21:9, min 2400×1030. Focal interest right of center so the teal slab covers no face.
- **Subject matter:** the district's own people doing the work — classrooms, data walls, leadership
  rooms. No stock, no duotone, no grayscale, no filters, no scrims outside the hero.
- **Missing image** → teal tile with the mark at 12%. Never a stretched upscale, never a generic
  stock substitute.
- WebP with JPEG fallback, width/height on every image, lazy below the fold, alt text describing the
  moment. **Get district media-release confirmation before publishing student-visible photography.**
- All photography in the mockup is placeholder from the approved homepage retreat set; several cards
  deliberately show the no-image fallback.

---

## 10. Interaction states

| Element | Default | Hover | Selected / Focus |
|---|---|---|---|
| **Filter chip** | White, 1px `#d3dde1`, teal label | Paper `#eef1f3` + teal border, 160ms | Selected: **solid teal, white label**, `aria-pressed="true"`. Selection is never color-only — the fill inverts. |
| **Story card** | Shadow-1 | Shadow-2 + 2px rise, 240ms | Container, not a link |
| **Read story** | 11px / 700 uppercase teal, 12px gap | Label → coral, gap 12 → 18px, 240ms | The card's single link; accessible name "Read story: {Title}" |
| **Spotlight CTA** | White pill on teal | → paper `#f5f6f8`, 160ms. No rise. | Ring |
| **Load more** | Secondary pill | Border → teal, ground → paper | Focus retained after append |
| **Primary pill** | Coral | `brightness(.92)`, 160ms, no rise | Ring |
| **Source pill / year badge** | Static | **No hover** — information, not controls | n/a |
| **Active nav item** | Impact Stories: 700, 2px coral underline at 8px offset | — | `aria-current="page"` |

**Focus-visible, everything:** 3px `#0A96CB` ring at 2px offset, never removed.

**Entrances:** fade + 28px rise, 700ms `cubic-bezier(.2,.7,.2,1)`, once, max three staggered.
Intersection Observer at `threshold: 0.08`, `rootMargin: 0 0 -6% 0`, with a **900ms safety timeout**
restoring opacity. Appended cards animate the same way. Disabled under `prefers-reduced-motion`.

---

## 11. Developer implementation notes (WordPress)

- **CPT** `fs_impact_story`, label "Impact Stories", `supports: title, editor, thumbnail, excerpt`,
  `has_archive: false` (the landing Page owns the URL), rewrite slug `impact-stories`.
- **Taxonomies:** `story_state` and `story_service` (shared with the Services CPT lanes). `district`
  and `year` are **meta fields, not taxonomies** — district is near-unique and year sorts
  numerically.
- **Featured:** a single true/false field. Only the newest Featured story is spotlighted; no
  editor-facing ordering.
- **Legacy Pages:** assign `page-impact-story-legacy.php`; scope them into the landing query by
  parent ID or a hidden category. **Keep their permalinks and add canonical tags — do not redirect
  working URLs.**
- **One card part** `template-parts/cards/story-card.php`, taking a normalized array so the loop does
  not branch on post type. Write one **`fs_normalize_story($post)`** helper — it is the single place
  the two sources reconcile.
- **Filters:** URL-driven (`?state=&year=`), server-rendered first, then REST-enhanced. Chip lists
  come from terms in use only.
- **Load more:** `posts_per_page = 9`, REST append, `aria-live="polite"` count, no layout shift.
- **Anchors:** `#stories` with `scroll-margin-top: 106px`.
- **A11y:** one `h1`; card titles are `h3`; filter groups are `role="group"` with accessible names;
  count region is `aria-live="polite"`; skip link to `#main`.
- **Mockup caveats:** media are CSS background images and the view switcher bar is review chrome —
  **neither ships.** Ship real `<img>` with width/height and `loading`.

---

## 12. Assumptions & missing assets

No page copy was supplied, so every string is the designer's and replaceable. None of these need a
new component.

1. **All copy is placeholder.** The hero line reuses the approved About stats framing; story titles,
   excerpts, and the single story's body and quote are written to demonstrate structure and length
   limits.
2. **Districts are real partners** from the About page list, but the results and quotes attached to
   them are invented. Nothing is publishable without district sign-off.
3. **No legacy inventory supplied** — the count of legacy Pages and what data they carry is unknown.
   Two mockup cards model the worst realistic case: title, excerpt, no image, no year.
4. **Two filters only** (State, Year). Service lane is specified as a taxonomy and could be a third
   row; three chip rows is the practical ceiling before the bar needs a rethink.
5. **No search field.** Worth adding past roughly thirty stories; it would reuse the existing input
   styling.
6. **Legacy pill visible** on legacy cards — a deliberate, honest signal. If it should not show
   publicly, remove the pill and keep the normalization; the layout is unaffected.
7. **Year as meta, not taxonomy** — chosen for numeric sorting. If year archive URLs are needed, it
   has to become a taxonomy instead.
8. **Landing URL** is a Page with a template, not a CPT archive, so the hero and CTA copy stay
   editable. Single stories live at `/impact-stories/{slug}`.
9. **No video.** If stories should carry video testimonials, the homepage video modal component drops
   into the body block set.
10. **Photography** — every story needs one 16:9 frame minimum, ideally shot in the district.
    Placeholder tiles are acceptable at launch but not at scale.
