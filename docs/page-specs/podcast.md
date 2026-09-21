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
