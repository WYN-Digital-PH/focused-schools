# Podcast — Page Design Specification

**Page:** Podcast ("Conversations on Learning")
**Template:** `page-podcast.php`
**Design system:** Focused Schools DS v1
**Source mockup:** `Focused Schools Podcast.dc.html`
**Status:** Approved for development
**Last updated:** September 17, 2026

---

## 1. Overview

**A reskin, not a rebuild — both players keep working.** The copy doc says "Podcast (keep as is)",
so all page copy is lifted verbatim from the live page. The work here is structure and skin: give
the Buzzsprout embed a proper home, replace the Elementor video block with a card that does not load
six iframes, and adopt raspberry as the marketing accent per DS v1.

Ten blocks. Eight reused, one extended (the video card), one new (the embed shell).

### Section hierarchy

The old page was H1 → Buzzsprout → "prefer to watch" → contact form. Same spine, two additions, one
removal.

| # | Section | Ground | Purpose |
|---|---|---|---|
| 01 | Hero | White + teal slab | H1 "Podcast" with the show name as a raspberry-ruled eyebrow, the intro paragraph verbatim, and two CTAs — Listen and Watch — because the page has two audiences. |
| 02 | Subscribe rail | White | **New.** Four platform tiles on the approved 264px label rail. The old page had no way to subscribe, which is the main job of a podcast page. |
| 03 | Latest episode | Teal `#005C6D` | Teal split panel. Newest episode gets the Buzzsprout single-episode player, so listening starts without scrolling. |
| 04 | All episodes (Buzzsprout) | Paper `#f5f6f8` | The full vendor player in a branded shell. **Preserved, not rebuilt.** |
| 05 | Prefer to watch our podcasts? | White | The old page's video section, rebuilt as a 3-up card grid with lazy players instead of stacked iframes. |
| 06 | Closing CTA | Paper `#f5f6f8` | Replaces the inline contact form. Primary to Contact, secondary to the Blog. |

**Removed:** the inline Contact Us form. Every other page in the rebrand ends with a CTA that links
to Contact; a full form mid-funnel on a listening page is friction. The form itself is untouched on
its own page. *(This is the one structural change to flag explicitly — a deliberate call, easily
reversed.)*

### Component map

| Component | Source | Status |
|---|---|---|
| Sticky header / footer | Homepage, verbatim; Podcast added to nav and active | Reused |
| Page hero (media + teal slab) | Homepage hero; raspberry rule on the eyebrow | Reused |
| Subscribe tile row | DS v1 link tile on the 264px label rail | Reused |
| Latest episode split | Homepage teal content/image band | Reused |
| **Embed shell** | **Titled container for third-party players** | **New** |
| Buzzsprout episode row | Homepage index row, narrowed | Reused |
| **Podcast Video Card** | **DS v1 podcast card + date, Watch action, in-card mount** | **Extended** |
| Load more button | DS v1 secondary pill + pagination rule | Reused |
| Section header + eyebrow rule | Homepage section header | Reused |
| Closing CTA split | Homepage contact split, form replaced by buttons | Reused |

**New:** the embed shell — a titled container that gives a third-party player a branded frame
without touching its markup. **Extended:** DS v1's podcast card, which already specifies a static
thumbnail with a raspberry play button and no always-loaded iframe; this adds the publish date, the
explicit Watch action, and the in-card mount state.

---

## 2. Brand accent treatment

**Raspberry `#BC3E8C` is the accent on this page and Blog only**, per DS v1. It appears as:

- the 26×3px rule beside every section eyebrow,
- episode-number eyebrows on cards and Buzzsprout rows (11px / 700 / 0.12em uppercase),
- the 58px play button fill and the in-card play arrows,
- hover borders on subscribe tiles and the Watch link's hover color,
- the audio progress bar fill,
- the Buzzsprout player accent (set in the Buzzsprout dashboard, not by CSS override).

