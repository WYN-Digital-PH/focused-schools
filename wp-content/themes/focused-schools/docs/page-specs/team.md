# Team — Page Design Specification

**Page:** Team / Meet Our Team
**Template:** `page-team.php`
**Design system:** Focused Schools DS v1
**Source mockup:** `Focused Schools Team.dc.html`
**Status:** Approved for development
**Last updated:** September 17, 2026

---

## 1. Overview

The Team page is a roster page: five sections, of which one — the queried card grid — is the page.
Everything else frames it.

Nine blocks in total: eight reused from the approved homepage / DS v1, one **extended** (the team
card gains a quote block and a footer action row).

**Client workflow is three steps:** Team → Add New → Publish. The grid is a query. Nothing on this
page is positioned by hand, and there is no page edit or block insertion when a person joins or
leaves.

### Section hierarchy

| # | Section | Ground | Purpose |
|---|---|---|---|
| 00 | Sticky header | White card | Global nav, Team active |
| 01 | Hero — educators first | White + teal slab | The differentiator is the H1. Eyebrow names the page; CTA jumps straight to the roster (`#roster`). |
| 02 | Intro — why that matters | White | Homepage belief rail. One heading, one paragraph, one secondary CTA. No stats, no cards. |
| 03 | Team grid — the page | Paper `#f5f6f8` | Queried card grid with a live count. Nine on first paint; Load more appends nine. |
| 04 | Bio modal | Overlay | Full quote, full bio, LinkedIn. One dialog serving every card. |
| 05 | Closing CTA | White | Approved "Get to Know Us." split. Primary to Contact, secondary to Impact Stories. |
| 06 | Footer | Teal | Homepage footer, verbatim |

### Component map

| Component | Source | Status |
|---|---|---|
| Sticky header | Homepage header, Team active + coral underline | Reused |
| Page hero (media + teal slab) | Homepage hero, video console removed, 21:9 media | Reused |
| Intro rail | Homepage "What we believe", scroll-hold removed | Reused |
| Grid header + live count | Homepage section header with the carousel counter type | Reused |
| **Team card** | **DS v1 team card + quote block + bio/LinkedIn footer row** | **Extended** |
| Load more button | DS v1 secondary pill, DS v1 pagination rule | Reused |
| Bio modal | Homepage video modal shell, media + copy split | Reused |
| Closing CTA split | Homepage contact split, form replaced by buttons | Reused |
| Footer | Homepage footer, verbatim | Reused |

**The extension:** DS v1's team card gains a quote block (lime 2px rule, 16px italic, 3-line clamp)
and a card footer row holding Read bio and LinkedIn. Geometry, ratio, radius, border, shadow, and
hover are unchanged — the quote sits in the existing 16px internal stack.

---

## 2. Hero

- Media: 21:9, `border-radius: 8px`, teal `#004855` fallback ground, with a 44×44 white corner
  bracket rule at top-right (`aria-hidden`).
- Teal slab `#005C6D`, **660px wide**, square (no radius), absolutely positioned `left: 24px;
  bottom: -56px`, `z-index: 2`, 44px padding.
- Eyebrow: "Meet our team" — 12px / 700 / 0.14em / uppercase / lime `#A7CC14`.
- H1: 52px / 0.98 / −0.025em / 700 / white. "We're educators first. That's what makes us
  different."
- Support paragraph: 16px / 1.55, `rgba(255,255,255,.92)`, max 500px.
- CTA: primary coral pill, 48px, "See the Team →", anchors to `#roster`.
- Section padding: `14px` top / `128px` bottom (the bottom absorbs the slab overhang).

## 3. Intro

- 264px label rail + `1fr` content, `96px / 112px` section padding.
- Rail: 28px brand mark + 12px uppercase label "Who you'll work with", max 9ch.
- H2: 68px / 1.02 / −0.03em, weight **400** with the emphasized clause at **700** via `<strong>`
  in the same element. Max 18ch.
- Body: 19px / 1.65, `#51536a`, max 890px.
- One secondary pill: "Explore Our Services →".

---

## 4. Team Grid

