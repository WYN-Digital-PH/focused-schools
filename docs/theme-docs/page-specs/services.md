# Services — Page Specification

**Status:** Approved for development
**Design source:** `Focused Schools Services.dc.html`
**Design system:** Focused Schools Design System v1
**Parent pattern:** Approved Homepage (`focused-schools-rebrand.vercel.app`)
**Last updated:** September 17, 2026

---

## 1. Objective & Constraints

The Services page is a direct extension of the approved Homepage. It introduces **no new visual direction**.

Three non-negotiable constraints govern the build:

1. **One reusable Service component.** The page renders a single block definition once per Services CPT record. There are not three service layouts — there is one layout rendered three times. Adding a fourth service requires no design or template work.
2. **Enforced accent coding.** Each service lane owns one accent from the DS v1 palette. The accent is a stored token key on the CPT record, never a free color choice.
3. **No manual card maintenance.** Nothing on this page requires an editor to duplicate, reorder, or position a block by hand. Grounds, media sides, numerals, and anchors all derive from query position.

---

## 2. Accent Coding (Enforced)

| Lane | Service | Accent | Hex | Token |
|---|---|---|---|---|
| 01 | Strategy and Vision | Cerulean | `#0A96CB` | `--fs-cerulean` |
| 02 | Leadership and Systems | Coral | `#DD6237` | `--fs-coral` |
| 03 | Capacity and Coaching | Lime | `#A7CC14` | `--fs-lime` |

### Accent application rules

The accent appears in exactly **two** places inside a lane, plus one derived place:

- **Accent bar** — 26 × 3px block at the head of the copy column, left of the lane numeral.
- **Offering bullets** — 6 × 6px squares in the Signature Offerings list.
- **Caption kicker** — the floating figure caption's eyebrow text.

The accent **never**:

- sets body copy, headings, taglines, or link color;
- fills a button (coral `#DD6237` is the only primary-button fill site-wide, independent of lane);
- tints a section ground;
- sets a focus ring (always cerulean `#0A96CB`, site-wide, regardless of lane).

### Lime exception

Lime `#A7CC14` has insufficient contrast for text at any size. In the Capacity lane, the caption kicker falls back to cerulean `#0A96CB` while the bar and bullets remain lime. This is codified in the component, not left to the editor.

### Fallback

An empty `accent` field falls back to teal `#005C6D` — never to a random or cycling color.

---

## 3. Section Hierarchy

| # | Section | Ground | Purpose |
|---|---|---|---|
| 01 | Page hero | White + teal slab | Names the page in the eyebrow; the H1 carries the promise, not the word "Services". One primary CTA. |
| 02 | Lane index | White | The whole offer in ten seconds. Three rows: numeral, title, one-line promise. Anchors down the page. |
| 03 | Lane 01 · Strategy and Vision | White | Reusable Service block. Media right. |
| 04 | Lane 02 · Leadership and Systems | Paper `#f5f6f8` | Same block. Media left. Six offerings → two columns. |
| 05 | Lane 03 · Capacity and Coaching | White | Same block. Media right. |
| 06 | A Cycle of Excellence | Teal `#005C6D` | The method underneath all three lanes. Answers "how do you actually work?" |
| 07 | Testimonial | Paper `#f5f6f8` | One approved quote + Google Reviews link. No carousel. |
| 08 | Closing CTA | White | For the reader who cannot self-select a lane. |

**Ground alternation is derived, not authored.** Lanes alternate white / paper by `$index % 2`. Inserting a service in the middle reflows the rhythm automatically.

---

## 4. Component Mapping

| Block | Source | Status |
|---|---|---|
| Sticky header | Homepage header, Services active + coral underline | Reused |
| Page hero (media + teal slab) | Homepage hero, video console removed, 21:9 media | Reused |
| Lane index rows | Homepage "How we help" service rows | Reused |
| **Service Lane block** | Content/Image split + numbered accent row + offerings list | **New** |
| Floating caption chip | Homepage figure caption, max one per section | Reused |
| Teal band + mark watermark | Homepage Cycle section, motion removed | Reused |
| Testimonial block | Homepage quote slide, carousel chrome removed | Reused |
| Primary / secondary pills | DS v1 buttons, unchanged | Reused |
| Closing CTA split | Homepage contact split, form replaced by buttons | Reused |
| Footer | Homepage footer, verbatim | Reused |

