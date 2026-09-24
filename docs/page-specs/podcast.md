# Page Spec: Podcast

Status: **approved** — ready for implementation. Written from the task's own explicit
requirements plus the established page structure, since no separate design spec was
available. Source of truth for `page-podcast.php`.

## 1. Page Identity

- **Page ID:** not provided in the task (unlike About/Services/Team/Impact Stories,
  which each specified a numeric ID). **Not a blocker:** `page-podcast.php` applies via
  WordPress's native `page-{slug}.php` hierarchy — the same "preserve via mechanism, not
  by referencing the ID" approach used throughout — so no code path needs the actual ID.
  Worth recording here once known, for consistency with the other page specs and for
  audit-trail purposes.
- **Slug / canonical URL:** `/podcast/`
- **Template file:** `page-podcast.php`

## 2. Sections (in order)

1. **Hero** — page title, intro copy, primary CTA button. Data-driven, not invented
   copy.
2. **Episodes grid** — see §3.
3. **CTA Banner** — closing call-to-action, Site Settings-driven.

## 3. Dynamic Audio/Video Content

**No `fs_podcast` CPT exists yet** (see `docs/architecture.md` §3.5 — explicitly
deferred; YouTube playlist automation is an out-of-scope stretch goal per the project
plan). "Dynamic" here means the grid is loop-driven over a defined episode list in
`page-podcast.php`, not hand-duplicated markup per episode — ready to swap for a real
query once a CPT/feed exists, without changing `podcast-card.php` itself.

Episode data in the template is **clearly-marked placeholder** (real Buzzsprout embed
codes and YouTube video IDs are not available to generate) — same honest-placeholder
approach already used for the Home page's Statistics/Podcast sections. Replace with real
values before launch.

Each episode renders via the existing `podcast-card.php` component, extended with one
new prop:

- **`embed_html`** (existing) — a Buzzsprout `<iframe>` embed, rendered immediately.
  Lightweight (a small audio player widget), so no lazy-loading is needed — this
  preserves existing Buzzsprout functionality unchanged, per requirement 1.
- **`youtube_id`** (new) — a bare YouTube video ID. Renders a lazy-load facade (thumbnail
  image + play button) instead of an iframe; the real iframe is only created in
  JavaScript after a genuine click. See §4.

## 4. Deferred YouTube Embeds (Performance)

- Initial render: `<img loading="lazy">` using YouTube's predictable thumbnail URL
  (`https://i.ytimg.com/vi/{id}/hqdefault.jpg`) plus a real `<button>` element (not
  `div[role=button]`, for native keyboard support) layered as a play-button facade.
