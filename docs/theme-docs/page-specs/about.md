# About — Page Design Specification

**Page:** About / Our Mission & Vision
**Template:** `page-about.php`
**Design system:** Focused Schools DS v1
**Source mockup:** `Focused Schools About.dc.html`
**Status:** Approved for development
**Last updated:** September 17, 2026

---

## 1. Overview

The About page is composed almost entirely from approved homepage components. Eleven blocks in
total: ten reused, one genuinely new (the Partner District Directory). No new colors, type sizes,
radii, or shadows are introduced.

Narrative order: **who we are → what guides us → what it produced → who we did it with → who we
are personally → what we want next.**

Three regions are query-driven, not typed. No editor ever duplicates a card to add content.

---

## 2. Section hierarchy

| # | Section | Ground | Purpose |
|---|---|---|---|
| 00 | Sticky header | White card on white | Global nav, About active |
| 01 | Hero — the cape line | White + teal slab | The most human sentence in the copy is the H1. Eyebrow names the page; CTA goes to the team, because that is what About readers want. |
| 02 | Who we are — since 2000 | White | Homepage belief rail, minus the scroll-hold. The no-exceptions statement is the display heading; the two-decade story is the body. |
| 03 | What guides us — Mission, Values, Approach | Paper `#f5f6f8` | Homepage commitment list: three numbered rows, each with its own CTA, beside one 4:5 figure. |
| 04 | Impact — what it produced | Teal `#005C6D` | DS v1 statistics treatment, gold rules and lime units. Links out to Impact Stories rather than repeating them. |
| 05 | Partner districts — who we did it with | White | State-grouped chips. Ends with the credential row and "Let's put you on the map." |
| 06 | Meet our team — educators first | Paper `#f5f6f8` | CPT card grid, 3-up. The teaser for the Team page. |
| 07 | Mission close | White | One primary, one secondary. No form — Contact owns the form. |
| 08 | Footer | Teal `#005C6D` | Homepage footer, verbatim |

Only one `<h1>` on the page (the hero cape line). Section headings are `h2`; pillar titles, state
names, stat labels, and team names are `h3`.

---

## 3. Component map

| Component | Source | Status |
|---|---|---|
| Sticky header | Homepage header, About active + coral underline | Reused |
| Page hero (media + teal slab) | Homepage hero, video console removed, 21:9 media | Reused |
| Belief rail | Homepage "What we believe", scroll-hold removed | Reused |
| Numbered pillar list + figure | Homepage commitments block, verbatim | Reused |
| Floating caption chip | Homepage figure caption, one per section | Reused |
| Statistics on teal | DS v1 statistics, gold rule + lime unit | Reused |
| **Partner district directory** | **DS v1 partner chips regrouped by state on the 264px rail** | **New** |
| Team card grid | DS v1 team card, 4:5, CPT-driven | Reused |
| Credential / badge row | Homepage contact badge treatment | Reused |
| Closing CTA split | Homepage contact split, form replaced by buttons | Reused |
| Footer | Homepage footer, verbatim | Reused |

### The one new component

**Partner District Directory.** The DS v1 partner strip (text chips, 8px radius, 12/18px padding)
reorganized into state groups on the existing 264px label rail — same chips, same rail, no new
geometry. Needed because 18 districts across 5 states cannot read as one flat strip.

---

## 4. Visual styles

### 4.1 Color

| Token | Hex | Use on this page |
|---|---|---|
| Teal (primary) | `#005C6D` | Body ink, headings, hero slab, stats band, footer |
| Teal deep | `#004855` | Hero media fallback ground |
| Coral | `#DD6237` | Primary buttons, active nav underline, spec eyebrow, card arrows |
| Coral hover | `#C4522C` | Primary button hover reference value |
| Lime | `#A7CC14` | Hero eyebrow on teal, stat units, footer link hover |
| Cerulean | `#0A96CB` | Caption chip eyebrow, chip bullets, focus ring |
| Gold | `#F3C145` | 3px top rule on each stat |
| Paper | `#f5f6f8` | Alternating section ground, chip fill, secondary hover fill |
| Hairline | `#d3dde1` | Section dividers, pillar rows, state rows, secondary borders |
| Hairline light | `#e3e9eb` / `#eef1f2` | Card borders, spec table rules |
| Body gray | `#51536a` | All long-form body copy |
| White | `#ffffff` | Page ground, cards, type on teal |

No color is used as the sole carrier of meaning anywhere on the page.

