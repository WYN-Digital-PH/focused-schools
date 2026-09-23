# Impact Story — Single Template Design Specification

**Template:** `single-fs_impact_story.php` · legacy variant `page-impact-story-legacy.php`
**URL pattern:** `/impact-stories/{slug}` (legacy Pages keep their existing permalinks)
**Design system:** Focused Schools DS v1
**Source mockup:** `Focused Schools Impact Stories.dc.html` (view: *Single story*)
**Companion spec:** `impact-stories.md`
**Status:** Approved for development
**Last updated:** September 17, 2026

> **Naming note:** the review mockup used the working CPT name `fs_story`. The canonical name for
> development is **`fs_impact_story`**.

---

## 1. Overview

One template serves both sources. New `fs_impact_story` records get the full structure; legacy Pages
get the same shell with sections omitted rather than filled with placeholders.

Body content is a flexible block set, so **no two stories need the same shape** — and none of it
requires layout decisions from the client.

Narrative order: **what happened → what changed → how.**

### Section hierarchy

| # | Section | Ground | Purpose |
|---|---|---|---|
| 00 | Sticky header | White card | Global nav, Impact Stories active |
| B1 | Breadcrumb + hero | White + teal slab | Same hero component as the landing. Meta chips in the slab carry district, state, year. |
| B2 | At a glance | Teal `#005C6D` | Statistics band, one to four results. Omitted when a story has no numbers. |
| B3 | Story body | White | Sticky 300px detail rail + 66ch prose. Heading, paragraph, pull quote, captioned figure. |
| B4 | Related stories | Paper `#f5f6f8` | Three cards, same component, matched on state then service lane then recency. |
| 05 | Footer | Teal | Homepage footer, verbatim |

### Component map

| Component | Source | Status |
|---|---|---|
| Sticky header / footer | Homepage, verbatim | Reused |
| Page hero (media + teal slab) | Homepage hero; same component as the landing | Reused |
| Breadcrumb | DS v1 uppercase meta line | Reused |
| Statistics band (At a glance) | Homepage / About stats, gold rule + lime unit | Reused |
| Detail rail (definition list) | Homepage 264px label rail, narrowed to 300px sticky | Reused |
| Pull quote | Homepage testimonial, coral slashes verbatim | Reused |
| Captioned figure | Homepage figure + caption type | Reused |
| Impact Story card | Same extended card as the landing grid | Extended |

No new components on this template — everything is drawn from the landing page and the homepage.

---

## 2. Breadcrumb + hero (B1)

### Breadcrumb

- 18px top / 22px bottom padding, above the media.
- `nav aria-label="Breadcrumb"`, 11px / 700 / 0.1em uppercase.
- Format: **Impact Stories** (link, teal, coral on hover) `/` **{District}** (current, `#51536a`);
  separator `/` in `#d3dde1`, `aria-hidden`.

### Hero

- Media 21:9, 8px radius, teal `#004855` fallback. No corner bracket on the single view (the landing
  owns that accent).