**Raspberry never replaces coral.** The closing primary CTA stays coral `#DD6237`, because coral is
the only primary-button fill site-wide, and the active nav underline stays coral — the nav belongs to
the site, not the section. **The focus ring stays cerulean `#0A96CB`** site-wide, never raspberry.

**Full palette on this page:** teal `#005C6D` · teal deep `#004855` (thumbnail fallback ground) ·
raspberry `#BC3E8C` (accent) · raspberry deep `#A33579` (play hover) · coral `#DD6237` (primary CTA,
active nav) · lime `#A7CC14` (footer hover only) · cerulean `#0A96CB` (focus) · paper `#f5f6f8` ·
hairlines `#d3dde1` / `#e3e9eb` / `#eef1f2` · body gray `#51536a`.

**Type:** DM Sans only. Hero H1 56px / 0.96 / 700 · display H2 68px · latest-episode H2 46px · card
title 22px / 3-line clamp · Buzzsprout row title 20px / 2-line clamp · body 17px / 1.75 · card date
14px · eyebrows 11–12px / 700 / 0.12–0.14em uppercase.

---

## 3. Hero

- Media 21:9, 8px radius, teal `#004855` fallback, 44×44 white corner bracket top-right.
- Teal slab **700px wide**, square, `left: 24px; bottom: -56px`, 44px padding.
- Eyebrow row: 26×3px **raspberry** rule + "Conversations on Learning" in white.
- H1 56px / 0.96 / −0.025em / 700 white: **"Podcast"**.
- Intro paragraph verbatim from the live page, 16px / 1.6, max 540px.
- Two CTAs: **raspberry** primary "Listen Now →" (anchors `#listen`) and a ghost pill "Watch
  Episodes →" (1px `rgba(255,255,255,.4)`, hover fill `rgba(255,255,255,.12)`, anchors `#watch`).
  This is the one place raspberry takes a primary-button role, and it is scoped to the section it
  introduces.

---

## 4. Subscribe rail

- 264px label rail ("Subscribe anywhere") + a 4-column tile grid at 16px gap.
- Tile: white, 12px radius, 1px `#e3e9eb`, shadow-1, padding 22/24px, `space-between` with a
  raspberry arrow at the right.
- Platform name 17px / 700 teal; kind label below at 12px / 700 / 0.1em uppercase `#51536a`
  ("Audio", "Video", "All episodes").
- Hover: border → raspberry, shadow-2, 2px rise at 240ms.
- Platforms shipped: Apple Podcasts, Spotify, Buzzsprout, YouTube. Tiles are a capped page repeater
  (name, kind, URL) so platforms can be added or removed without a template change.

---

## 5. Latest episode (Buzzsprout single embed)

- Section label: raspberry rule + "Latest episode".
- Article grid `minmax(0,1fr) 520px`, teal ground, 8px radius, `overflow: hidden`.
- Copy panel padding **60px 64px 64px**: eyebrow "Episode 14 · March 4, 2026", H2 46px / 1.06 / 700
  max 24ch, summary 17px / 1.7 max 58ch.
- **Buzzsprout single-episode player mount:** a `rgba(255,255,255,.1)` panel with a
  `rgba(255,255,255,.22)` border, 12px radius, 20/22px padding, holding the vendor player. Play
  button 54px raspberry; state label and duration on one row at 12px / 700; 4px progress track
  `rgba(255,255,255,.26)` with a raspberry fill and a proper `role="progressbar"` with
  `aria-valuenow`.
- Media panel: 1:1 cover art, teal fallback with the mark at 16%.

---

## 6. All episodes — the embed shell (new component)

The shell is ours; **everything inside is the vendor's own player.**

- Container: white, 16px radius, 1px `#e3e9eb`, shadow-1, `overflow: hidden`.
- **Shell header** 24/28px padding with a 1px `#eef1f2` bottom rule: a 40px teal tile with the
  brand mark, the show name at 17px / 700, and "Hosted on Buzzsprout" at 12px / 700 / 0.1em
  uppercase.