**The one genuinely new component** is the Service Lane block: the homepage Content/Image split with a numbered accent row and a Signature Offerings list added inside the copy column. Geometry, type scale, radii, shadow, and button styles are unchanged, so it requires no new CSS beyond the offerings list and the accent-bar utility.

---

## 5. The Reusable Service Component

One block, rendered once per `fs_service` record in `menu_order`. Three services today, six tomorrow, with no layout work.

### Field → rendering map

| Field | Rendering |
|---|---|
| `post_title` | Lane H2 — 52px / 700 / −0.03em, max 18ch |
| `tagline` | 21px / 700 teal, max 30ch — and the one-line promise in the lane index |
| `short_description` | 17px / 1.75 gray, max 62ch |
| `signature_offerings` | Repeater → two-column list at 4+ items, 6px accent bullet |
| `featured_image` | 4:5 figure, 8px radius, shadow, alternating side |
| `cta_label` / `cta_link` | Coral primary pill at the end of the copy column |
| `accent` | Token key → accent bar, bullets, caption kicker. Never type. |
| `menu_order` | Lane numeral, ground parity, media side, anchor order |

### Derived values (never authored)

- **Lane numeral** — generated from loop position, so reordering in WordPress renumbers the page.
- **Ground** — white on even index, paper on odd.
- **Media side** — right on even index, left on odd.
- **Anchor** — `#{post_name}`, with `scroll-margin-top: 106px` to clear the sticky header.
- **Caption side** — mirrors the media side (`left: -16px` when media is left, `right: -16px` when right).

---

## 6. Long-Content & Empty-Field Behavior

### Long content

| Element | Rule |
|---|---|
| **Title** | Wraps at 18ch, up to three lines, before reaching the media column. Reduces to 44px at the 1240px breakpoint. Never auto-fits. |
| **Tagline** | Hard 30ch measure. Editors get a 110-character soft guide in the admin field description. Wraps, never truncates. |
| **Description** | 62ch measure. Past ~160 words the block simply grows; media stays top-aligned via `align-items: start`, so there is no vertical centering drift. |
| **Offerings** | Two columns from four items up, one column below four and on mobile. Eight is the practical ceiling; past that the section grows and stays legible. |

### Empty fields

| Missing | Behavior |
|---|---|
| **Offerings** | Label, hairline, and 30px padding all disappear. CTA moves up. No empty rule left behind. |
| **Featured image** | Figure collapses; copy column spans full width at a 70ch measure. *(Alternative fallback pending approval: teal tile with the mark at 7%.)* |
| **CTA** | Button omitted. The page-level closing CTA still catches the reader. |
| **Tagline** | Omitted; description moves up. The lane index row shows title only. |
| **Accent** | Falls back to teal `#005C6D`. |

### Scaling to N services

- The lane index runs the same query as the lanes, so a new service appears in both places at once.
- At **6+ services**, switch the lane index to a sticky rail rather than lengthening the list. *(Documented, not built.)*
- No per-service layout toggle is ever exposed. Variation comes from content length and accent only.

---

## 7. Responsive Behavior

### Breakpoints

| Breakpoint | Lane layout | Notes |
|---|---|---|
| **≥1241px** | Copy 1fr + media 500px, 96px gap | Full desktop. Hero slab offset −56px. |
| **1024–1240px** | Single column, 48px gap; media below copy | Hero slab goes static with a −45px pull. Display type 60px, H1 46px, lane title 44px. |
| **768–1023px** | Single column | Hamburger nav begins. Lane index collapses to 56px + 1fr with the tagline on a second row; index arrow hidden. |
| **<768px** | Single column, full stack | See mobile rules below. |