### 4.2 Typography

Single family: **DM Sans** (400, 500, 700 + italics), preconnected and `display=swap`.

| Role | Desktop | Notes |
|---|---|---|
| Display H2 | 84px / 1.0 / −0.03em | Sections 02, 03 |
| Display H2 (secondary) | 68px / 1.03 / −0.03em | Sections 05, 06, 07 |
| Display H2 (on teal) | 58px / 1.02 / −0.03em | Section 04 |
| H1 | 52px / 0.98 / −0.025em / 700 | Hero slab only |
| H3 pillar title | 36px / 1.12 / −0.025em / 700 | Max 22ch |
| Stat numeral | 76px / 1 / −0.04em / 700 | Never animated |
| H3 state name | 24px / 1.2 / −0.02em / 700 | |
| H3 team name | 22px / 1.2 / −0.02em / 700 | Clamps at 2 lines |
| Lead body | 19px / 1.65 | Rail intro, section intros |
| Body | 17px / 1.75 | Standard paragraph |
| Body small | 15px / 1.7 | Pillar bodies, card roles, chips |
| Eyebrow | 12px / 700 / 0.14em / uppercase | Section labels |
| Button label | 11–13px / 700 / 0.09–0.1em / uppercase | |

Mixed-weight headings: `font-weight: 400` on the element with `<strong>` at 700 for the emphasized
clause. Do not reproduce this with two elements.

### 4.3 Shape & elevation

- Radius: `8px` photography and chips, `16px` cards and header, `999px` buttons.
- The hero teal slab is **square** — no radius. This is intentional.
- Shadow 1 (rest card): `0 1px 2px rgba(0,92,109,0.08)`
- Shadow 2 (hover card): `0 12px 28px rgba(0,92,109,0.12)`
- Photo shadow: `0 20px 44px rgba(0,92,109,0.14)`
- Caption chip: `0 10px 28px rgba(0,92,109,0.14)`
- Header: `0 2px 12px rgba(0,92,109,0.08)`
- Watermark marks (stats band, footer): brand mark at **6% opacity**, `aria-hidden`.

---

## 5. Spacing rules

| Measure | Value |
|---|---|
| Container / gutter | 1400px / 24px |
| Section padding, light ground | 120px |
| Section padding, teal band | 120px |
| Hero slab offset | bottom −56px, left 24px |
| Belief rail: label / content | 264px + 1fr |
| Pillars: figure / list | 430px / 1fr, 96px gap |
| Pillar row padding | 40px top / 44px bottom |
| Section header → body | 96px (72px on stats) |
| Stats grid gap | 48px |
| State row padding | 36px |
| State label / chips | 264px + 1fr, 60px gap |
| Chip gap | 12px |
| Team grid gap | 32px |
| Team card padding | 28px |
| Mobile section padding | 64px |
| Mobile gutter | 20px |
| Anchor scroll-margin | 106px |

---

## 6. Responsive behavior

Breakpoints match the approved homepage exactly: **1240 / 1024 / 900 / 720**.

### 6.1 Desktop (≥1241px)

Full layout as specified above. Hero slab is absolutely positioned, 680px wide, overlapping the
21:9 media by 56px. Two-column section headers (heading left, intro right) with 80px gap.

### 6.2 Large tablet / small laptop (≤1240px)

- Belief rail collapses to one column, 40px gap.
- Section headers collapse to one column, 44px gap.
- Hero slab becomes static, full-width, `margin-top: −45px`, 40px padding.
- Pillars body becomes one column, 56px gap; figure caps at 430px.
- Display headings step to 60px; H1 steps to 46px.

### 6.3 Tablet (≤1024px)

- Primary nav and the Menu text label hide; the mark + burger button remains.
- Pillar rows tighten to `56px + 1fr`, 14px row gap / 24px column gap.
- Team grid → 2-up.
- Closing CTA split → one column, 48px gap.
- Partner state rows → one column, 48px gap.
- Footer → 2-up, 48px gap.

### 6.4 Small tablet (≤900px)

Spec appendix grids collapse to one column. No effect on the live page.

### 6.5 Mobile (≤720px)

- Gutters 20px; section padding 64px.
- Type step: H1 34px / display 36px / H3 26px / body 16px. No new sizes.
- Hero media goes **4:5**; the teal slab loses its −56px offset and sits flush beneath, full-bleed
  to the 20px gutter.
- Header "Let's Talk" CTA hides (the burger carries navigation).
- "Who we are" rail: label row stacks above the display heading with a 40px gap; the mark stays
  28px.
