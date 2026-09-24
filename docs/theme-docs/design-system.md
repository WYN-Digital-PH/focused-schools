# Focused Schools — Design System

**Version:** v1.1 (DS v1 plus the values locked while building the pages)
**Sources of truth, in order:** approved homepage → Brand Field Guide → supplied page copy
**Visual reference:** `Design System v1.dc.html`
**Page specs:** `docs/page-specs/*.md` · **Components:** `docs/component-specs.md`
**Last updated:** September 23, 2026

> Where the six built pages differ from DS v1, this file has the final value. Those rows say
> **Supersedes v1**. Anything not listed here needs sign-off before it's used.

---

## 1. Principles

1. **Extend the homepage, don't reinvent it.** No new art direction, fonts, colors or one-off components.
2. **Each color has one job.** Brand colors mark a role (a lane, the marketing voice, the primary action). They aren't decoration.
3. **One component, many records.** Repeating content (Services, Team, Stories, Episodes) comes from a CPT through one card or block partial. Editors never copy cards by hand.
4. **Editors pick from limited options.** Accents are stored as token keys, sections only offer ground and padding settings, and there's no color picker anywhere.
5. **Keep motion quiet.** Entrances play once, there's no scroll-jacking, and the rotating Cycle mark on the homepage is the only loop.

---

## 2. Color

### 2.1 Brand palette (locked)

| Token | Hex | Role | Sets type? |
|---|---|---|---|
| `--fs-teal` | `#005C6D` | Ink, dark sections, footer, eyebrows on light grounds | **Yes**, the default ink |
| `--fs-cerulean` | `#0A96CB` | Strategy & Vision lane, focus ring, links on light grounds | Links and kickers only |
| `--fs-coral` | `#DD6237` | Leadership & Systems lane, **the only primary-button fill** | Numerals, arrows, active nav |
| `--fs-lime` | `#A7CC14` | Capacity & Coaching lane | **Never** on light. Eyebrows and hovers on teal only |
| `--fs-gold` | `#F3C145` | Emphasis, star glyph, stat rules on teal | **Never** |
| `--fs-raspberry` | `#BC3E8C` | Marketing voice | Podcast and Blog only |

### 2.2 Interface neutrals

| Token | Value | Use |
|---|---|---|
| `--fs-gray` | `#51536a` | Secondary body, captions, meta |
| `--fs-white` | `#ffffff` | Default ground, cards |
| `--fs-paper` | `#f5f6f8` | Alternating light ground |
| `--fs-paper-hover` | `#eef1f3` | Row hover ground, neutral tags |
| `--fs-teal-deep` | `#004855` | Teal-on-teal cards, video thumb ground |
| `--fs-line` | `#e3e9eb` | Card border |
| `--fs-line-soft` | `#eef1f2` | Inner rules |
| `--fs-line-strong` | `#d3dde1` | Secondary pill border, section hairlines |
| `--fs-field` | `#e0e6e9` | Input border (hover `#c8d2d6`) |
| `--fs-placeholder` | `#9aa4a8` | Placeholder text, "Optional" tag |
| `--fs-coral-hover` | `#C4522C` | Primary hover (≈ `brightness(.92)`) |

### 2.3 Feedback colors (form states only)

| Token | Value | Use |
|---|---|---|
| `--fs-error` | `#C0392B` | Error border, message, required asterisk |
| `--fs-error-ground` | `#fdf7f6` | Tint behind a field in error |
| `--fs-error-panel` | `#fdf3f1` / border `#f0c8c1` / ink `#8E2A1E` | Error summary panel |

**Supersedes v1:** DS v1 specced gray-only validation. Contact moved to a red from the coral family, always paired with an icon, a tinted ground and a text message, so color is never the only signal (WCAG 1.4.1).

### 2.4 Binding color rules

1. **At most two ground colors per page:** white/paper plus teal.
2. **Coral is the only primary fill** anywhere on the site. Use one primary action per section. A lane accent never fills a button.
3. **Eyebrows on white or paper are teal `#005C6D`.** Eyebrows on teal are lime. **Supersedes v1**, which had coral eyebrows on light grounds.
4. **Lime and gold never set type on light grounds.** That covers links, labels and captions. Where the Capacity lane needs a text kicker, it falls back to cerulean.
5. **Raspberry only appears on Podcast and Blog.** Never on client-delivery pages.
6. **Body text** is gray on light grounds and white at 88% on teal. Never gray on teal.
7. **No gradients** except the teal scrim behind hero media. Text never sits on a brand tint below 4.5:1.
8. **Lane accents are stored as keys** (`cerulean | coral | lime | teal`) and mapped to tokens. If none is set, the fallback is teal.

### 2.5 Contrast reference

| Pair | Ratio | Rule |
|---|---|---|
| Teal on white | 8.4:1 | Any size |
| Gray on white | 6.9:1 | Any size |
| White on teal | 8.4:1 | Any size |
| White on coral | 3.6:1 | Button labels only, 13px/700 uppercase or larger |
| Lime or gold on white | < 2:1 | Never as text |

---

## 3. Typography

