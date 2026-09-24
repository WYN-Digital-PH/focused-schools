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

## 5. Known Limitation: Excerpts on Elementor-Authored Posts

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

## 8. Re-Verification Pass (Legacy Blog Compatibility Audit)

A later task re-ran this entire audit end-to-end with live browser automation (not
available in the original pass), for two reasons: to confirm nothing here regressed after
extensive unrelated work this session touched the *shared* `site-header.php`/
`site-footer.php`/`style.css` that every one of these templates depends on via
`get_header()`/`get_footer()`, and to close the "not verified" gaps the original pass
left open (images, video, featured images — the original 4 test fixtures had none).

**Regression check**: all 4 fixture posts (§6) plus `/blog/`, `/blog/page/2/`, and
`/category/uncategorized/` re-tested live. Zero change in behavior — Elementor posts
still bail to bare `the_content()` (confirmed via DOM: zero `.fs-post-single__title`/
`__meta`/`.nav-links`/`__related` present), native/Gutenberg posts still get full
chrome, pagination and archive titles still correct, zero console errors, zero horizontal
overflow at any width tested (320–1440px). The nav-link CSS fix and `.screen-reader-text`
utility from §7 are both still intact.

**Closed the images/video/featured-image gap**: reused two of the three real media
library attachments already present in this environment (IDs 11/13 — not new uploads,
`wp-content/uploads/` untouched) to add a featured image to `standard-native-post` and an
inline `<img>` + a real YouTube oEmbed URL to `new-gutenberg-post`'s content. Verified
live: featured image renders full-width above the title (`the_post_thumbnail('large')`
path), inline Gutenberg image loads correctly, the oEmbed URL resolves to a real embedded
YouTube iframe (`the_content()`'s core oEmbed handling — no custom video code exists or
was needed), and `the_post_navigation()`'s prev/next correctly links to the
chronologically-adjacent post. Screenshots reviewed at both desktop and mobile (375px) —
clean stacking, zero overflow, footer/header render correctly beneath the new media.

**Fixed a documentation-integrity issue found in passing**: `docs/06-url-seo-preservation.md`
had binary/corrupted content in its "Risks" section (not a tool-read error — confirmed via
`git log`, the one existing commit for that file already contains the same corruption, so
there was no clean version to recover). Truncated the unreadable tail and left an
editorial note rather than fabricating replacement risk-list content.

**Still not verifiable in this environment** (unchanged from §6/§4): Elementor Pro's
Theme Builder (a site-wide override mechanism that could take precedence over these
templates on the real site — architecturally separate from the per-post check this task
implements) and Yoast SEO's actual canonical/meta/schema output, since neither Elementor
nor Yoast is installed locally. The structural compatibility argument (standard Loop,
`wp_head()`, `title-tag` support, nothing overriding Yoast's own hooks) stands, but is
unverified against the real plugins. **Both must be checked directly against
staging/production before this is considered fully verified.**