- Guiding pillars: image moves **below** the list (the list is the content); rows keep their
  hairlines, the number moves inline above the title.
- Stats stack to one column with 40px gaps; gold top rule stays 3px, numerals stay 76px.
- Partner directory: state name and numeral on one row, chips wrap below at 14px; 44px minimum tap
  height preserved even though chips are not links.
- Team grid 1-up; card padding 28px → 22px; hover becomes a no-op on touch, focus ring still
  applies.
- Badge row stacks: badge, then Approved Provider line, then "Let's put you on the map." with a
  full-width primary button.
- Mission close: copy first, image last, buttons stacked full-width with a 16px gap.

---

## 7. Dynamic content & long-content behavior

### 7.1 Team grid — `fs_team` CPT

- Fields: `post_title`, `role`, `bio`, `portrait`, `menu_order`.
- 3-up ≥1025, 2-up 768–1024, 1-up <768. Any count; a trailing row of one or two cards is
  left-aligned, never stretched.
- Cards paginate at nine; a **Load more** secondary button appends the next nine.
- Bio opens a modal (DS v1 team card); no single-member URL needed.
- Missing portrait → teal tile + mark at 12% + "Portrait pending" chip. Real and placeholder
  portraits must not mix in one grid once photography lands.
- Role wraps to two lines at 15px; titles clamp at 22px / 2 lines.

### 7.2 Partner directory — taxonomy grouped

- `fs_partner` CPT with a `state` taxonomy; the template loops states alphabetically, then
  districts within each.
- State rows are generated — adding a sixth state adds a row, no template change.
- Chips wrap freely; a state with one district and a state with seven both look correct.
- Numerals are loop indices, so ordering stays consistent after edits.
- Long district names wrap inside the chip at 1.4 line-height; chips never truncate.
- School-year label is one editable field on the page, not repeated per chip.

### 7.3 Guiding pillars & stats

- Pillars are an ACF repeater (title, body, CTA label, CTA link) on the page, **max four rows** per
  DS v1.
- Row bodies run to 700px at 15px / 1.7; past ~90 words the row grows rather than clamping.
- Stats are three page fields (value, unit, label). Numerals never animate.
- Stat values are shared with the homepage — store them once in theme options so the two pages
  cannot drift.
- Hero H1 holds to three lines at 52px; at four lines the slab grows and the media offset absorbs
  it.

---

## 8. Image guidance

| Slot | Ratio | Minimum | Notes |
|---|---|---|---|
| Hero | 21:9 | 2400×1030 | The cape line wants breadth: a wide room with educators mid-work, focal interest right of center so the slab covers no face. Eager, `fetchpriority="high"`. |
| Guiding-pillars figure | 4:5 | 1000×1250 | 8px radius, shadow `0 20px 44px rgba(0,92,109,.14)`, one floating caption chip only. |
| Team portraits | 4:5 | 900×1125 | Top-weighted crop, eyes on the upper third, consistent lighting and background across the whole team — this is the one place inconsistency shows immediately. |
| Mission close | 4:5 | 1000×1250 | A two-person moment rather than a room. |

- Documentary color photography only: no duotone, grayscale, filters, or scrims outside the hero.
- WebP with JPEG fallback, explicit `width`/`height` on every image, lazy except the hero.
- Real alt text describing the work; decorative marks get `alt=""`.
- All four photographs in the mockup are placeholders from the approved homepage retreat set. About
  needs its own set so the page does not read as a homepage reprise.

---

## 9. Interaction states

| Element | Rest | Hover | Focus / Active |
|---|---|---|---|
| Primary pill | Coral `#DD6237`, white label | `filter: brightness(.92)` (≈`#C4522C`), 160ms. **No rise.** | 3px `#0A96CB` ring at 2px offset |
| Secondary pill | White, 1px `#d3dde1`, teal label | Border → `#005C6D`, ground → `#f5f6f8`, 160ms | Same ring |
| Team card | Shadow 1 | Shadow 2 + 2px rise, 240ms. Whole card is the trigger; `Read bio` is the accessible button inside it. | Ring on the inner button |
| Nav link | 500 weight, transparent 2px underline | Color → `#DD6237` | — |
| Active nav item (About) | 700 weight, 2px coral underline at 8px offset | — | `aria-current="page"` |
| Footer link | White | Color → `#A7CC14` | Ring |
| Social tile | `rgba(255,255,255,.16)` | White ground, teal glyph | Ring |
| Header burger | White, 1px `#e6eaec` | Ground → `#f5f6f8` | Ring |
| District chips | Paper fill, 1px `#e3e9eb`, cerulean bullet | **None — static, informational, not links** | n/a |

