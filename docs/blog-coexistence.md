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


---

## 10. Import Procedure — tested end to end (September 25, 2026)

The live export was imported into the local environment and verified. The
steps below are the ones that were actually run, in order, not a suggestion.

```bash
wp plugin install wordpress-importer --activate
wp import blog-posts.xml --authors=create
```

The import takes a while because it downloads all 379 attachments from the live
site; it is not stalled. Result locally: 123 published posts, 1 draft, 379
attachments, **0 featured images failed to resolve**.

### The domain rewrite, and the part that is easy to get wrong

After import, content still points at the live domain. The usual command fixes
`post_content` but **silently misses Elementor**, because Elementor stores its
layout as JSON with escaped forward slashes — `https:\/\/www.focusedschools.com\/`
— which never matches a plain search for `https://www.focusedschools.com`.

Measured on this import: after the standard replace, `post_content` was clean
(28 rows → 0) while **22 `_elementor_data` rows were still pointing at the live
site**. Two passes are required:

```bash
# 1. plain URLs
wp search-replace 'https://www.focusedschools.com' 'https://TARGET-DOMAIN'   --precise --recurse-objects --skip-columns=guid
wp search-replace 'http://www.focusedschools.com'  'https://TARGET-DOMAIN'   --precise --recurse-objects --skip-columns=guid

# 2. the JSON-escaped form Elementor stores — without this, 22 posts keep
#    loading their images from the live site
wp search-replace 'https:\/\/www.focusedschools.com' 'https:\/\/TARGET-DOMAIN'   --precise --recurse-objects --skip-columns=guid

wp elementor flush-css   # staging only; Elementor is not installed locally
```

`--precise` keeps serialized data valid; `--skip-columns=guid` leaves guids
alone, which is correct — guids are identifiers, not links.

Verified afterwards: **0 `_elementor_data` rows on the live domain, 30 rows
checked, 0 invalid JSON**. The only remaining `focusedschools.com` references are
`hello@focusedschools.com` email addresses in post copy, which must not be
rewritten.

### Verification matrix — real imported content, not fixtures

| Case | Post | Result |
|---|---|---|
| Recent Elementor post | `dear-teacher` (2026-05-01) | Own output only, no native chrome, no live-domain URLs |
| Older Elementor post | `a-strong-start-to-2023-…` (2023-01-24) | Same |
| Native post | `hello-world` | Full native chrome |
| Gutenberg post | `new-gutenberg-post` | Full native chrome |

Archive at full volume: `/blog/`, `/blog/page/2/` and `/blog/page/14/` all 200,
pagination 1…14, 10 images in main, all with `alt`, **zero live-domain image
URLs**. Eight routes swept, zero PHP errors.

### Still not provable from this environment

Elementor is not installed locally, so an Elementor post renders through the
coexistence branch as its raw `post_content`. **That proves the branch, not that
Elementor's own layout renders.** Together with the Theme Builder question in §4,
this is what has to be checked on staging after the import.


---

## 11. Single-Post Design (September 25, 2026)

`single.php` now renders the approved article design: breadcrumb, teal title
slab over the featured image with category badges and a byline, a sticky share
rail beside the prose, an author box, post navigation, and a three-up related
strip.

### The constraint, stated plainly

**The design applies to native and Gutenberg posts only. The 29 Elementor
posts are untouched and render exactly as they did before.**

That is not a shortcut — it is what the sprint's own rules require. A post
whose layout Elementor owns cannot be wrapped in this design without:

- duplicating the title, which Elementor layouts usually draw themselves;
- constraining full-width Elementor sections into a 1fr prose column;
- fighting Elementor Pro's Theme Builder if a condition is active on Single
  Post.

So the guard at the top of `single.php` is unchanged, byte for byte: an
Elementor post renders `the_content()` and nothing else — no breadcrumb, no
hero, no share rail, no author box, no related strip.

