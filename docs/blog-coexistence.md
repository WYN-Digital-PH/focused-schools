# Blog / Elementor Coexistence Rules

Status: **implemented and verified locally** (with real test fixtures — see §6), but
**not verified against real Elementor content or Elementor Pro's Theme Builder**, since
neither exists in this local environment. Verify against staging/production before
relying on this.

## 1. Known Constraints (as given)

- Blog Posts Page: ID 1191, slug `/blog/` (`page_for_posts`)
- Permalinks: `/%postname%/`
- 123 published Posts total; 29 contain Elementor layout data
- Elementor Pro currently supplies Single Post and Blog Archive templates (**Theme
  Builder** — see §4, a distinct mechanism from the per-post check this task specifies)
- Legacy content must **not** be bulk-migrated this sprint
- Elementor / Elementor Pro must **not** be deactivated

## 2. Template Hierarchy — which file governs which route

| Route | Template | Why this file, not another |
|---|---|---|
| `/blog/` (Page 1191, the Posts Page) | `home.php` | WordPress uses `home.php` — **not** `page-{slug}.php` — for whatever page is assigned via `page_for_posts`. Naming it `page-blog.php` would silently never apply. |
| Category / tag / date / author archives | `archive.php` | No single associated post/page to key off of; always native rendering. Previously fell back to bare `index.php` (no Elementor awareness, no pagination handling, and a real `<h1>`-per-post-in-a-loop accessibility bug — fixed by these new templates existing). |
| Search results (`/?s=`) | `search.php` | Core's search route. Always native — a results listing has no single post to key the coexistence check off. |
| Individual post (any post type using default routing) | `single.php` | Standard per-post Loop; the coexistence check applies to *this specific queried post*. |
| Individual `fs_impact_story` post | `single-fs_impact_story.php` (pre-existing, unrelated to this task) | More specific in the hierarchy than `single.php` — unaffected by this task's changes. |

## 3. The Coexistence Rule (per-post/page check)

Implemented exactly as specified:

```php
get_post_meta( $id, '_elementor_edit_mode', true ) === 'builder'
```

- **`home.php`**: checks **Page 1191 itself** (via `get_option( 'page_for_posts' )`). If
  Elementor-built, renders only that page's `the_content()` — Elementor's own saved
  output, untouched. Otherwise: native heading + a post-card grid over the main query +
  `the_posts_pagination()` (WordPress core — never hand-rolled, so pagination cannot be
  broken by this template).
- **`single.php`**: checks the **current queried post**. Same untouched-`the_content()`
  branch if Elementor-built. Otherwise: full native presentation — title, featured
  image, author/date meta, content, `wp_link_pages()`, `the_post_navigation()`, and a
  related-posts section (same category, excludes the current post, limit 3).
- **`archive.php`**: no single post/page to check — **always native**. Every post in an
  archive listing (home.php or archive.php) renders as a native excerpt card via the new
  `post-card.php` component, **regardless of that individual post's own Elementor
  status** — this is deliberate, not an oversight. See §5.

## 4. What This Task Does *Not* Cover — Elementor Pro's Theme Builder

"Elementor Pro currently supplies Single Post and Blog Archive templates" describes
Elementor Pro's **Theme Builder** — a separate, site-wide template-override system. A
Theme Builder condition (e.g. "apply this design to all Single Posts") can take over
rendering at a level above WordPress's normal template hierarchy, independent of any
individual post's `_elementor_edit_mode` value. **This is architecturally distinct from
the per-post check this task specifies, and this local environment has no Elementor
installed to inspect or test it.** Concretely: `home.php`/`archive.php`/`single.php`
existing does not guarantee they are the templates actually rendering the real site —
if a Theme Builder condition is active, it may take precedence. **This must be verified
directly against the real Elementor Pro configuration on staging/production** before
assuming these new files are the effective ones for `/blog/` or single posts there.

## 5. Excerpts on Elementor-Authored Posts — measured, and not a problem

**Updated September 24, 2026.** The concern below was written before the real export
was available. It has now been measured against the actual content, and it does not
apply to this site: **all 30 Elementor posts carry real text in `post_content`** —
minimum 428 characters, median 2,544. Auto-generated excerpts in listings therefore
work normally, and no Elementor post shows a thin or empty card. No manual excerpts
exist anywhere (0 of 124 posts), so every card excerpt is auto-generated, which is
fine at those content lengths.

The original note is kept below because the reasoning still holds for any site where
Elementor posts *do* have empty `post_content`.

### Original note