- **Episode rows** (vendor markup in production): `46px + 1fr + auto` grid, 22px gap, 22/28px
  padding, 1px `#eef1f2` rules between rows.
  - Play button 46px raspberry circle.
  - Raspberry episode eyebrow, title 20px / 700 / 2-line clamp, date 14px gray.
  - Duration right-aligned at 13px / 700 `#51536a`.
- Row hover: ground → `#f7f9fa` at 160ms; the play button inverts to raspberry fill. Rows keep their
  hairlines.
- Section header carries a secondary pill "Open in Buzzsprout →".

### Preserving the Buzzsprout model

- **Buzzsprout stays Buzzsprout.** The existing embed script and player ID are carried over verbatim
  into the shell. Do **not** rebuild the audio player, do not proxy the feed for playback, and do not
  touch download or analytics behavior — **stat continuity matters to the client.**
- **Only the wrapper is themed.** Buzzsprout exposes player colors through its own settings: set the
  player accent to raspberry `#BC3E8C` and the text to teal `#005C6D` **in the Buzzsprout dashboard**
  rather than overriding vendor CSS.
- **The embed is lazy too:** the Buzzsprout script loads on scroll-into-view
  (`IntersectionObserver`), with the shell header and a skeleton row reserving its height so nothing
  shifts.
- **If Buzzsprout fails,** the section degrades to a titled panel with a direct link out. Neither
  vendor failure can take down the page.

---

## 7. The Podcast Video Card (extended)

Four required parts: **thumbnail, title, publish date, watch action.**
**No card loads a YouTube iframe on page load.**

### 7.1 Anatomy

| Part | Specification |
|---|---|
| **Thumbnail** | 16:9, fixed ratio box so rows never jag. Cached YouTube still; teal tile + mark at 12% when missing. |
| **Play affordance** | 58px raspberry circle, centered, `0 8px 22px rgba(0,0,0,.28)` shadow. **52px on mobile.** A real `<button>`, not an image overlay. |
| **Duration badge** | Bottom-right, 12px / 700 white on `rgba(0,72,85,.78)`, 6px radius. Omitted when the feed has no duration. |
| **Eyebrow** | 11px / 700 / 0.12em uppercase raspberry — "Episode 14". Falls back to "Video". |
| **Title** | 22px / 700 / −0.02em teal, **3-line clamp**. The card's primary content. |
| **Publish date** | 14px / 1.6 gray, long format. Always present — it is the only recency signal on the card. |
| **Watch action** | Footer row, 11px uppercase with a raspberry arrow; 44px tap height; pinned with `margin-top: auto`. |
| **YouTube tile** | 44px paper square at the right of the footer — the escape hatch to the channel for people who prefer to watch there. |

Card shell: white, 16px radius, 1px `#e3e9eb`, shadow-1, flex column; body padding 26/28/28px.

### 7.2 Play behavior — the facade pattern

1. **Initial paint:** a cached thumbnail `<img>` plus a 58px raspberry play button. **Zero
   third-party JS, zero iframes, no YouTube cookies set.**
2. **Hover / focus:** the play button scales to 1.08 and the thumbnail dims 8%. **Nothing loads yet.**
3. **On click:** the facade is replaced in place by
   `<iframe src="youtube-nocookie.com/embed/{id}?autoplay=1&rel=0">` at the same 16:9 box, **so no
   layout shift.**
4. **One at a time:** mounting a player unmounts any other. Six mounted iframes is exactly the
   problem this solves.
5. **Second click on the same card** does nothing — the player owns its own controls from that point.
6. **No-JS fallback:** the whole card is wrapped in an `<a>` to the YouTube watch URL; JS upgrades it
   to the in-place player. **The card always works.**
7. **Prefetch:** `<link rel="preconnect">` to the YouTube origins fires on **first hover**, so the
   perceived start is fast without the cost.
8. **Privacy:** `youtube-nocookie.com` and no YouTube request before an explicit click — which also
   keeps the page clean under a cookie-consent policy.
