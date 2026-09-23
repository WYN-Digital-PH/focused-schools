# Focused Schools — Component Specifications

**Companion to:** `docs/design-system.md` (tokens, rules) and `docs/page-specs/*.md` (page composition)
**Last updated:** September 23, 2026

Each entry lists where the component comes from, its anatomy, its states and the rules for implementing it.
**Status key:** Reused = the homepage version, unchanged · Extended = homepage version with added states or slots · New = no homepage precedent, approved during page design.

---

## Index

| # | Component | Status | Used on |
|---|---|---|---|
| 01 | Buttons (primary, secondary, on-teal, arrow link) | Reused | All |
| 02 | Sticky header + mobile menu | Reused | All |
| 03 | Footer | Reused | All |
| 04 | Page hero (media + teal slab) | Reused | Services, About, Team, Stories, Podcast |
| 05 | Compact label-rail hero | Reused | Contact |
| 06 | Section header (split / label-rail) | Reused | All |
| 07 | Content/media split + caption chip | Reused | About, Services |
| 08 | Service Lane block | New | Services |
| 09 | Lane index rows | Reused | Services |
| 10 | Base card | Reused | — (skins below) |
| 11 | Team card | Extended | About, Team |
| 12 | Impact Story card | Extended | Stories, homepage |
| 13 | Episode / video card | New | Podcast |
| 14 | Link card (accent bar) | Reused | Contact, cross-links |
| 15 | Teal aside / feature card | Reused | Contact, Podcast |
| 16 | Numbered list (commitments / steps) | Reused | About, Contact |
| 17 | Statistics row | Reused | About, Stories |
| 18 | Testimonial block | Reused | Services, About |
| 19 | Partner directory | Reused | About |
| 20 | Filter bar | New | Stories, Team |
| 21 | Tag / chip | Reused | Stories, Team, Podcast |
| 22 | Form field | Extended | Contact |
| 23 | Error summary panel | Extended | Contact |
| 24 | Success panel | New | Contact |
| 25 | Team bio modal | New | Team (pending decision) |
| 26 | Subscribe row | New | Podcast |

---

## 01 · Buttons

There are three variants and an arrow link. No others.

| Variant | Default | Hover | Active | Focus |
|---|---|---|---|---|
| **Primary** | Coral `#DD6237`, white label, 48px, 999px, padding 0 28px | `brightness(.92)` ≈ `#C4522C`, 160ms. No rise | `scale(.99)` | 3px `#0A96CB` ring, 2px offset |
| **Secondary** | White, 1px `#d3dde1`, teal label, 46px | Border → teal, ground → paper, 160ms | — | Same ring |
| **On-teal** | White fill, teal label | Ground → paper | — | Same ring |
| **Arrow link** | 11–13px/700/0.1em uppercase teal, coral `→`, 12px gap | Label → coral, gap 12 → 18px, 240ms | — | Same ring |

- Label: 13px / 700 / 0.09em uppercase. Minimum height 44px on mobile, where a button standing alone goes full width.
- **One primary per section.** Never put two primaries next to each other. Lane accents never fill a button.
- **Disabled / sending:** 45% opacity, `cursor: not-allowed`, `aria-busy`. The label changes (e.g. "Sending…"). A spinner is never the only signal.

---

## 02 · Sticky Header

- White, 88px desktop / 74px mobile, sticky with shadow-1 once scrolled. It never shrinks.
- Logo 34–40px tall. Nav items 15px/700 teal, 26px apart. The active item gets a 2px coral underline at 8px offset + `aria-current="page"`.
- Right side: "Let's Talk" primary + "Login" secondary.
- **≤1023:** hamburger opens a full-height teal panel with links at 22px/700 white and the primary button full width at the bottom.
- **≤767:** logo + menu only. The "Let's Talk" pill is dropped because each page already repeats the CTA.

## 03 · Footer

- Teal base, 96px top / 40px bottom. White reversed lockup at 40px.
- Four link columns (15px/500, white 85%, hover white + underline). Tagline in 18px italic.
- Mark watermark bottom-right at 8%. Legal row 13px above a 1px white-18% rule.
- ≤1023 → 2-up columns. ≤767 → stacked.