### 4.1 Grid header

- Flex row, `align-items: flex-end`, `justify-content: space-between`, 40px gap, 56px bottom
  padding.
- Eyebrow "The team" + H2 "Meet Our Team" at 68px / 1.0 / 700.
- Live count at the right: 11px / 700 / 0.1em / uppercase `#51536a`, format **"Showing 6 of 9"**,
  wrapped in `aria-live="polite"` so appends are announced.

### 4.2 Card data model — `fs_team` CPT

| Field | Required | Card rendering |
|---|---|---|
| `post_title` (**Name**) | Yes | 22px / 700 / −0.02em, **2-line clamp**. Modal heading at 34px. |
| `position` (**Position**) | Yes | 15px / 1.5 gray, **3-line clamp**, `min-height: 45px` reserved slot for row alignment. |
| `_thumbnail_id` (**Headshot**) | Yes | 4:5 at the top of the card, no radius of its own (the card clips it), 900×1125 min. |
| `quote` (**Quote**) | Yes* | 16px italic teal, lime 2px left rule at 16px inset, **3-line clamp**. Full text in the modal at 21px. |
| `bio` (**Bio**) | Optional | Renders the Read bio button and the modal body. |
| `linkedin` (**LinkedIn**) | Optional | 44px paper tile at the right of the footer row. |
| `menu_order` | — | Grid order; alphabetical by name as the fallback. |

\* Specified as required, but the card renders correctly without it (see 4.5), so the field can be
relaxed to optional without a redesign.

### 4.3 Card construction

- `display: flex; flex-direction: column`, white ground, `border-radius: 16px`, 1px `#e3e9eb`,
  `overflow: hidden`, shadow-1 `0 1px 2px rgba(0,92,109,.08)`.
- Grid uses `align-items: stretch`; the card body is `flex: 1 1 auto` and the footer row is pinned
  with `margin-top: auto`.
- Internal stack: name → 6px → position → 20px → quote → 22px → footer hairline → 18px → actions.
- Footer row: `justify-content: space-between`, 1px `#eef1f2` top hairline. Read bio at left,
  LinkedIn tile at right.
- Headshot fallback tile: teal `#005C6D`, brand mark at **12%** opacity at 58% height, plus a
  "Portrait pending" chip — `rgba(255,255,255,.16)`, 999px radius, 10px / 700 / 0.12em uppercase,
  inset 16px from bottom-left.

### 4.4 Long-content rules

- **Long name** — 22px / 700 / −0.02em wraps to two lines, then clamps with an ellipsis. Full name
  stays in the modal heading at 34px and in the card's accessible name. **Names never shrink** —
  no auto-fit type.
- **Long position** — 15px / 1.5, wraps to three lines then clamps. The slot reserves 45px (two
  lines) so one-line and two-line positions still align across a row.
- **Long quote** — 16px italic, 3-line clamp (~150 characters). Full text lives in the modal at
  21px. Editors get a **180-character soft guide** in the field description.
- **Row alignment** — cards stretch to the tallest in the row; the footer row is pinned with
  `margin-top: auto`, so Read bio and LinkedIn always line up horizontally across a row regardless
  of copy length.
- **Trailing row** — a row of one or two cards stays left-aligned at column width. Cards never
  stretch to fill.
- Character guides in field descriptions: **name 40, position 70, quote 180.**

### 4.5 Optional-field states

- **No quote** → block omitted entirely, no empty rule. The footer row stays pinned to the bottom,
  so the card still aligns.
- **No bio** → Read bio button omitted, card is not clickable, LinkedIn keeps its position at the
  right.
- **No LinkedIn** → icon omitted; Read bio stays left-aligned.
- **Neither bio nor LinkedIn** → the footer row and its hairline are omitted; the card ends after
  the quote with 30px bottom padding.
- **No headshot** → teal tile, mark at 12%, "Portrait pending" chip. DS v1 rule: once real
  portraits land, placeholders and photographs must not mix in one grid.
- **Empty roster** → the section renders its header and a single paper panel reading "Team members
  coming soon." **Never an empty grid.**

### 4.6 Pagination