### Mobile (<768px)

- Hero media goes **4:5**; the teal slab drops its negative offset and sits flush beneath, full-bleed to the 20px gutter.
- Type: **H1 34px / H2 36px / lane title 32px / tagline 19px / body 16px** — the DS v1 mobile step. No new sizes.
- Lane stack order is always **copy → offerings → CTA → image**. The image is evidence, not the headline, so it never pushes the promise below the fold.
- Lane index keeps numeral and title, drops the arrow and moves the tagline to a second row. Rows stay 36px minimum with a 44px tap target.
- Offerings collapse to one column, 12px row gaps. Bullets stay 6px accent squares.
- Every button becomes full-width, 46px high, centered label. One primary per screenful.
- Cycle band: mark centered above the copy, 280px → 220px; section padding 80px.
- Testimonial: quote 24px; coral slash marks stay at the 78px column width above the quote.
- Sticky header stays 74px with logo + menu only. The "Let's Talk" pill is dropped — it repeats twice in-page and once in the footer.
- Gutters 20px; section padding 64px.

---

## 8. Spacing Specification

| Measure | Value |
|---|---|
| Container / gutter | 1400px / 24px |
| Section padding, light ground | 120px |
| Section padding, teal band | 120px |
| Hero slab offset | bottom −56px, left 24px |
| Lane grid: copy / media | 1fr / 500px, 96px gap |
| Accent row → title | 30px |
| Title → tagline | 22px |
| Tagline → body | 26px |
| Body → offerings hairline | 40px |
| Offerings label → list | 22px |
| Offerings row / column gap | 16px / 40px |
| Offerings → CTA | 40px |
| Lane index row padding | 36px |
| Section header → body | 56px |
| Mobile section padding | 64px |
| Mobile gutter | 20px |
| Anchor scroll-margin | 106px |

---

## 9. Image Guidance

| Slot | Spec |
|---|---|
| **Hero** | 21:9, min 2400 × 1030. Focal interest right of center so the teal slab never covers a face. Eager load + `fetchpriority="high"`. |
| **Lane featured image** | 4:5, min 1000 × 1250. 8px radius, shadow `0 20px 44px rgba(0,92,109,.14)`. |
| **Mobile hero** | Same file, art-directed 4:5 crop via `<picture>` — not CSS cropping. |

### Subject matter

One person or one team **in the act of working**. No posed grip-and-grins, no stock photography. Documentary color only: no duotone, no grayscale, no filters, no gradient scrims outside the hero.

### Technical

- WebP with JPEG fallback.
- `width` / `height` attributes on every `<img>` to hold layout.
- Lazy load everything except the hero.
- Real alt text describing the work, not the brand. Decorative marks get `alt=""`.

### Required assets

Each lane needs **its own** photograph so the page does not repeat homepage imagery. Three lane images + one hero = **four photographs**.

---

## 10. CTA & Interaction States

### Buttons

| Component | Default | Hover | Focus |
|---|---|---|---|
| **Primary pill** | Coral `#DD6237`, white label, 48px, 999px radius | `filter: brightness(.92)`, 160ms. No rise, no shadow. | 3px `#0A96CB` ring at 2px offset |
| **Secondary pill** | White, 1px `#d3dde1`, teal label, 46px | Border → `#005C6D`, ground → `#f5f6f8`, 160ms | Same ring |
| **Teal-band button** | White fill, teal label | Ground → `#f5f6f8`, 160ms | Same ring |

**The lane accent never fills a button.** Every primary CTA on the page is coral, regardless of lane.

### Other interactive states

- **Lane index row** — the whole row is the link. Ground → `#eef1f3` at 240ms; the arrow stays static; hairlines are retained.
- **Text / arrow link** — label → coral, gap 12px → 18px at 240ms.
- **Focus-visible, everything** — 3px `#0A96CB` at 2px offset. Never removed, never restyled per component or per lane.
- **Active nav item** — Services carries a 2px coral underline at 8px offset, plus `aria-current="page"`.
- **Entrances** — fade + 28px rise, 700ms, once per element. Disabled under `prefers-reduced-motion`. No scroll-scrubbed motion on this page.