9. **Mounted player takes focus** so keyboard users land in the video; Escape returns focus to the
   card's play button.
10. **Never autoplay on mobile** beyond the explicit click — data cost matters on a school network.

### 7.3 Long-content & empty states

- **Long title** — 22px / 700, 3-line clamp. Episode titles in this feed run long, so three lines,
  not two. Full title stays in the link's accessible name.
- **Footer pinned** with `margin-top: auto`, so Watch and the YouTube tile align across a row
  regardless of title length.
- **No duration** (the feed sometimes omits it) → the badge is omitted, not zeroed.
- **Missing thumbnail** → teal tile with the mark at 12%. Never a stretched upscale.
- **No episode number** → the raspberry eyebrow falls back to "Video".
- **Empty feed / API failure** → the section renders its header plus one paper panel linking to the
  YouTube channel. **Never an empty grid, never a broken embed.**
- **Trailing row** of one or two cards stays left-aligned at column width.
- **Pagination:** six on first paint; **Load more episodes** appends the next six, retains focus,
  announces the count.

---

## 8. Playlist-driven automation (future support)

The card is built so the grid can be fed by a playlist without any design change.

- **Recommended source:** an `fs_video` CPT (title, YouTube ID, publish date, duration, cached
  thumbnail) populated by a **nightly WP-Cron pull from the YouTube Data API** for the channel's
  uploads playlist. The client can also add one manually.
- **Playlist-scoped:** the pull takes a **playlist ID** as a setting, not a hardcoded channel — so
  "Conversations on Learning" can move to its own playlist, or a second playlist (e.g. shorts,
  a series) can drive a second grid using the same card part, with no template work.
- **Why cached, not live:** a live API call on every page load is a quota risk and a slow first
  paint. **Cache the JSON for 12 hours** and sideload thumbnails into the media library on first
  sight.
- **Feed unavailable** → the last cached set is served. Never an empty grid.
- **Client workflow:** publish on YouTube → appears within 24 hours, or hit **Refresh podcast feed**
  in the admin bar for immediate sync. No page edit, no card placement.
- **Manual override:** a `menu_order` field lets a specific episode be pinned first without
  disabling the automation.
- **Audio/video pairing (future):** if every episode exists in both formats, a `youtube_id` field on
  the Buzzsprout episode record would let one card carry both Listen and Watch actions, replacing
  sections 04 and 05 with one. Flagged as a conversation, not built.

---

## 9. Responsive behavior

Card-grid breakpoints match the Team and Impact Stories grids exactly, so all three behave the same
way: **1240 / 1023 / 900 / 767**.

| Breakpoint | Video grid | Notes |
|---|---|---|
| **Desktop ≥1024** | **3-up**, 32px gap | Latest 1fr + 520px · subscribe 4-up · card title 22px |
| **Tablet 768–1023** | **2-up**, 24px gap | Hamburger nav from 1023 · subscribe 2-up · latest stacks |
| **Mobile <768** | **1-up**, 20px gap | Subscribe 1-up · card padding 22/20px · play button 52px |

### ≤1240px

Display 60px / H1 46px; hero slab static with `margin-top: −45px`, 40px padding; latest-episode
panel collapses to one column with the art at 16:9 and copy padding 44/44/48px; label rails and
two-column headers collapse to one column.

### ≤1023px

Primary nav + Menu label hide; video grid 2-up; subscribe tiles 2-up; CTA split one column; footer
2-up.

### ≤767px

- Gutters 20px; section padding 64px; header CTA hides.
- **Hero:** media 4:5; the teal slab drops its −56px offset and sits flush beneath, full-bleed to the
  20px gutter. The two hero CTAs stack full-width.
- **Type:** H1 34px / H2 36px / card title 20px / date 13px — the DS v1 mobile step, no new sizes.
- **Subscribe rail:** 4-up → 2-up → 1-up; each tile stays a 44px-plus tap target.
- **Latest episode** stacks: cover art 16:9 on top, teal copy panel with the Buzzsprout player below
  at 28px padding.