- On click: `assets/js/components/podcast-video.js` replaces the facade with a
  `youtube-nocookie.com` iframe (`autoplay=1`, appropriate since it's a direct result of
  the user's own click, not page load) inside the same container.
- **Zero layout shift:** the facade and the eventual iframe both live inside one
  `aspect-ratio: 16/9` container and fill it identically — the swap changes zero pixels
  of page layout.
- No automated YouTube playlist syncing — out of scope per the task and the project
  plan.

## 5. Responsive & Edge Cases

- Grid uses the existing `card-grid.css` (`auto-fit`/`minmax`), consistent with every
  other page.
- Missing embed (neither `embed_html` nor `youtube_id` provided): card still renders
  with title/description/CTA, no broken embed block.
- Empty episode list: friendly message, same pattern as Services/Team/Impact Stories.

## 6. Container Widths

All sections use the uniform wide container (`.fs-container.fs-container--wide`,
1200px), matching the established pattern.

## 9. Rebuild Against the Approved Mockup (2026 rebrand)

The sections described earlier in this document are the **pre-rebrand** build (hero →
"Episodes" grid over a hardcoded placeholder array → generic CTA banner). That array held
`TODO:` strings for episode titles, Buzzsprout embed codes and YouTube IDs, which both
failed `AGENTS.md` §3 ("no TODOs left in committed code") and rendered visible TODO text
on the front end. This rebuild replaces it with two real data sources and removes the
placeholder array entirely.

As with Services, **no `.dc` file exists for this page** — the source of truth is the
deployed mockup's `/#/podcast` route, with copy taken verbatim.

### 9.1 Sections (in order) and component mapping

| # | Section | Component | Data source |
|---|---------|-----------|-------------|
| 1 | Hero | `hero.php` (**extended**) | Literal mockup copy. Eyebrow is the configured show name. Two CTAs jump to `#listen` / `#watch`. |
| 2 | Subscribe strip | `podcast-subscribe.php` (**new**) | Site Settings → Podcast (Apple/Spotify/Buzzsprout) plus the existing `youtube_url`. |
| 3 | "Every episode, in one place." | `podcast-player.php` (**new**) | The Buzzsprout hosted player. |
| 4 | "Prefer to watch our podcasts?" | `podcast-card.php` (unchanged) | `focused_schools_get_podcast_playlist()` (fields `video_id`, `title`, `thumbnail_url`, `published_at`). |
| 5 | Closing CTA | `cta-banner.php` (unchanged) | Links to the Buzzsprout show when configured, else `/contact/`. |

### 9.2 Component changes

**`podcast-subscribe.php` (new)** — the platform link row. Each link renders only when it
has both a label and a URL, and the whole strip is omitted when none do, so an unconfigured
site shows nothing rather than dead links.

**`podcast-player.php` (new)** — embeds the Buzzsprout hosted player. It takes the
**numeric podcast ID** and builds the iframe `src` itself; raw embed HTML is never stored
in the database or echoed. The ID is stripped to digits (`preg_replace('/[^0-9]/', ...)`)
before use.

**`hero.php` (extended, additive)** — gained optional `cta2_label` / `cta2_url`, rendered
in a new `.fs-hero__actions` row. Verified that Services, Team and Impact Stories still
render exactly one hero button each.

### 9.3 Content model: no new CPT

The mockup shows per-episode cards with numbers, dates and durations. Producing those from
WordPress would mean an episode CPT, which `AGENTS.md` forbids without approval, and would
duplicate data Buzzsprout already owns. Instead **Buzzsprout remains the audio source of
record** (also required by `AGENTS.md`) and its own player renders the episode list, while
video episodes come from the existing feature-flagged YouTube helper. Five settings were
added to Site Settings → Podcast: show name, Buzzsprout ID, Buzzsprout show URL, Apple
Podcasts URL and Spotify URL.

**Consequence worth knowing:** the styled per-episode rows and video cards in the mockup
(episode number · date · duration, "Load more episodes") are not reproduced one-for-one.
The audio list is Buzzsprout's player UI, not our markup, so it carries Buzzsprout's
styling. Matching the mockup exactly would require a Buzzsprout feed/API integration and
an episode content model — a separate, approvable piece of work.

### 9.4 QA performed

`php -l` and PHPCS clean on every touched file. Rendered at 1280px: all five sections
present, 4 subscribe links, 2 section headers, hero actions row with 2 buttons, zero
occurrences of "TODO" in the output (was previously visible on the page). Player verified
by temporarily setting a test Buzzsprout ID — the iframe built the expected
`buzzsprout.com/{id}?client_source=large_player&iframe=true` src — and the ID sanitizer was
checked with a script-injection attempt (`12ab34"><script>…`), which was reduced to digits
with nothing leaked into the markup. The test ID was then cleared, so both empty states
currently show, which is correct for an unconfigured site. All nine site routes still
return 200.

**Not verified:** no real Buzzsprout ID or enabled YouTube playlist exists in this
environment, so neither populated section has been seen with real episodes; and screenshot
capture was unavailable (browser extension timeouts), so tablet/mobile widths were not
visually reviewed.

## 10. Exact-Fidelity Pass Against `Focused Schools Podcast.dc.html`

§9 above states that no `.dc` file exists for this page. One now does, so this pass rebuilt
the page against it. The source's sections in order are: hero, subscribe rail, latest
episode, Buzzsprout list, video grid — and **no closing CTA**.

**Changed to match the source**

- **Hero** (`hero.php`, additive props): `accent: raspberry` gives the 26×3px raspberry
  rule beside a white eyebrow and a raspberry primary button; `cta2_style: ghost` gives the
  transparent white-outlined secondary; `heading_size` (56px) and `slab_width` (700px) are
  per-caller like Team/Services. Defaults leave every other page unchanged (verified:
  Services still renders one `fs-btn--primary`). `button.php` gained `raspberry` and
  `ghost` styles. Photo corrected to `retreat-1.jpg` (was `student-video.jpg`).
- **Subscribe**: was a bordered strip; now the source's 264px label rail beside four cards
  (name, qualifier, raspberry arrow, raspberry hover border, lift). 4 / 2 / 1 columns at
  1023 / 767.
- **Listen**: paper ground, 68/60/36px heading, "Open in Buzzsprout" outline button, and the
  Buzzsprout player inside the source's white shell with a header row (mark tile, show name,
  "Hosted on Buzzsprout"). The player is still `podcast-player.php` (numeric ID → iframe
  src, digits-only sanitised); the source's "Vendor player — unchanged" and "in production
  the vendor player renders here" labels are mockup annotations and are not shipped.
