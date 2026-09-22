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
| 4 | "Prefer to watch our podcasts?" | `podcast-card.php` (unchanged) | `FocusedSchoolsCore\get_podcast_youtube_videos()`. |
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