- **Buzzsprout list rows** stack to two lines: play button and meta on the first, duration beneath.
  The vendor's own responsive behavior is left alone inside the shell.
- **Video cards** 1-up; the 16:9 thumbnail is unchanged at every size, so one cached still serves
  all. Play button 52px (still well over the 44px minimum).
- **Load more** full-width 46px; focus retained after append, count announced via `aria-live`.

---

## 10. Spacing specification

| Measure | Value |
|---|---|
| Container / gutter | 1400 / 24px |
| Section padding, light ground | 112px |
| Hero slab offset / width | bottom −56px, left 24px / 700px |
| Subscribe rail label / tiles | 264px + 4 cols, 16px gap |
| Subscribe tile padding | 22px 24px |
| Latest: copy / art | 1fr / 520px |
| Latest copy padding | 60px 64px 64px |
| Buzzsprout shell header | 24px 28px |
| Buzzsprout row padding | 22px 28px |
| Buzzsprout row columns | 46px + 1fr + auto, 22px gap |
| Video grid gap (desktop / tablet / mobile) | 32 / 24 / 20px |
| Video card padding | 26px 28px 28px |
| Eyebrow → title → date | 10 / 12px |
| Card footer padding-top | 18px |
| Play button | 58px desktop / 52px mobile |
| Grid → Load more | 56px |
| Mobile section padding | 64px |
| Anchor scroll-margin | 106px |

---

## 11. Image guidance

- **Video thumbnail 16:9** — pull `maxresdefault.jpg` (1280×720) from the feed and **cache it locally**
  into the media library. **Never hotlink `i.ytimg.com`:** it is a third-party request on first
  paint, which is the thing this pattern exists to avoid.
- **Fallback chain:** cached still → `hqdefault.jpg` → teal tile with the mark at 12%. Never a
  stretched upscale.
- **Cover art** for the latest-episode panel: 1:1, min 1400×1400 — the same file the podcast uses on
  Apple and Spotify, so it stays recognizable. **This is the highest-value missing asset on the
  page.**
- **Hero** 21:9, min 2400×1030. A recording moment or a leader mid-conversation, focal interest right
  of center so the teal slab covers no face.
- **Do not** add play-button graphics to the thumbnail image itself — the button is a real control,
  not part of the picture.
- WebP with JPEG fallback, width/height on every image, lazy below the fold. Alt text = **"Video
  thumbnail: {Episode title}"**.
- No cover art, thumbnails, or hero photograph were supplied; every card in the mockup shows the
  fallback tile.

---

## 12. Interaction states

| Element | Default | Hover | Focus / active |
|---|---|---|---|
| **Play button** | 58px raspberry circle | `brightness(.88)` + `scale(1.08)` at 240ms; thumbnail dims 8% | Active `scale(.96)`; cerulean ring |
| **Video card** | Shadow-1 | Shadow-2 + 2px rise, 240ms | Container; the play button and the Watch link are the two real controls, both pointing at the same action |
| **Watch link** | 11px uppercase teal, 12px gap | Label → raspberry, gap 12 → 18px, 240ms | Accessible name "Watch: {Title}" |
| **YouTube tile** | 44px paper square | Ground → raspberry, glyph → white, 160ms | Ring |
| **Subscribe tile** | White, 1px `#e3e9eb` | Border → raspberry, shadow-2, 2px rise, 240ms | Ring |
| **Buzzsprout row** | White | Ground → `#f7f9fa`, 160ms; play button inverts to raspberry fill | Rows keep hairlines |
| **Hero primary** | Raspberry pill | `brightness(.92)`, 160ms | Ring |
| **Hero ghost** | 1px `rgba(255,255,255,.4)` | Fill `rgba(255,255,255,.12)`, border white | Ring |
| **Closing primary** | **Coral** pill | `brightness(.92)` | Ring |
| **Load more** | Secondary pill | Border → teal, ground → paper | Focus retained after append |
| **Active nav item** | Podcast: 700, 2px **coral** underline at 8px offset | — | `aria-current="page"` |