**Focus-visible, everything:** 3px `#0A96CB` ring at 2px offset, never removed.

**Entrances:** fade + 28px rise, 700ms `cubic-bezier(.2,.7,.2,1)`, once per element. Driven by an
Intersection Observer at `threshold: 0.08`, `rootMargin: 0 0 -6% 0`, with a **900ms safety timeout**
that restores opacity so no element can be stranded transparent. Elements already above the fold on
load are never hidden. Fully disabled under `prefers-reduced-motion: reduce`, along with smooth
scrolling and all transitions. No scroll-scrubbed motion on this page.

---

## 10. Developer implementation notes (WordPress)

- **Page template** `page-about.php` composed of existing blocks; no new page-level CSS beyond the
  chip group and the team placeholder tile.
- **CPTs:** `fs_team` (portrait, role, bio, menu_order) and `fs_partner` (district name + `state`
  taxonomy). Both archive-off; both surfaced only through blocks.
- **Repeater:** guiding pillars live on the page (title, body, CTA label, CTA link), hard-capped at
  four rows in the field config.
- **Shared numbers:** the three stats come from theme options so About and the homepage always
  match.
- **Team modal:** one `<dialog>` reused for all cards, populated from the card's `data-bio`; focus
  trapped, Escape closes, focus returns to the triggering card.
- **Load more:** AJAX append with `aria-live="polite"` count announcement; no page reload, no layout
  shift.
- **Anchors:** `#team` with `scroll-margin-top: 106px` for the sticky header.
- **Accessibility:** one `h1`; state names are `h3`; the chip list is a real `<ul>`; the badge image
  carries its membership year in alt text; stat numerals carry a full-sentence `aria-label`
  (e.g. "More than 2 million students impacted") so screen readers don't announce "2+ Million"
  as fragments.
- **Skip link** to `#main` is present and visually offscreen until focused.
- **Mockup caveat:** hero and figure media are CSS background images in the mockup to keep the
  streaming preview clean — ship them as real `<img>` with `width`/`height` and `loading`.

---

## 11. Assumptions & missing assets

Decisions made to keep moving. Correcting any of them requires no new component.

1. **Our Values has no itemized list** in the supplied copy — only its framing paragraph. Mission,
   Values, and Approach are rendered as the three guiding-pillar rows using their own paragraphs
   verbatim. If there are three to five named values, they belong in these rows.
2. **Team records** — no names, roles, bios, or portraits supplied. Six placeholder cards shown
   with the DS v1 teal fallback tile.
3. **All four photographs** are homepage placeholders. About needs its own set, including a wide
   hero that earns the cape line.
4. **"super heros"** in the source doc is set as **"superheroes"** here. Confirm the spelling to
   publish.
5. **"Let's put you on the map."** — read as the closing line of the partner section and paired
   with the contact CTA. If it was meant as a literal US map graphic, that is a new component and a
   separate conversation.
6. **District list is dated** 2024–2025. Needs a current-year list. The MA DESE Approved Provider
   line has been pulled out of the district list into a credential row.
7. **Impact numbers** reused from the homepage. Confirm they are current before they appear on two
   pages.
8. **Team page vs. section** — the nav has a separate Team page. This section is the teaser;
   confirm whether it shows the full roster or the first nine with a link out.
9. **Focused Leadership Framework** is named in the Approach copy but not explained. Referenced as
   text only; if it needs a diagram, that is a new component to scope.
10. **No testimonial on About** — the stats band and partner directory carry the proof. The
    homepage quote block can drop in above the mission close on request.

---

## 12. Content inventory (as built)

**Hero H1:** "From the boardroom to the classroom, every educator has a cape that they wear."

**Guiding pillars:** 01 Our Mission → See Our Impact · 02 Our Values → Meet Our Team · 03 Our
Approach → Explore Our Services

**Stats:** 2+ Million students impacted · 20+ Years partnering with schools · 25+ States served

**Partner states (5) / districts (15 as built):** California (3), Connecticut (2), Illinois (3),
Massachusetts (6), Vermont (1)

**Credentials:** 2026 Manatee Chamber of Commerce member badge · Massachusetts DESE Approved
Provider

**Mission close H2:** "Support the educators who shape students' lives."