**Consequence to communicate to the client:** immediately after launch the blog
will look like two different sites — 94 posts in the new design, 29 in their
Elementor layouts. Closing that gap means rebuilding those 29 as blocks, which
is editorial work, deliberately out of scope this sprint, and explicitly
forbidden as a bulk conversion.

### Design decisions worth knowing

| Decision | Reason |
|---|---|
| Standfirst uses `get_the_excerpt()` | Auto-generates when no manual excerpt exists, so all 123 posts get a sensible opening; writing one overrides it |
| Reading time is computed from `post_content` | No stored field, nothing to maintain |
| Share buttons are plain links | No third-party widget, so no tracking script loads on an article and nothing is requested from a social network until a reader chooses to share |
| Author box hides itself when the bio is empty | An empty card with a name and an avatar is worse than no card |
| Prose is styled with element selectors | The body is whatever the editor wrote, classic or Gutenberg; it cannot be reached through classes this theme controls |
| Breadcrumb adds no structured data | Yoast prints its own breadcrumb schema; two sources could disagree about the hierarchy |

### Verified

| Case | Result |
|---|---|
| `dear-teacher` (real Elementor, 2026-05-01) | Own output only — no hero, share, breadcrumb or related |
| `a-strong-start-to-2023-…` (real Elementor, 2023-01-24) | Same |
| `hello-world` (native) | Full design |
| `new-gutenberg-post` (blocks) | Full design |
| 6 random real imported native posts | All 200, hero present, no PHP errors |
| Author box | Absent with no bio; appears once one is written |
| Share links | All four resolve, each with an accessible label |
| Headings | One `h1`, then `h2`/`h3` — no skips |
| `fs_impact_story` singles | Unaffected; they use their own template |

Twelve routes swept, zero PHP errors, phpcs clean on six files.


---

## 12. Contact Form Preservation (September 25, 2026)

The Contact page was rebuilt to the approved design. **No form system was
built, and no form behaviour was touched.** Elementor Pro still owns the form
on that page: its fields, its validation, its saved submissions, its
notification emails and its redirect to /thanks/.

Two paths, both untouched pass-throughs, and neither can alter the form:

1. **Page is Elementor-built** — `page-contact.php` renders Elementor's own
   output and none of the new design applies. Same guard as `single.php`.
2. **Otherwise** — the page's own content is rendered through
   `the_content()` into the form column, so whatever shortcode, widget or
   block holds the form runs exactly as it does today. The theme adds a
   styling wrapper around it and nothing else.

### Verified locally

Elementor is not installed here, so the form itself could not be exercised.
What *was* proved is the pass-through, using a stand-in shortcode that
returns a form:

| Check | Result |
|---|---|
| Form renders inside the design's form column | yes |
| `action` attribute preserved byte-for-byte | `/thanks/` unchanged |
| `required` attribute preserved | yes |
| Theme adds only a wrapper | yes |
| Empty state when no form content exists | shown, with the direct contact details beside it |
| `/thanks/` still resolves | 200 |
| Page IDs | podcast 43, contact 44, thanks 75 — unchanged |

### Not verifiable in this environment — must be done on staging

The task list asks for a real submission test. **None of the following could
be run here, because Elementor, Elementor Pro and any mail transport are all
absent from this local environment:**

1. required-field validation behaviour
2. actual submit
3. submission saved to Elementor's store
4. **notification email actually received**
5. redirect to /thanks/ firing
6. error behaviour on failure

**Mail delivery is the biggest unknown.** `docs/01-technical-audit.md` already
records that no SMTP plugin was found and that delivery relies on PHP mail,
which is unreliable and unobservable. Nothing in this sprint changed that, and
nothing in this sprint can prove an email arrives. A real end-to-end
submission on staging, with a confirmed received email, is still outstanding
and should not be assumed to work.

---

## 13. Podcast Page (September 25, 2026)

Rebuilt to the approved design. **Buzzsprout is preserved, not replaced.**