**Focus-visible, everything:** 3px `#0A96CB` ring at 2px offset — cerulean, not raspberry, so the
focus ring stays consistent site-wide. Never removed.

**Entrances:** fade + 28px rise, 700ms `cubic-bezier(.2,.7,.2,1)`, once. Intersection Observer at
`threshold: 0.08`, `rootMargin: 0 0 -6% 0`, with a **900ms safety timeout** restoring opacity.
Disabled under `prefers-reduced-motion`, which **also suppresses the play-button scale.**

---

## 13. Developer implementation notes (WordPress)

- **Page template** `page-podcast.php`. Hero copy, intro, and the two section headings are page
  fields so the client can edit words without touching structure.
- **Video source:** `fs_video` CPT (title, YouTube ID, publish date, duration, cached thumbnail) fed
  by a nightly WP-Cron pull against a configurable **playlist ID** (see §8).
- **One card part** `template-parts/cards/video-card.php`, taking a normalized array. The same part
  serves the Blog page's video cards later.
- **Facade JS:** ~30 lines, no dependency. Delegated click listener on the grid, `replaceChild` the
  facade with the iframe, single-instance guard, `preconnect` on first `pointerenter`.
- **Buzzsprout:** keep the existing embed snippet in an ACF code field or a template partial so the
  ID is never hardcoded in two places. Lazy-init on intersection. Player colors set in the Buzzsprout
  dashboard.
- **Load more:** `posts_per_page = 6`, REST append, `aria-live="polite"`, no layout shift.
- **Editor guardrails:** no layout fields, no color fields, no per-card accent. The Elementor version
  of this page is **retired, not migrated.**
- **SEO:** emit `VideoObject` and `PodcastSeries` schema; keep the existing page URL and meta
  description.
- **Anchors:** `#listen` and `#watch` with `scroll-margin-top: 106px`.
- **A11y:** one `h1` ("Podcast"); section headings `h2`, card titles `h3`; the play control is a real
  `<button>` with a descriptive name; duration badges are `aria-hidden` because the duration is
  already in the accessible name; the audio progress track is a real `role="progressbar"`.
- **Mockup caveats:** hero and CTA media are CSS background images; the Buzzsprout rows and the
  mounted-player panel are styled stand-ins for the vendor markup. Ship real `<img>` and the real
  embeds.

---

## 14. Assumptions & missing assets

1. **Page copy is verbatim** from the live page — H1 "Podcast", the intro paragraph, and "Prefer to
   watch our podcasts" (title-cased). "Conversations on Learning" comes from the page title tag and
   is used as the show name in the eyebrow.
2. **Episode data is invented.** No episode list, titles, dates, durations, or YouTube IDs were
   supplied; the episodes and video cards are placeholders demonstrating length limits and empty
   states.
3. **Section headings are the designer's** — "Every episode, in one place.", the subscribe rail, and
   the closing CTA. The doc says keep the page as is, so treat these as proposals to approve or cut.
4. **The contact form is removed** from this page in favor of a CTA. A deliberate call, easily
   reversed.
5. **Subscribe destinations needed** — Apple, Spotify, and the Buzzsprout public URL are
   placeholders. Need the real links, and confirmation of which platforms the show is actually on.
6. **Buzzsprout player ID and API access** not supplied; the existing embed is assumed carried over
   as-is.
7. **YouTube API key** needed for the nightly pull. Without one, the CPT still works — the client
   adds videos by pasting a URL.
8. **No cover art or thumbnails.** Cover art at 1400×1400 anchors the latest-episode panel.
9. **Raspberry is scoped to this page and Blog** per DS v1. The closing primary CTA stays coral.
10. **Audio and video are not paired.** The same conversation exists in both sections without being
    cross-linked. If every episode has both, a single card with Listen and Watch actions would be
    better — and it would replace both sections with one.