Elementor's real content lives in `_elementor_data` post meta as JSON, not in
`post_content` — for many Elementor-built posts, `post_content` is just a placeholder
(or near-empty). `get_the_excerpt()` (used by `post-card.php` in every archive listing)
auto-generates from `post_content` when no manual excerpt is set, so **some of the 29
Elementor-authored posts may show a thin or empty excerpt in `/blog/` or category/tag
archive listings** if their authors never set a manual excerpt. This was not "fixed"
because doing so would mean either (a) requiring a content-workflow change (setting
manual excerpts — not a code task), or (b) parsing Elementor's JSON layout data to
synthesize a text preview, which is real scope creep, fragile, and wasn't requested.
Flagging honestly rather than guessing at a fix.

## 6. Verification Matrix — tested locally with real fixtures

No Elementor-flagged posts existed in this environment before this task, so 4 local test
posts (and 6 filler posts for pagination) were created purely for QA — not part of the
committed code:

| Case | Post | Result |
|---|---|---|
| Recent Elementor post | `recent-elementor-post` (`_elementor_edit_mode = builder`) | ✅ Confirmed via live DOM check: zero native markup rendered (`.fs-post-single__title`, `__meta`, `.nav-links`, `.fs-post-single__related` all absent) — only `the_content()`'s raw output, wrapped in header/footer. |
| Older Elementor post | `older-elementor-post` (same meta, dated 2023) | Same code path as above (identical logic, not re-verified separately — the branch is date-independent by design). |
| Standard native post | `standard-native-post` (classic paragraphs, manual excerpt) | ✅ Full native rendering confirmed via screenshot: title, "By Admin on November 5, 2022", content, post navigation (fixed a real CSS bug found during this check — see §7), and a 3-item related-posts grid (shared category). |
| New Gutenberg post | `new-gutenberg-post` (block comments in content) | ✅ Renders correctly through the same native path — `the_content()` handles Gutenberg block markup natively, no special-casing needed. |

**Pagination**: confirmed end-to-end, not just asserted — 11 published posts (10
`posts_per_page`) correctly produced page 1 + page 2, `the_posts_pagination()` rendered
"1, 2, Next", and navigating directly to `/blog/page/2/` correctly resolved and showed
the 11th post.

**Category archive**: `/category/uncategorized/` confirmed rendering via `archive.php`,
with `get_the_archive_title()` correctly outputting "Category: Uncategorized".

**Yoast SEO / canonical / schema**: **not verified** — Yoast is not installed in this
local environment. Structural compatibility only: standard Loop, `wp_head()` (already
called in `header.php`), `title-tag` theme support (already registered) — nothing custom
that would fight Yoast's own hooks. Verify against staging/production, where Yoast is
actually active.

## 7. Bug Found and Fixed During This Task

While testing `single.php`'s post navigation, `the_post_navigation()`'s label/title spans
rendered run-together ("NEXTOlder Elementor Post") instead of stacked. Root cause: the
CSS applied `display: flex; flex-direction: column` to `.nav-previous`/`.nav-next`, but
the label/title spans are nested one level deeper — inside WP core's own `<a>` wrapper —
so flex-direction on the outer div never reached them. Fixed by moving to `display: block`
directly on the spans themselves, which doesn't depend on the exact DOM nesting. Verified
visually after the fix.

Separately, this task also revealed that `.screen-reader-text` had never been styled
anywhere in this theme (the skip-link's own independent CSS masked this — it doesn't rely
on that class for its hide/show behavior). Without it, `the_post_navigation()`'s
auto-generated `<h2 class="screen-reader-text">Post navigation</h2>` would have rendered
as a fully visible heading. Added the standard WP-core utility class to `style.css`
(global, not blog-specific) and confirmed live: present for screen readers, visually
1×1px/`position: absolute`.


---

## 8. Archive Redesign (September 24, 2026)

`/blog/`, the term archives and search results were rebuilt against the approved
design. **The coexistence rules above are unchanged** — this was a presentation change
only, and the constraints it had to hold are worth stating explicitly.

### What was not touched

- **The main query.** No `pre_get_posts`, no `posts_per_page` override, no
  `post__not_in`, no ordering change. Verified: the theme and the core plugin contain
  no `pre_get_posts` hook at all.
- **URLs.** `/%postname%/` unchanged, `/blog/page/N/` unchanged, term archives
  unchanged. Nothing rewrites or redirects.
- **`the_posts_pagination()`.** Still core's own output, styled but never
  hand-rolled, so page URLs cannot drift from what the query expects.