| Requirement | How it is met |
|---|---|
| Keep Buzzsprout active | Both players are Buzzsprout's own iframes; the new "embed shell" is a frame drawn around the vendor markup, which is untouched |
| No unnecessary YouTube iframes on load | **Zero YouTube iframes in the page source.** Verified with the page fully configured |
| YouTube automation not a launch dependency | With no playlist configured the Watch section shows its own message and a link to the channel; every other section works normally |
| YouTube automation out of scope | Not extended. The existing playlist client is unchanged |

Sections now render in the design's order: hero, subscribe rail, **latest
episode**, all episodes in the embed shell, watch, closing CTA. The first two
of those are new; the rest were already present.

The latest-episode panel is optional — with no episode id set it simply does
not appear, and the full player below still lists every episode, so the page
never depends on it. Three optional Site Settings fields drive it.

### One functional gap to decide on

The Watch section has **no manual video source**. Videos come only from the
YouTube playlist integration, so unless that is configured on staging the
section will show its empty state rather than the videos the live page
currently displays.

Adding a manual video list would be new scope and sits next to the YouTube
automation this task was told to leave alone, so it was not built. The
options are: configure the playlist integration, or approve a small manual
video source. **This needs a decision before launch** if the live page's
videos are expected to carry over.


---

## 14. YouTube Playlist Integration (September 25, 2026)

The feature-flagged module already existed from an earlier sprint and met the
requirements; this pass configured it against the real playlist, tested every
named case, and fixed one edge case the testing exposed.

**Buzzsprout was not touched.** Audio and video are independent: the podcast
page renders Buzzsprout's own iframes for listening and this module's data for
watching.

### How it meets each requirement

| Requirement | Where |
|---|---|
| Playlist ID configurable by admin | Focused Schools → Podcast (YouTube) |
| API key from environment/wp-config, never committed | `FS_YOUTUBE_API_KEY` constant. `wp-config.php` is gitignored (`.gitignore:74`) and no key value appears in any commit |
| WordPress HTTP API | `wp_remote_get()` in `class-youtube-client.php` |
| Normalized fields | `video_id`, `title`, `thumbnail_url`, `publish_date` |
| Cache ~6 hours | Transient, `cache_duration` default 21600 |
| Last-known-good on failure | Persistent option, never overwritten by a failed fetch |
| Admin-only manual refresh, nonce + capability | `admin_post` handler, `manage_options` + `wp_verify_nonce` |
| Theme consumes, plugin renders nothing | `FocusedSchoolsCore\get_podcast_youtube_videos()`; all markup lives in the theme |
| No API request on page view | Helper is cache-only and never fetches |
| Feature flag | `enabled` checkbox; off returns an empty list |

### Test results

Live fetch against playlist `PLntAE9m90gzDHfD8VebNCbSgIlRPd19Ya`: **13 videos,
all four fields populated, zero incomplete records**, thumbnails from
`i.ytimg.com`. The page renders 13 cards with **zero YouTube iframes in the
source** — thumbnails only, players load on click.

**No API request on page view**, proved rather than asserted: a
`pre_http_request` probe logging every outbound call recorded **nothing across
three page views**, while a manual refresh through the same probe recorded
exactly one call to googleapis.com. The probe was verified working by that
second observation, so the zero is meaningful.

Failure handling, each forced through a mocked HTTP layer:

| Case | Result | Last-known-good |
|---|---|---|
| Bad API key (403) | WP_Error | preserved |
| Quota exceeded (403) | WP_Error | preserved |
| Server error (500) | WP_Error | preserved |
| Network failure | WP_Error | preserved |
| Malformed JSON | WP_Error | preserved |
| Empty playlist (200, `items: []`) | WP_Error | preserved — **see below** |
| Stale cache (transient deleted) | serves last-known-good | 13 videos |
| Feature flag off / on | 0 / 13 videos | — |