- Nine records on first paint; **Load more** (secondary pill, 48px, "Load more ↓") appends the next
  nine.
- Button is centered with 56px top padding; omitted entirely when the roster fits on one page.
- After append, focus stays on the button and the new count is announced via the header's
  `aria-live="polite"` region.

---

## 5. Bio modal

- Fixed overlay, `z-index: 110`, 24px padding, scrim `rgba(0,72,85,.72)` as a full-bleed close
  button. Opacity + visibility transition at 260ms.
- Dialog: `role="dialog" aria-modal="true"`, `width: min(1000px, 92vw)`, `max-height: 88vh`,
  internal scroll, white, 16px radius.
- Body grid: **340px portrait + `1fr` copy**, copy padding 40px / 44px.
- Heading 34px / 1.08 / 700; position 15px gray beneath.
- Quote: 21px / 1.45 italic teal with the same lime 2px rule at 20px inset. Omitted when empty.
- Bio: 16px / 1.75, max 62ch; may run several paragraphs — the modal scrolls, the page behind it
  does not.
- Close: 44×44 tile, 10px radius, `#f0f2f4` → `#e3e8ea` on hover, top-right of the copy column.
- Secondary pill: "Connect on LinkedIn →", shown only when the field is set.

---

## 6. Responsive behavior

Breakpoints match DS v1 exactly: **1240 / 1023 / 900 / 767**. The card ratio never changes at any
breakpoint, so no art direction and no `<picture>` element is needed.

| Breakpoint | Columns | Rules |
|---|---|---|
| **Desktop ≥1280** | **3-up** | 1400px shell, 48px gutter behavior · 32px grid gap · card padding 28px · name 22px / position 15px / quote 16px |
| **Laptop 1024–1279** | **3-up** | Fluid shell, 32px gutter · 32px grid gap · card padding 28px · hero slab static, media 21:9 |
| **Tablet 768–1023** | **2-up** | 24px grid gap · hamburger nav begins at 1023 · card padding 28px · modal becomes single column, portrait crops to 16:9 |
| **Mobile <768** | **1-up** | 20px gutter, 20px grid gap · card padding 24/22px · Load more full-width 46px · name 21px / position 14px / quote 15px |

### ≤1240px

- Intro rail collapses to one column, 40px gap.
- Section headers collapse to one column, 44px gap.
- Hero slab becomes static and full-width, `margin-top: −45px`, 40px padding.
- Display headings step to 60px; H1 steps to 46px.

### ≤1023px

- Primary nav and the Menu text label hide; mark + burger remains.
- Team grid → 2-up, 24px gap.
- Closing CTA split → one column, 48px gap. Footer → 2-up, 48px gap.
- Modal body → one column, portrait `aspect-ratio: 16 / 9`, gap 0.

### ≤767px

- Gutters 20px; section padding 64px.
- Hero media goes **4:5**; the teal slab drops its −56px offset and sits flush beneath, full-bleed
  to the 20px gutter. Header "Let's Talk" CTA hides.
- Type step: H1 34px / H2 36px / name 21px / position 14px / quote 15px — the DS v1 mobile step,
  no new sizes.
- Grid header stacks: eyebrow, heading, then the "Showing 6 of 9" count on its own line (20px gap).
- Cards 1-up with 20px gaps; padding 28px → 24/22px. The 4:5 portrait is unchanged, so one file
  serves every breakpoint.
- Hover is a no-op on touch; the whole card is **not** a link — Read bio is the button, so tapping
  a portrait never navigates by accident.
- Read bio and the LinkedIn tile are both 44px minimum tap targets and sit at opposite ends of the
  footer row.
- Load more is full-width at 46px, keeps focus on the button after appending; new cards announced
  via `aria-live`.
- The bio modal becomes a full-width sheet: portrait crops to 16:9 at the top, copy below, padding
  28px, close button stays 44px in the top-right.
- Intro rail: label row stacks above the heading with a 40px gap.

---

## 7. Spacing specification