---

## 04 · Page Hero (media + teal slab)

- Full-bleed 21:9 photo (4:5 art-directed crop on mobile). A solid teal slab overlaps the bottom-left: 24px radius, offset `bottom: −56px`.
- Slab: lime eyebrow (page name) → H1 in white → one line of body at 88% → one primary button.
- ≤1240: the slab stops overlapping and pulls up −45px. ≤767: it sits flush under the media at full width.
- **Rules:** keep the left third of the photo quiet and never put a face under the slab. No type directly on the photo. Eager load + `fetchpriority="high"`.

## 05 · Compact Label-Rail Hero

- 264px rail (28px mark + eyebrow) + `1fr` column. H1 68px, body 19px, max 62ch. Padding 72/80px.
- No media, on purpose, so a page's main task (e.g. the Contact form) starts above the fold.

## 06 · Section Header

- **Split:** eyebrow + H2 on the left, lead paragraph + optional arrow link on the right. `align-items: end`, 80px gap, 56–96px above the content.
- **Label-rail:** the same 264px rail as 05, for sections that open a page.
- Eyebrow 12px/700/0.14em uppercase: **teal on light, lime on teal.** 26px from eyebrow to H2.
- ≤1240: collapses to one column with a 40px gap.

## 07 · Content/Media Split

- `1fr` copy + 460–500px media, 96–100px gap, `align-items: start`.
- Media: 4:5 or 3:2, 8–24px radius, `--fs-shadow-media`. The side alternates between consecutive splits.
- **Caption chip (optional, max one per section):** white, 16px radius, shadow-2, 12/18px padding, overlapping the media corner by 16–24px. Kicker in the lane accent (lime falls back to cerulean).
- On mobile, media always stacks **below** the copy.

---

## 08 · Service Lane Block

One partial, rendered once per `fs_service` record.

| Slot | Source | Spec |
|---|---|---|
| Accent row | `accent` + loop index | 26×3px accent bar + 2-digit numeral, 30px above the title |
| Title | `post_title` | 52px / 700 / −0.03em, max 18ch |
| Tagline | `tagline` | 21px / 700 teal, max 30ch |
| Description | `short_description` | 17px / 1.75 gray, max 62ch |
| Offerings | `signature_offerings` repeater | 6×6px accent squares. Two columns at 4+ items, 16/40px gap |
| CTA | `cta_label` / `cta_link` | Primary pill |
| Media | `featured_image` | 4:5, 8px radius, 500px column |

- **Derived from loop position:** numeral, ground (white/paper by `$index % 2`), media side, anchor `#{post_name}`.
- **Accent appears in only three places:** bar, bullets, caption kicker. Never in type, button fills, grounds or focus rings.
- **Empty fields:** no offerings → label and rule are removed. No image → copy spans 70ch. No CTA → button is omitted. No accent → teal.
- Mobile order: copy → offerings → CTA → image.

## 09 · Lane Index Rows

- Built from the same query as 08. Each row is one link: numeral · title · tagline · arrow. 36px padding, hairlines above and below.
- Hover: ground → `#eef1f3`, 240ms. Wrapped in `<nav aria-label="Jump to a service">`.
- ≤1023: `56px 1fr` with the tagline on a second row and the arrow hidden.

---

## 10 · Base Card

The shared shape for every card skin:

| Property | Value |
|---|---|
| Ground / border | White, 1px `#e3e9eb` |
| Radius / shadow | 16px, shadow-1 |
| Padding | 24–32px (24px mobile) |
| Layout | `display: flex; flex-direction: column`. **The footer is pinned with `margin-top: auto`** |
| Hover | Shadow-2 + 2px rise + border → teal, 240ms. Title → coral where the card has a title link |
| Link | The whole card is one `<a>` (or the title link has a stretched `::after`) |

Skins change the content and one accent detail only. They never change the shape.

## 11 · Team Card