Security, with `wp_die` converted to an exception so each guard is observable:

| Attempt | Result |
|---|---|
| Refresh as non-admin | blocked on capability |
| Clear as non-admin | blocked on capability |
| Refresh with missing nonce | blocked |
| Refresh with wrong nonce | blocked |
| Clear with wrong nonce | blocked |
| **Clear using a refresh nonce** | **blocked** — actions do not share a nonce |

### The edge case testing found

A successful response containing an empty list originally **overwrote
last-known-good with nothing**, emptying the published grid.

YouTube answers with an empty list for a playlist that has been made private,
or whose permissions have lapsed, exactly as it does for one an editor has
genuinely emptied — indistinguishable from the server. The costs are not
symmetric: keeping videos that were removed is mild staleness, while wiping a
good cache empties a published page.

An empty result therefore no longer overwrites videos already held; it is
reported as an error instead. Clearing the grid on purpose is done with a new
**Clear cached videos** button, which carries its own capability check and its
own nonce.

This is a deliberate judgement rather than a literal reading of "preserve on
failure", since an empty 200 is not a failure. It is easily reversed if the
client would rather an empty playlist empty the page immediately.


---

## 15. Block Conversion, Round Two (September 25, 2026)

Impact Stories, Podcast and Contact are now block-built like Home, About,
Services and Team. **Blog is deliberately not**, for reasons below.

Nine new blocks; everything else reuses what existed. Hero and the closing
split are the same blocks the other pages use.

### Contact: the form is carried, never replaced

The Contact page's content field is where the Elementor form lives. Seeding
blocks into it would destroy the form, so the seeder copies whatever is there
into the Contact Form block's `formShortcode` attribute, which the block then
runs through `do_shortcode()`. The form keeps rendering, in the same column,
owned by the same plugin, with the same recipients and the same redirect.

Verified with a real shortcode in place:

| Check | Result |
|---|---|
| Shortcode carried into the block | `{"formShortcode":"[elementor-template id=\"4242\"]"}` |
| Valid JSON after saving | yes |
| `parse_blocks()` returns it intact | `[elementor-template id="4242"]` |
| Original also kept in `_fs_pre_block_content` | yes |

That needed `wp_slash()` on the way in: `wp_update_post()` unslashes what it
is given, which stripped the backslashes JSON needs and left the block
unparseable. Caught by testing the round trip rather than assuming it.

The seeder also **skips any Elementor-built page outright** and says so,
rather than writing blocks into content Elementor still owns.

### Why /blog/ is not block-built

Two structural reasons, both specific to it:

1. **It is the Posts Page.** WordPress renders it through `home.php`, and a
   Posts Page's own content is not output by the loop — the loop is the posts
   query. Blocks placed there would simply not render.
2. **That content field is what the coexistence guard reads.** `home.php`
   checks Page 1191's `_elementor_edit_mode` and renders its `post_content`
   wholesale when Elementor owns it. Putting blocks in the same field is a
   direct collision with the mechanism protecting the blog.

On top of that the listing is query-driven, and the sprint requires its query
and URL behaviour to stay identical — which blocks controlling the loop would
put at risk.

The intro copy above the listing is already editable: `home.php` renders the
Posts Page's content as the standfirst when it is set. That is the safe
portion of the ask, and it already works.

### Preserved throughout

- Page IDs, slugs and URLs untouched: impact-stories 15, podcast 43, contact 44.
- Elementor guard unchanged on all three templates.
- Buzzsprout untouched — the Podcast blocks render the same vendor embed.
- No YouTube player on page view; the video block reads cache only.
- Impact Stories filters still server-rendered, so a filtered view stays
  shareable and works with JavaScript off. Verified at `?state=Illinois`:
  spotlight correctly hidden, filters present, count correct.
- The merged story query — records plus migrated legacy Pages, interleaved by
  year then date — moved into one shared helper so the spotlight and the grid
  cannot disagree about it.