**DM Sans only.** Weights 400 / 500 / 700 / 900, plus 400/700 italic. Self-hosted woff2 with `font-display: swap`.

Most headlines follow this pattern: the sentence is set in **Regular 400** and the key phrase in **Bold 700 or Black 900**.

### 3.1 Scale (as built, desktop)

| Role | Size / line-height | Weight | Tracking | Measure |
|---|---|---|---|---|
| Display (section openers) | 84px / 1.0 | 400 + 700 | −0.03em | 11–14ch |
| H1 page title | 68px / 1.0–1.03 | 700 (or 400 + 700) | −0.03em | 14–18ch |
| H2 section | 58–68px / 1.02 | 400 + 700 | −0.03em | 14–18ch |
| H2 block (lane title, form head) | 52px / 1.05 | 700 | −0.03em | 18ch |
| H2 small | 32–42px / 1.06–1.12 | 700 | −0.02em | 22ch |
| H3 card title | 22–24px / 1.2 | 700 | −0.02em | — |
| Tagline / lead | 19–21px / 1.5–1.65 | 400–700 | 0 | 30–62ch |
| Body | 17px / 1.75 | 400 | 0 | 62ch max |
| Small / meta | 15px / 1.5–1.6 | 400 | 0 | — |
| Help text / captions | 13px / 1.5–1.6 | 400 | 0 | 34–56ch |
| Eyebrow | 12px / 1.6 | 700, uppercase | 0.14em | — |
| Button label | 13px | 700, uppercase | 0.09em | — |
| Card CTA / "Read bio" | 11px | 700, uppercase | 0.1em | — |
| Tag / chip | 10px | 700, uppercase | 0.12em | — |
| Form label | 12px | 700 | 0.06em | — |
| Stat numeral | 64–72px / 1.0 | 900 | −0.03em | — |

**Supersedes v1:** v1 had a 58–64px H1, 40px H2 and sentence-case 16px button labels. The approved pages use the larger display scale above and 13px uppercase button labels. Use the built values.

### 3.2 Responsive type steps

| Breakpoint | Display | H1 | H2 | Lane title | Body |
|---|---|---|---|---|---|
| ≥1241 | 84 | 68 | 58–68 | 52 | 17 |
| ≤1240 | 60 | 46 | 46 | 44 | 17 |
| ≤767 | 40 | 34 | 36 | 32 | 16 |

Only these two steps. Don't add a fluid `clamp()` to individual elements. Form inputs stay 15px (effectively 16px) on mobile to stop iOS from zooming.

---

## 4. Layout

### 4.1 Containers

| Container | Max width | Use |
|---|---|---|
| Full-bleed | 100vw | Hero media, teal bands |
| Shell | **1400px**, 24px gutter | Every content section, header, footer |
| Text measure | 720px (≈62–70ch) | Story body, legal, long intros |

**Supersedes v1:** v1 had a 1200px standard container and a 1440px wide one. The built homepage and all six pages use a single 1400px shell.

### 4.2 Grid patterns

- **Label-rail header:** 264px rail (mark + eyebrow) + `1fr` content. Used for page heroes and section intros.
- **Split header:** `minmax(0,1fr) minmax(0,1fr)`, 80px gap, `align-items: end`. Eyebrow + H2 on the left, lead + link on the right.
- **Content/media split:** `1fr` + 460–500px media, 96–100px gap.
- **Form + aside:** `1fr` + 400px sticky aside, 80px gap.
- **Card grids:** `repeat(3, 1fr)` for Team, Stories and Episodes. `repeat(4, 1fr)` for reach cards. 20–24px gap.
- Always left-aligned. No centered body copy and no justified text.

### 4.3 Breakpoints

| Name | Range | Main change |
|---|---|---|
| Desktop | ≥1241 | Full grids, sticky asides, 3-up cards |
| Laptop | 1024–1240 | Splits stack, asides un-stick, type steps down |
| Tablet | 768–1023 | Hamburger nav, cards 2-up, footer 2-up |
| Mobile | ≤767 | Single column, 20px gutter, full-width buttons |

An extra step at **900px** is used only where a 3-up grid gets too tight (Stories, Podcast).

---

## 5. Spacing

Built on a 4px base. These are the values that recur across pages:

| Token | Value | Use |
|---|---|---|
| `--sp-1` | 8px | Icon to label, field to message |
| `--sp-2` | 16px | Text stack inside a card |
| `--sp-3` | 20–24px | Card-to-card gap, paired fields, gutter |
| `--sp-4` | 26–30px | Eyebrow to heading, field group gap |
| `--sp-5` | 40–48px | Block to block inside a section |
| `--sp-6` | 56–80px | Section header to content |
| `--sp-7` | 96px | Section padding (dense pages), tablet |
| `--sp-8` | 120px | Section padding, light and teal |
| Mobile section | 64px | All sections ≤767 |
| Anchor offset | 106px | `scroll-margin-top` to clear the sticky header |

Adjacent sections on the same ground share one padding value. The gap doesn't double.

---

## 6. Shape & Elevation