Every interactive target is **44px minimum** in its smallest dimension.

---

## 11. Developer Implementation Notes (WordPress)

### Custom post type

```
fs_service
  public: true
  has_archive: false
  supports: title, editor, thumbnail, page-attributes
```

`page-attributes` provides `menu_order` for drag-to-reorder. A single-service template is optional — the Services page is the canonical view. If singles ship later, they reuse this same block at full width.

### Fields (ACF)

| Field | Type | Required | Notes |
|---|---|---|---|
| `tagline` | Text | Yes | 110-character soft guide in the field description |
| `short_description` | Textarea | Yes | ~160-word guide |
| `signature_offerings` | Repeater (one text sub-field) | No | Soft cap 8 rows |
| `featured_image` | Native featured image | No | 4:5, `add_image_size('service-lane', 1000, 1250, true)` |
| `cta_label` | Text | No | Defaults to "Start This Conversation" |
| `cta_link` | Link | No | |
| `accent` | Select | Yes | `cerulean` / `coral` / `lime` / `teal` |

**`accent` stores a token key, not a hex value.** The template maps `cerulean` → `var(--fs-cerulean)`. Editors can never enter a color, and the palette cannot drift.

### Template structure

One template part, looped:

```
template-parts/blocks/service-lane.php
```

```php
$q = new WP_Query([
  'post_type'      => 'fs_service',
  'posts_per_page' => -1,
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
]);
```

- Ground and media side derive from `$index % 2`.
- The lane index runs the **same query** and prints anchors from `post_name`.
- Optional fields are wrapped in `if` guards per §6.

### Editor guardrails

- No per-service layout toggle.
- No color picker anywhere on the CPT.
- No rich text in `tagline`.
- Section wrapper exposes ground + padding only, per the DS v1 handoff.

### Accessibility

- One `h1` (hero). Lane titles are `h2`.
- The offerings label is a `<p>`, not a heading — it is not a document landmark.
- The lane index is a `<nav>` with an accessible name ("Jump to a service").
- Images lazy except the hero.
- Contrast: all body copy meets 4.5:1; lime is never used for type.

### CSS budget

**Zero new CSS** beyond the offerings list grid and the accent-bar utility. Everything else is existing homepage classes.

---

## 12. Assumptions & Missing Assets

| # | Item | Notes |
|---|---|---|
| 1 | **Lane photography** | Placeholders from the homepage retreat set. Three distinct photographs needed, 4:5, min 1000 × 1250. |
| 2 | **Hero image** | Placeholder, and it repeats a homepage frame. Needs a wide 21:9 frame with a quiet right side. |
| 3 | **CTA label** | "Start This Conversation" is a placeholder for `cta_label`. Client copy wins. |
| 4 | **Closing CTA copy** | "Not sure which lane you need?" and its paragraph are written by design; the supplied doc has no Services closing copy. |
| 5 | **Technical Assistance** | The copy doc places it as a capability inside Leadership and Systems; the homepage lists it as a fourth service row. The homepage row should either relabel or link into this lane. **Needs a decision.** |
| 6 | **Lane accents** | Applied as bar, bullets, and caption kicker only. Lime never sets type. |
| 7 | **Testimonial** | One approved quote shown statically with a Google Reviews link, per the doc's "keep testimonials as is." If Services should carry the full carousel, it is the same homepage component. |
| 8 | **Google Reviews URL** | Placeholder search link. Needs the real review destination. |
| 9 | **Cycle animation** | The rotating mark stays a homepage signature; here the mark is static. **Confirm.** |
| 10 | **Impact Stories** | Deliberately not included — the lanes plus one quote carry the proof. The homepage stories carousel can drop in above the closing CTA on request. |
| 11 | **Mockup caveat** | Lane and hero media are CSS background images in the design file to keep the streaming preview clean. Ship real `<img>` with `width`/`height` and `loading`. |