| Measure | Value |
|---|---|
| Container / gutter | 1400 / 24px |
| Section padding, light ground | 112–120px |
| Hero slab offset | bottom −56px, left 24px |
| Hero slab width | 660px |
| Intro rail: label / content | 264px + 1fr |
| Grid header → grid | 56px |
| Grid gap (desktop / tablet / mobile) | 32 / 24 / 20px |
| Card padding (desktop / mobile) | 28 / 24px |
| Name → position | 6px |
| Position → quote | 20px |
| Quote → footer hairline | 22px |
| Footer row padding-top | 18px |
| Quote left rule inset | 16px, 2px lime |
| Grid → Load more | 56px |
| Modal: media / copy | 340px + 1fr, padding 40/44px |
| Mobile section padding | 64px |
| Anchor scroll-margin | 106px |

---

## 8. Visual styles

**Type:** DM Sans only (400 / 500 / 700 + italics), preconnected, `display=swap`.

**Color:** teal `#005C6D` (ink, slab, footer) · teal deep `#004855` (media fallback) · coral
`#DD6237` (primary, active nav, arrows) · lime `#A7CC14` (hero eyebrow, quote rule, footer hover) ·
cerulean `#0A96CB` (focus ring) · paper `#f5f6f8` (grid ground, chip/tile fill) · hairlines
`#d3dde1` / `#e3e9eb` / `#eef1f2` · body gray `#51536a`.

**Shape & elevation:** radius 8px (photography, LinkedIn tile), 16px (cards, header, modal), 999px
(buttons); the hero slab is square by intent. Shadow-1 `0 1px 2px rgba(0,92,109,.08)`, shadow-2
`0 12px 28px rgba(0,92,109,.12)`, photo `0 20px 44px rgba(0,92,109,.14)`, header
`0 2px 12px rgba(0,92,109,.08)`. Footer watermark mark at 6% opacity, `aria-hidden`.

---

## 9. Image guidance

- **Headshot ratio 4:5**, min 900×1125, upload at 1200×1500. One ratio at every breakpoint — no art
  direction, no `<picture>`.
- **Crop** top-weighted: eyes on the upper third, shoulders in frame, 8–12% headroom. Register a
  `team-card` image size (900×1125, hard crop) so editors cannot break the ratio.
- **Consistency is the whole job** — same lighting direction, same background value, same distance
  across the roster. A single off-set portrait is more visible here than anywhere else on the site.
- **Background:** plain, light, uncluttered. No office-window backlight, no on-stage crops, no
  group-photo cutouts.
- **Hero** 21:9, min 2400×1030 — a working-team photograph, not a lineup. Focal interest right of
  center so the slab covers no face.
- WebP with JPEG fallback, explicit `width`/`height`, lazy below the fold, alt text = **"Portrait of
  {Name}, {Position}"**.
- No headshots were supplied, so every card in the mockup shows the DS v1 fallback tile and the
  hero is a homepage placeholder.

---

## 10. Interaction states

| Element | Rest | Hover | Focus / behavior |
|---|---|---|---|
| Card | Shadow-1 | Shadow-2 + 2px rise, 240ms | Container, **not a link** — no whole-card click target |
| Read bio | 11px / 700 uppercase teal, 12px gap | Label → coral, gap 12 → 18px, 240ms | Real `<button>` with `aria-haspopup="dialog"`; ring |
| LinkedIn tile | Paper, 1px `#e3e9eb`, teal glyph | Ground → teal, glyph → white, 160ms | Accessible name "{Name} on LinkedIn"; new tab, `rel="noopener"` |
| Load more | Secondary pill | Border → `#005C6D`, ground → paper | Focus stays on the button after append; count announced `aria-live="polite"` |
| Primary pill | Coral | `filter: brightness(.92)`, 160ms, no rise | Ring |
| Secondary pill | White, 1px `#d3dde1` | Border → teal, ground → paper | Ring |
| Nav link | 500 weight | Color → coral | — |
| Active nav item (Team) | 700 weight, 2px coral underline at 8px offset | — | `aria-current="page"` |
| Modal close / scrim | — | `#f0f2f4` → `#e3e8ea` | Escape closes |