| Token | Value | Use |
|---|---|---|
| `--fs-r-sm` | 8px | Inputs, lane figures, small media |
| `--fs-r-md` | 12px | Inner panels, error summary, consent panel, spec cards |
| `--fs-r-lg` | 16px | Cards, form shell, aside cards |
| `--fs-r-xl` | 24px | Hero slab, feature media |
| `--fs-r-pill` | 999px | Buttons, tags, dots |
| `--fs-shadow-1` | `0 1px 2px rgba(0,92,109,.08)` | Resting card |
| `--fs-shadow-2` | `0 12px 28px rgba(0,92,109,.12)` | Hover, floating chips |
| `--fs-shadow-media` | `0 20px 44px rgba(0,92,109,.14)` | Lane and feature photos |

A card never has both a visible border and shadow-2 at rest.

---

## 7. Imagery

| Ratio | Use | Minimum source |
|---|---|---|
| 21:9 | Page hero (desktop) | 2400 × 1030 |
| 4:5 | Hero mobile crop, lane photos, team portraits | 1000 × 1250 |
| 3:2 | Impact Story covers | 1200 × 800 |
| 16:9 | Video / episode thumbnails | 1280 × 720 |
| 1:1 | Podcast cover art | 1400 × 1400 |

- Documentary color photography only. No filters, duotone, grayscale or stock photos. People should be shown doing the work.
- The teal gradient scrim is only allowed on hero media.
- Mobile crops are art-directed with `<picture>`, not CSS cropping.
- Every `<img>` needs `width`/`height` and lazy loading (except the hero). Alt text describes what's happening, not the brand.
- **No-image fallback:** teal tile with the brand mark at 7–12% opacity. Team uses initials in white 900 instead. A grid never mixes photos and fallbacks.

---

## 8. Brand Motifs

Only two are allowed:

1. **Starless mark** — the Cycle of Excellence. It rotates on the homepage (40s linear). It's static everywhere else.
2. **Mark watermark** at 6–12% opacity, clipped bottom-right, on teal bands, teal aside cards and the footer. Always `aria-hidden`.

**Plus one structural accent:** the 26 × 3px accent bar that opens lane blocks and link cards.

No invented shapes, no blobs, and no icon-in-circle patterns beyond the homepage stroke set.

---

## 9. Motion

| Token | Value | Use |
|---|---|---|
| `--fs-dur-fast` | 160ms | Color, border and filter hovers |
| `--fs-dur-base` | 240ms | Card rise, row ground, arrow gap |
| `--fs-dur-enter` | 700ms | Reveal entrance |
| `--fs-ease` | `cubic-bezier(.2,.7,.2,1)` | Everything |

**Reveal rules (final):**

- Fade + 28px rise, once per element, triggered by an IntersectionObserver at `threshold: 0.08` with `rootMargin: 0 0 -6% 0`.
- **Anything already on screen when the page loads is never hidden.** Only elements below the fold start transparent.
- **900ms safety timeout** clears opacity on every `[data-reveal]`, so content can't stay invisible if the observer fails.
- Under `prefers-reduced-motion`, all entrances and the Cycle rotation are off. Hovers keep their color changes only.
- States that follow a user action (success panel, modal, lazily loaded iframe) appear without entrance motion.

---

## 10. Accessibility Baseline

- **Focus ring everywhere:** 3px `#0A96CB` at 2px offset. Never removed, never restyled per component or lane. Inputs use a 3px `rgba(10,150,203,.22)` halo.
- Tap targets are at least 44px.
- One `h1` per page, and headings follow document order. Eyebrows and offering labels are `<p>`, not headings.
- Each page has a skip link to `#main`, and the current nav item carries `aria-current="page"`.
- Accordions, filters and play buttons are real `<button>`s with `aria-expanded` / `aria-pressed` as needed.
- Live regions: the error summary is `role="alert"` and the success panel is `role="status"`. Focus moves to each when it appears.

---

## 11. WordPress Implementation

- Tokens are defined once in `_tokens.css` on `:root`. **No hex values in templates or ACF.**
- Every component is one template part or block. Pages are built from these parts, and a new page should need no new CSS.
- The section wrapper exposes **ground** (white / paper / teal) and **padding** (default / tight) and nothing else.
- CPTs: `fs_service`, `fs_team`, `fs_story`, `fs_episode`, `fs_partner`. All are ordered by `menu_order` unless a spec says otherwise.
- Values that come from position (numerals, ground alternation, media side, anchors) are never entered by editors.

---

## 12. Open Decisions

| # | Item | Owner |
|---|---|---|
| 1 | Team bios: modal vs. `/team/{slug}` single pages | Client |
| 2 | Impact Stories: show or hide the "Legacy" pill; Year as a taxonomy vs. a meta field | Client + dev |
| 3 | Technical Assistance: fourth homepage row vs. part of Leadership & Systems | Client |
| 4 | Cycle mark reuse beyond the homepage (currently static on internal pages) | Designer |
| 5 | Contact reply-time promise (placeholder: "within two business days") | Client |
| 6 | Partner display: text chips vs. licensed logos | Client |