- Teal slab **720px wide** (wider than the landing's 660px to carry the meta chip row), square,
  `left: 24px; bottom: -56px`, 44px padding, `z-index: 2`.
- Meta chips: `rgba(255,255,255,.14)`, 8px radius, 8/14px padding, 11px / 700 / 0.1em uppercase
  white — **District, State, Year**, in that order, wrapping freely at 10px gap. 26px below to the
  H1.
- H1 **48px** / 1.0 / −0.025em / 700 white (four points smaller than the landing H1, because story
  titles run longer than page titles).
- Excerpt 17px / 1.6, `rgba(255,255,255,.92)`, max 560px.
- Section padding 14px top / 128px bottom; the following section carries `margin-top: 96px` to clear
  the slab overhang.

### Long-title behavior

- H1 holds to **three lines at 48px**. At four lines the slab grows downward and the section's bottom
  padding absorbs it — the media never moves and the H1 never shrinks.
- No clamp on the H1: a story title is never truncated on its own page. Truncation is a grid-card
  concern only.
- Titles over ~90 characters should be shortened editorially rather than typographically; the field
  description carries a 90-character soft guide.
- Meta chips wrap to a second row before the H1 reflows; the 26px gap is unchanged.

---

## 3. At a glance (B2)

- Teal band, 96px padding, `margin-top: 96px`, `overflow: hidden`, with the brand mark watermark at
  **6%** opacity (`right: -60px; top: 30px; height: 360px`, `aria-hidden`).
- Eyebrow "At a glance" — 12px / 700 / 0.14em uppercase white, 48px below.
- Grid `repeat(3, minmax(0,1fr))`, 48px gap.
- Each result: 3px gold `#F3C145` top rule, 22px padding-top, then
  - **value** 68px / 1 / −0.04em / 700 white,
  - **unit** 13px / 700 / 0.14em uppercase lime `#A7CC14`,
  - **label** 17px / 400 / 1.5 `rgba(255,255,255,.88)`, 14px above.
- Numerals **never animate.**
- Because values are abbreviated ("400+"), each result carries a full-sentence `aria-label` ("More
  than 400 community members engaged").

**Count behavior:** one to four results. Four wraps to a second row at the same gap. **Omitted
entirely when a story has no numbers** — and many legacy ones won't. When omitted, the prose column
starts directly under the hero.

---

## 4. Story body (B3)

### 4.1 Layout

- `300px minmax(0,1fr)`, **96px gap**, `align-items: start`. Section padding 112px / 120px.
- **Detail rail** is `position: sticky; top: 160px` (clears the sticky header plus breathing room).
- **Prose column** measure **66ch**.

### 4.2 Detail rail

- Eyebrow "Partnership details" — 11px / 700 / 0.14em uppercase teal, 22px below.
- `<dl>` with a 1px `#d3dde1` top rule; each row 16px vertical padding with a 1px `#d3dde1` bottom
  rule.
  - `dt` 11px / 700 / 0.1em uppercase `#51536a`, 6px below
  - `dd` 15px / 1.5 / **700** teal
- Rows: **District, State, Year, Service lane, Partnership length.**
- Primary coral pill "Start a Partnership →" 32px below the list.

**Degradation:** the rail renders **only the rows it has**. With fewer than two values it drops
entirely and the prose column spans full width at **72ch**.

### 4.3 Body blocks

Four block types only. The prose scope owns all typography — no custom colors, no spacers, no
columns.

| Block | Treatment | Gap above / below |
|---|---|---|
| **Heading** | `h2` 34px / 1.12 / −0.025em / 700 teal, max 26ch | 26 / 26px |
| **Paragraph** | 19px / 1.75 `#51536a`, max 66ch | — / 30px |
| **Pull quote** | `78px + 1fr` grid, 34px gap. Two coral `#DD6237` 10×38px bars skewed −14°, 7px apart, 14px top padding. Quote 36px / 1.26 / −0.02em italic 400 teal, max 34ch. Citation 11px / 700 / 0.1em uppercase teal preceded by a 26×2px coral rule | 56 / 56px |
| **Figure** | Full-column 16:9, 8px radius; caption 13px / 1.6 italic `#51536a`, max 56ch, 16px below | — / 56px |

**Captions are required** on in-body figures — an uncaptioned photo in a case study reads as
decoration.

### 4.4 Legacy stories on the single view

- Legacy Pages keep their **own URL and their existing body content** — no content migration required
  to launch.
- They receive the new header, footer, breadcrumb, and Related grid via a page template, so
  navigation is consistent site-wide from day one.
- **Missing hero image** → the teal slab sits on a plain teal band at the same height. No stretched
  low-res upscales.
- **No results** → At a glance omitted; the prose column starts directly under the hero.
- **No detail values** → the rail renders only the rows it has; with fewer than two it drops and the
  prose column spans full width at 72ch.
- **Legacy body markup is styled through a single prose scope** (19px / 1.75, 66ch) rather than
  rewritten. Headings, lists, and images inside legacy content inherit the block treatments above.

---

## 5. Related stories (B4)

- Paper ground, 112px / 120px padding.
- Header: eyebrow "Keep reading" + H2 58px / 1.02 / 700 max 18ch, with a secondary pill "All Impact
  Stories →" at the right, 56px below to the grid.
- **Three cards**, the same extended story card as the landing grid — identical metadata hierarchy,
  clamps, badges, and source pills (see `impact-stories.md` §4.3–4.4).
- Matching order: **State → Service lane → recency.** The current story is always excluded.
- Fewer than three matches → renders what it has, left-aligned; cards never stretch. Zero matches →
  the section is omitted.
- In Related, "Read story" is the card's single link; no Load more.

---

## 6. Responsive behavior

Same breakpoints as the landing page and the Team page: **1240 / 1023 / 900 / 767**.

| Breakpoint | Body layout | Related grid | Notes |
|---|---|---|---|
| **Desktop ≥1280** | 300px sticky rail + 1fr prose, 96px gap | **3-up** | At a glance 3-up · H1 48px · quote 36px |
| **Laptop 1024–1279** | **Rail drops below the prose** (order swap), one column, 56px gap | **3-up** | Hero slab static, `margin-top: −45px` · display 60px / H1 46px |
| **Tablet 768–1023** | One column, rail below | **2-up**, 24px gap | At a glance **2-up** · hamburger nav from 1023 |
| **Mobile <768** | One column, rail below | **1-up**, 20px gap | At a glance 1-up, 32px gap · H1 34px · quote 26px |

### ≤1240px

- `[data-single-body]` collapses to one column at 56px gap; the aside takes `order: 2` and the prose
  `order: 1`, so the reading order is prose first, details after.
- Hero slab becomes static and full-width with `margin-top: −45px`, 40px padding.
- The rail loses its sticky positioning (nothing to stick to in a single column).

### ≤767px

- Gutters 20px; section padding 64px; header CTA hides.
- Hero media goes **4:5**; the teal slab drops its −56px offset and sits flush beneath, full-bleed to
  the 20px gutter. Meta chips wrap freely.
- Type step: H1 34px / H2 36px / prose **17px** / pull quote **26px** — DS v1 mobile step, no new
  sizes.
- At a glance stacks 1-up at 32px gap; the gold rule stays 3px and numerals stay 68px.
- Pull quote keeps its coral slashes at full size; the `78px` bar column is unchanged so the quote
  stays visually anchored.
- Figures stay 16:9 full-column; captions unchanged at 13px.
- Detail rail renders as a flat definition list below the prose, full width, rows unchanged.
- Related cards 1-up; the "All Impact Stories" pill goes full-width below the heading.
- Breadcrumb wraps rather than truncating.

---

## 7. Spacing specification

| Measure | Value |
|---|---|
| Container / gutter | 1400 / 24px |
| Section padding, light ground | 112–120px |
| Section padding, teal band | 96px |
| Breadcrumb padding | 18px top / 22px bottom |
| Hero slab offset / width | bottom −56px, left 24px / 720px |
| Meta chip gap | 10px, 26px below to H1 |
| Teal band clearance | `margin-top: 96px` |
| At a glance: label → grid | 48px |
| At a glance grid gap | 48px (32px mobile) |
| Single: rail / prose | 300px + 1fr, 96px gap |
| Sticky rail offset | `top: 160px` |
| Rail row padding | 16px |
| Rail → CTA | 32px |
| Prose measure / paragraph gap | 66ch / 30px |
| Prose measure, no rail | 72ch |
| Block gap: heading / quote / figure | 26 / 56 / 56px |
| Quote: bars / text columns | 78px + 1fr, 34px gap |
| Figure → caption | 16px |
| Related header → grid | 56px |
| Related grid gap | 32 / 24 / 20px |
| Mobile section padding | 64px |

---

## 8. Visual styles

**Type:** DM Sans only (400 / 500 / 700 + italics). Italic is used exactly twice: the pull quote and
figure captions.

**Color:** teal `#005C6D` (ink, slab, stats band) · teal deep `#004855` (media fallback) · coral
`#DD6237` (quote slashes, citation rule, primary pill, active nav) · lime `#A7CC14` (stat units) ·
gold `#F3C145` (3px stat rules) · cerulean `#0A96CB` (focus ring) · paper `#f5f6f8` (Related ground)
· hairlines `#d3dde1` / `#eef1f2` · body gray `#51536a`.

**Shape & elevation:** 8px radius on all photography and figures; 16px on cards; 999px on pills and
badges. Hero slab square. Shadow-1 `0 1px 2px rgba(0,92,109,.08)`, shadow-2
`0 12px 28px rgba(0,92,109,.12)`. Watermark mark at 6% opacity, `aria-hidden`.

---

## 9. Image guidance

- **Story hero** 21:9, min 2400×1030. Focal interest right of center so the teal slab covers no face.
  Eager, `fetchpriority="high"`.
- **In-body figures** 16:9, min 1600×900, 8px radius, **always captioned.**
- **Related card images** 16:9, min 1200×675 — the same `story-card` size as the landing grid, so no
  additional uploads.
- **Subject matter:** the district's own people doing the work — classrooms, data walls, leadership
  rooms. No stock, no duotone, no grayscale, no filters, no scrims outside the hero.
- **Missing image** → teal tile with the mark at 12%. Never a stretched upscale.
- WebP with JPEG fallback, width/height on every image, lazy below the fold, alt text describing the
  moment. **Get district media-release confirmation before publishing student-visible photography.**

---

## 10. Interaction states

| Element | Default | Hover | Focus / behavior |
|---|---|---|---|
| Breadcrumb link | Teal | → coral, 160ms | Ring |
| Detail rail CTA | Coral pill | `brightness(.92)`, 160ms, no rise | Ring |
| "All Impact Stories" | Secondary pill | Border → teal, ground → paper | Ring |
| Related card | Shadow-1 | Shadow-2 + 2px rise, 240ms | Container, not a link |
| Read story | 11px / 700 uppercase teal, 12px gap | Label → coral, gap 12 → 18px, 240ms | Card's single link; accessible name "Read story: {Title}" |
| Source pill / year badge | Static | **No hover** — information, not controls | n/a |
| Stat figures | Static | — | Never animated; full value in `aria-label` |
| Active nav item | Impact Stories: 700, 2px coral underline at 8px offset | — | `aria-current="page"` |

**Focus-visible, everything:** 3px `#0A96CB` ring at 2px offset, never removed.

**Sticky behavior:** the detail rail sticks at `top: 160px` on desktop only. It never overlaps the
footer — the grid's `align-items: start` plus the section's bottom padding keep it inside its
column.

**Entrances:** fade + 28px rise, 700ms `cubic-bezier(.2,.7,.2,1)`, once, max three staggered.
Intersection Observer at `threshold: 0.08`, `rootMargin: 0 0 -6% 0`, with a **900ms safety timeout**
restoring opacity so nothing is stranded transparent. Disabled under `prefers-reduced-motion`, along
with smooth scroll and all transitions.

---

## 11. Developer implementation notes (WordPress)

- **Template** `single-fs_impact_story.php`; legacy variant `page-impact-story-legacy.php` shares
  every template part and differs only in where content comes from.
- **Results:** repeater of `value` / `unit` / `label`, **hard-capped at four rows** in the field
  config. Wrap the whole section in a count guard.
- **Detail rail:** rendered from District (meta), State (`story_state` term), Year (meta), Service
  lane (`story_service` term), Partnership length (meta). Each row is individually guarded; a
  `count < 2` check drops the rail and switches the prose to 72ch.
- **Body:** restrict the block palette to **heading, paragraph, quote, image**. No columns, no
  spacers, no custom colors — the prose scope owns all typography. Register the four block styles as
  theme block styles so the editor preview matches the front end.
- **Prose scope:** one wrapper class handling `h2`, `p`, `blockquote`, `figure`, `ul`, `ol`, and
  `a` at the sizes in §4.3. Legacy markup passes through it unchanged.
- **Related query:** `story_state` match → `story_service` match → recency; `post__not_in` the
  current story; `posts_per_page = 3`. Reuses
  `template-parts/cards/story-card.php` with the same `fs_normalize_story($post)` helper as the
  landing, so legacy records can appear in Related without branching.
- **Canonical / permalinks:** legacy Pages keep their URLs with canonical tags. **Do not redirect
  working URLs.** New stories at `/impact-stories/{slug}`.
- **Anchors:** any in-page anchor uses `scroll-margin-top: 106px` for the sticky header.
- **A11y:** exactly one `h1` (the story title); body headings are `h2`; the stats band's `h2` labels
  sit under the "At a glance" eyebrow; Related card titles are `h3`; `<dl>` is a real definition
  list; stat values expose full text via `aria-label`; skip link to `#main`.
- **SEO:** `Article` schema with `headline`, `datePublished`, `about` (district), `locationCreated`
  (state). Excerpt drives the meta description.
- **Mockup caveats:** media are CSS background images and the view switcher bar is review chrome —
  **neither ships.** Ship real `<img>` with width/height and `loading`.

---

## 12. Assumptions & missing assets

Shared with the landing spec; the items specific to this template:

1. **The whole demo story is written by the designer** — body copy, headings, the pull quote and its
   citation, and all three result figures. Champaign Unit 4 is a real partner; nothing attributed to
   them here is real or publishable without district sign-off.
2. **Three results shown**, four is the cap. Confirm whether stories will reliably have numbers — if
   most won't, At a glance becomes the exception rather than the norm and the hero may need to carry
   more weight.
3. **Partnership length** is specified as a free-text meta field ("Ongoing, 2 years"). If it should
   be structured (start year / end year), say so before build.
4. **Service lane** is shared with the Services CPT taxonomy. That coupling is intentional — it
   powers Related matching — but it means lane names must stay in sync across the two sections.
5. **No video block.** If stories should carry video testimonials, the homepage video modal component
   drops into the body block set as a fifth block type.
6. **No author or contributor byline.** Stories read as the district's, not an individual
   consultant's. Add a byline row to the detail rail if that changes.
7. **No PDF / print version.** If districts want a shareable one-pager per story, that is a separate
   print stylesheet and a separate conversation.
8. **Related matching is heuristic** (state → lane → recency). If editors want to curate Related
   manually, that is a relationship field and a template change.