- 4:5 portrait (top-weighted crop) → name H3 22px/700 → role 15px gray → "Read bio →" footer.
- **The footer is pinned**, so cards line up when role lengths differ. Role is clamped to 2 lines.
- Grid 3 / 2 / 1. Any number of cards, from the `fs_team` CPT in `menu_order`. Loads in pages of 9 on mobile.
- No portrait → teal initials tile. The grid never mixes photos and initials.
- Fields: `post_title`, `role`, `bio`, `portrait`, `menu_order`, optional `department` taxonomy (filter).

## 12 · Impact Story Card

- 3:2 cover with a white state/level tag at the top left → H3 (3-line clamp) → excerpt (2-line clamp) → meta row (district · year) → "Read the story →".
- **Normalize helper:** CPT `fs_story` records and legacy Pages both go through `fs_normalize_story($post)` → `{title, url, cover, state, level, year, excerpt, is_legacy}`. One partial renders both.
- Legacy fallbacks: no cover → teal watermark tile. No excerpt → `wp_trim_words(content, 24)`. No state → tag omitted.
- The "Legacy" pill is hidden by default, pending a decision.

## 13 · Episode / Video Card

- 16:9 static thumbnail on `#004855` → 58px raspberry play button → duration chip at the bottom right.
- Body: raspberry eyebrow ("Episode 14") → H3 (2-line clamp) → 2-line excerpt.
- **Click-to-mount:** the button swaps the thumbnail for the YouTube iframe (`youtube-nocookie.com`, `autoplay=1`). **Nothing third-party loads until the user clicks.** Buzzsprout player embeds are kept as-is.
- Thumbnail = cached YouTube still (`maxresdefault` → `hqdefault` fallback), stored server-side.
- Button: `aria-label="Play: {title}"`. After mounting, focus moves to the iframe.

## 14 · Link Card

- Base card, 220px minimum height, `justify-content: space-between`.
- 26×3px accent bar **in the destination section's color** (Stories cerulean, Services coral, Podcast raspberry, Team lime) → 24px/700 title → 15px note → footer CTA with a coral arrow.
- Rendered from a capped page repeater (title, note, CTA label, link, accent key).

## 15 · Teal Aside / Feature Card

- Teal ground, 16px radius, no border, no shadow. Padding 32–36px. Mark watermark at 8%, clipped at the bottom right.
- Lime eyebrow, white type, 88% white body. Key/value rows: 10px/700 caps key at 66% white above a 19px/700 value, separated by white-22% hairlines. Value hover → lime.
- **Max two per page.** Sticky when used as an aside (`top: 118px`), un-sticks at ≤1240.

## 16 · Numbered List

- `30–34px + 1fr` grid. 2-digit coral numeral, H4 20px/700, 15px body, optional arrow link.
- The commitments variant adds a 2px left rule on the active row (lime on light is allowed only as a **rule**, gold on teal). The steps variant uses `#eef1f2` row hairlines.
- Max four rows.

## 17 · Statistics Row

- 3-up. Numeral 64px/900 (72px coral on light) → uppercase unit (lime on teal) → one-line caption.
- Gold 3px top rule on teal. Numerals never count up or animate. Stacks at ≤767.

## 18 · Testimonial Block

- Coral quote glyph (or 78px coral slash column) → 24px italic quote, max 40 words, 46–52ch → attribution eyebrow → role at 80%.
- The static version is used on internal pages. The homepage carousel version (coral active dot, swipe, no autoplay) is the same component with its controls turned on.

## 19 · Partner Directory

- Text chips, not logos: 8px radius, 12/18px padding, grouped by state under a state eyebrow.
- From the `fs_partner` CPT with a `state` taxonomy, sorted alphabetically by state and then by district. Static, no marquee.

---

## 20 · Filter Bar

- A row of toggle pills (`aria-pressed`) with "All" first, a live result count at the right (`aria-live="polite"`) and a "Clear" text link when a filter is active.
- Pill: 10–11px/700 caps. Inactive = `#eef1f3` ground, teal label. Active = teal ground, white label.
- Filters by taxonomy (state, level, year / department). Results update in place without a page reload. The URL keeps `?state=` for sharing.
- ≤767: the row scrolls horizontally with a snap and no visible scrollbar. The count moves above the row.
- Empty state: one sentence + "Clear filters" secondary button. Never a blank grid.