**Focus-visible, everything:** 3px `#0A96CB` ring at 2px offset. Never removed.

**Modal:** focus moves to the dialog heading on open, is trapped inside, Escape closes, and focus
returns to the triggering Read bio button. Background scroll is locked.

**Entrances:** fade + 28px rise, 700ms `cubic-bezier(.2,.7,.2,1)`, once per element, staggered max
three items. Intersection Observer at `threshold: 0.08`, `rootMargin: 0 0 -6% 0`, with a **900ms
safety timeout** restoring opacity so nothing can be stranded transparent; elements already above
the fold on load are never hidden, and newly appended cards are registered on update. Fully disabled
under `prefers-reduced-motion: reduce`.

---

## 11. Developer implementation notes (WordPress)

- **CPT** `fs_team`, label "Team", `supports: title, thumbnail, page-attributes`,
  `has_archive: false`, `publicly_queryable: false`. Publishing is the only step — no page edit, no
  block insertion.
- **Fields:** `post_title` = Name; `position` (text, required); `_thumbnail_id` = Headshot
  (required); `quote` (textarea, required); `bio` (wysiwyg, optional); `linkedin` (url, optional).
- **Order:** `menu_order ASC, title ASC`. Drag-to-reorder via a menu-order plugin or the Page
  Attributes box; alphabetical is the fallback so an unset order is never random.
- **Query:** `posts_per_page = 9` on first paint; Load more appends the next nine over REST
  (`/wp-json/fs/v1/team?page=n`). The full roster is server-rendered when JS is off.
- **One template part** `template-parts/cards/team-card.php`, used by this page, the About teaser,
  and any future use. Optional fields are wrapped in `if` guards exactly as specified in 4.5.
- **Modal:** one dialog element for the page; content populated from the card's data attributes (or
  fetched per member if bios are long). Native `<dialog>` with a focus-trap polyfill.
- **Image size:** `add_image_size('team-card', 900, 1125, true)` plus an 1800×2250 retina size; the
  editor's crop tool is the only cropping UI exposed.
- **Editor guardrails:** no layout fields, no color fields, no per-member ordering by hand-placed
  blocks. Field descriptions carry the character guides (name 40, position 70, quote 180).
- **Anchors:** `#roster` with `scroll-margin-top: 106px` for the sticky header.
- **Accessibility:** one `h1` (hero); section headings `h2`; member names `h3`; count region
  `aria-live="polite"`; skip link to `#main`; fallback tiles carry a descriptive `aria-label`
  ("Portrait pending for {Name}, {Position}").
- **Mockup caveat:** hero and CTA media are CSS background images in the mockup to keep the
  streaming preview clean — ship real `<img>` with `width`/`height` and `loading`.

---

## 12. Assumptions & missing assets

None require a new component.

1. **No team data supplied** — nine placeholder records with invented positions and quotes, shown
   at deliberately varied lengths to prove the card's clamping. Every string is replaceable.
2. **No headshots** — all cards show the DS v1 teal fallback with a "Portrait pending" chip.
   Placeholders and real photographs must not mix once shooting is done.
3. **Intro heading** — "We have sat in the seat you are sitting in." is written by the designer; the
   source doc gives only the hero line and one paragraph for this whole page.
4. **CTA copy** — "Get to Know Us." is the approved homepage line; its supporting sentence is the
   designer's.
5. **Quote is required** in this spec even though the card also handles its absence — the field can
   be relaxed to optional without a redesign.
6. **Bio in a modal, not a single page** — keeps one URL for the team and avoids nine thin pages. If
   `/team/name` URLs are wanted for SEO, the card links out instead and the modal is dropped.
7. **Nine per page** before Load more, per DS v1. Confirm the roster size — under twelve people,
   pagination could be dropped entirely.
8. **No filtering** by service lane or region. Worth adding only past roughly twenty people; it
   would reuse the existing chip styling.
9. **Leadership vs. team** — one flat grid, no featured founders row. If leadership should read
   first, `menu_order` handles it without a second component.
10. **LinkedIn only** — no email or phone per member. The footer row can take a second 44px tile on
    request.