- **Watch**: two-column header (raspberry-rule eyebrow, mixed-weight "Prefer to watch **our
  podcasts?**", lead + outline button), fixed 3 / 2 / 1 column grid at 32 / 24 / 20px, and
  "Load more episodes" after six. `podcast-card.php` was rewritten as the source's video
  card (16:9 thumbnail, 58px raspberry play, 22px 3-line-clamped title, date, footer pinned
  with `margin-top:auto`, Watch + YouTube tile). It no longer extends `.fs-card` (its 32px
  padding and coral hover don't match) and dropped the unused Buzzsprout-embed/episode props
  — this page was its only caller.
- **Empty states**: header plus a single paper panel with a channel link — never an empty grid.
- **Removed the closing CTA banner** ("Subscribe wherever you listen."): it is not in the
  source, which drops the old page's contact form and ends at the video grid.

**Progressive enhancement / a11y**: "Watch episode" and the YouTube tile are real links to
the video, so cards work without JS; `podcast-video.js` upgrades the Watch link and the
thumbnail button to the in-place `youtube-nocookie` player (`autoplay=1&rel=0`) inside the
same 16:9 box and moves focus to the iframe. No iframe loads before a click (verified: 0
iframes on load). One `h1`; sections `h2`; card titles `h3`.

**Latest episode panel (approved data source: Buzzsprout RSS).** The teal split panel
(copy + single-episode player left, cover art right) is built as
`template-parts/components/podcast-latest.php` / `assets/css/components/podcast-latest.css`.
No episode CPT (`AGENTS.md`); data comes from the show's public feed,
`https://feeds.buzzsprout.com/{podcast_id}.rss`, using the ID already in Site Settings.

- `Modules\Podcast\Buzzsprout_Feed` (plugin) reads the newest `<item>`: title, summary
  (tags stripped, 48 words), `itunes:duration` (normalised to `m:ss` / `h:mm:ss`),
  `itunes:episode`, publish date, episode ID (from the `Buzzsprout-<id>` GUID) and cover art
  (`itunes:image`, else the show's).
- Same rules as the YouTube helper: the theme-facing read
  `FocusedSchoolsCore\get_podcast_latest_episode()` is cache-only and never makes an HTTP
  request on a page load. A 3-hour transient is the fast path; once it expires the
  last-known-good option (`autoload=no`) keeps serving while one wp-cron event refreshes in
  the background. A failed fetch never overwrites it. "Refresh Now" on the Podcast (YouTube)
  admin page also refreshes the feed. A cached payload for a different podcast ID is ignored.
- The panel is hidden (not an empty shell) until the first fetch succeeds or when no
  Buzzsprout ID is set. The player is Buzzsprout's own iframe built from the two numeric IDs
  (`small_player`, 200px) inside the design's translucent wrapper; the design's mock
  play button and progress bar are annotations of that vendor player and are not shipped.
  With no cover art the white mark on `#004855` is shown, as in the design.
- Layout from the source: `1fr / 520px`, copy padding `60/64` -> `44` (<=1240) -> `28/24`
  (<=767), cover art stacks on top at 16:9, 46 / 60 / 36px title.
- Cover art hotlinks the Buzzsprout image URL from the feed (not copied into the media
  library), like the YouTube thumbnails.

**Known differences from the source**
- **Video metadata:** the cached playlist helper returns title, ID, thumbnail and publish
  date only, so the source's per-video eyebrow and duration badge are omitted (both props
  exist on the card and render when supplied). The source also says to cache thumbnails
  into the media library instead of hotlinking `i.ytimg.com`; the card uses the helper's
  thumbnail URL as before.
- **Breakpoints:** the source's mobile step is 767px. Page-specific CSS uses 767/1023/1240
  exactly; the *shared* `hero.css` steps at 720px (Home/About), so the hero's H1/slab
  mobile treatment starts 47px later than the source's (same inter-file inconsistency
  documented in `team.md` §9).

**Verified**: `php -l` and PHPCS (project ruleset) clean on every touched PHP file. Rendered
with temporary sample data (8 videos, test Buzzsprout ID and subscribe URLs — restored
afterwards): 11 widths 320–1440px, zero overflow; grid columns 1/2/3, subscribe 1/2/4, rail
2-col ≥1241, H1 56px/slab 700px ≥1241, section H2 68/60/36px; six cards visible with two
hidden until "Load more"; Watch link and thumbnail both mount the player; keyboard order
reaches the hero CTAs then the subscribe cards. With no data configured the page shows the
empty states, no "TODO" text, no closing banner. Eight-page overflow sweep after the change
was clean. **Not verified:** a real Buzzsprout player (the test ID 404s inside Buzzsprout's
own iframe) or a populated YouTube playlist — neither exists in this environment. The feed parser was checked against a fixture RSS (title/summary/duration in seconds and h:mm:ss/episode/date/cover art, malformed XML rejected) and the panel rendered from seeded cache at 375–1440px with no overflow; a live fetch of the real Buzzsprout feed and the wp-cron refresh were not run.