## 21 · Tag / Chip

- 10px/700/0.12em uppercase, 6/12px padding, pill shape.
- **Neutral:** `#eef1f3` ground, teal ink. **New:** coral ground, white ink. **On-media:** white ground, teal ink.
- A chip labels content. It's never a button unless it's used in 20.

---

## 22 · Form Field

| Property | Value |
|---|---|
| Height / radius | 48px / 8px (textarea 6 rows, `resize: vertical`) |
| Border | 1px `#e0e6e9` → hover `#c8d2d6` |
| Text / placeholder | 15px teal / `#9aa4a8` |
| Label | 12px/700/0.06em teal, 10px above the field, always visible |
| Optional tag | 9px/0.12em caps `#9aa4a8` |
| Help / error text | 13px/1.5, 8px below the field |
| Select | Native `appearance` reset, custom 16px chevron (`pointer-events: none`) |
| Checkbox | 20px, `accent-color: #005C6D`, the whole label is clickable |

| State | Treatment |
|---|---|
| Focus | Border teal + 3px `rgba(10,150,203,.22)` halo |
| Error | Border `#C0392B`, ground `#fdf7f6`, message in `#C0392B`, `aria-invalid`, `aria-describedby` |
| Error + focus | Red border **and** cerulean halo |
| Disabled | Paper ground, 55% text. Only used while sending |

**Validation:** on blur, never on keystroke. Once a field is in error it re-checks on input. On submit it validates everything and moves focus to the summary. Messages tell the user how to fix the problem. `novalidate` is set on the form, but real `type`, `inputmode` and `autocomplete` values stay in place.

## 23 · Error Summary Panel

- `#fdf3f1` ground, 1px `#f0c8c1`, 12px radius, 20/22px padding, 30px above the first field.
- 24px red circle with "!" → 15px/700 `#8E2A1E` headline naming the error count → underlined in-page links to each field.
- `role="alert"`, `tabindex="-1"`, gets focus on submit and scrolls to 106px below the top.
- The same panel is used for network or server failure: all values are kept, with "Try again" plus a mailto link.

## 24 · Success Panel

- **Replaces the form in place.** No redirect, and the aside stays.
- White card, 56/52/60px padding. 64px teal check circle → "Message sent" eyebrow → H2 42px → 17px body repeating the reply-time promise → rule → "While you wait" + two secondary pills.
- `role="status"` and gets focus. No entrance motion. **The analytics conversion fires when this panel renders**, not on click.
- The reply-time text comes from the same theme option as the line under the submit button.

## 25 · Team Bio Modal *(pending decision)*

- Centered dialog, max 880px, 16px radius. Two columns: 4:5 portrait + name, role, bio (17px/1.75, scrolls inside the dialog).
- Native `<dialog>` with `showModal()`: focus is trapped, Esc closes it, and focus returns to the card that opened it. The backdrop is `rgba(0,72,85,.6)`.
- Each bio updates the URL to `?member={slug}` so it can be shared. If `/team/{slug}` single pages are chosen instead, the same layout becomes the single-page template and the card links there.

## 26 · Subscribe Row

- Three platform pills (Apple, Spotify, Buzzsprout) as secondary buttons, each with a 16px platform glyph and an 8px gap.
- Shown on teal (on-teal variant) in the Podcast hero and on paper above the episode grid.
- URLs come from a theme option. If a URL is empty, that pill is left out.

---

## Implementation Checklist (every component)

- [ ] Uses tokens only, with no hex values in the template
- [ ] 3px cerulean focus ring on every interactive element
- [ ] Minimum 44px tap target
- [ ] Long content: clamps or measures are defined, with no overflow at 320px
- [ ] Empty field behavior defined, with no empty rules or dangling labels left behind
- [ ] `prefers-reduced-motion` respected
- [ ] Repeating content comes from a CPT or repeater, never duplicated by hand