- **The Elementor branch in `home.php`.** Byte-for-byte the same check and the same
  untouched `the_content()` render.

### The one thing worth understanding

The design leads with a "Latest article" feature above the grid. That feature is
**the first result of the same main query**, presented differently — not a second
query, and not an exclusion from the first. It only appears when `! is_paged()`.

This matters because the obvious implementations are both wrong: a separate "latest
post" query plus `post__not_in` on the main one would change the post count per page
and desynchronise pagination, and excluding it via `pre_get_posts` would do the same.
Verified empirically rather than assumed — with 14 posts, page 1 renders 10 (1 feature
+ 9 cards) and page 2 renders 4, **zero overlap, 14 unique**.

### Search and category filtering

The design shows a search field and category chips. Both go through core's own
routing rather than filtering client-side:

- the search field posts `s` (plus `post_type=post`) to the site root — core's search
  route, rendered by the new `search.php`;
- each chip is a real `get_category_link()` URL, rendered by `archive.php`.

Client-side filtering was rejected deliberately: with 123 posts it could only ever
filter the ten already on the page, silently hiding the rest. Going through core means
every result is reachable, pagination keeps working, and the URLs stay shareable.

### Routes that remain Elementor-dependent

| Route | Dependency |
|---|---|
| `/blog/` **if Page 1191 is Elementor-built** | Renders that page's Elementor output; none of the new design applies. This is intentional and unchanged. |
| Any single post with `_elementor_edit_mode = builder` | Renders its own Elementor output; no native chrome. 29 posts are in this state. |
| **Anything governed by Elementor Pro's Theme Builder** | Still unverifiable locally — see §4. A Theme Builder condition on Single Post or Archive can override the template hierarchy entirely, which would mean these files are not the ones rendering on staging/production at all. **This remains the single biggest unknown and must be checked directly on staging.** |

Category/tag/date/author archives and search results are **never** Elementor-dependent:
there is no single post to key the check off, so they are always native.

### Verified on this pass (local, 14 posts, 4 fixtures)

| Check | Result |
|---|---|
| Recent Elementor post | Only its own output — no native title, meta, nav or related |
| Older Elementor post (2023) | Same |
| Native classic post | Full native render: title, author, date, content, nav, related |
| New Gutenberg post | Full native render; block markup output correctly |
| Elementor posts **in listings** | Rendered as native excerpt cards, as designed (§5) |
| Pagination | 10 + 4 = 14, no overlap |
| Category archive | Correct term, correct count, active chip marked `aria-current` |
| Search | Correct results; empty search shows the empty state |
| Featured image / author / date | All render on card, feature and single |
| Images | 10 in main, all with `alt`, 8 lazy |
| PHP errors | None across 13 routes |

### Still unverified

- **Yoast** — not installed locally. Canonical, meta and schema output unchecked. The
  templates add nothing that competes with Yoast's hooks (`wp_head()` in `header.php`,
  `title-tag` support), but that is structural reasoning, not a test.
- **Elementor Pro Theme Builder** — see above.
- **Tablet/mobile** — breakpoints written from the design's CSS; not viewed at width.


---

## 9. The Live Export — measured facts (September 24, 2026)

Taken from `focusedschools.WordPress.2026-09-24.xml`, 2.8 MB, before any import.

| Fact | Value |
|---|---|
| Items | 503 |
| Posts | 124 — **123 published**, 1 draft ("Skills for Life", 2024-02-12) |
| Attachments | 379, all on `www.focusedschools.com` (227 png, 83 jpg, 67 jpeg, 1 svg, 1 gif) |
| Elementor posts | 30 with `_elementor_edit_mode = builder` — **29 published + 1 draft**, which reconciles the audit's "29" |
| Elementor date span | 2023-01-24 → 2026-05-01 |
| Featured images | present on **all 124** |
| Yoast meta | present on **all 124** |
| Authors | 4 — `grapeadmin`, `broberts`, `focadmin`, `jgordon` |
| Live domain occurrences | 1,771 |

### Categories: there are none

The export contains **zero category and zero tag definitions**, and every one of the
124 posts sits in `Uncategorized` alone. This is a content fact, not an export fault.

It has one design consequence. The approved blog design shows a row of category
filter chips, which against this data would render "All | Uncategorized" — a filter
that filters nothing, reading as broken rather than as a feature. The chip row is
therefore hidden whenever fewer than two categories exist, and appears by itself once
posts are categorised. Nothing else about the design changes.

If the client wants the filters visible, the work is editorial — categorise the 123
posts — not a code change.
