# 04 — Elementor and Blog audit

## Dependency result

**Can Elementor safely be removed during this rebuild without breaking existing Blog Posts? ONLY AFTER MIGRATION.**

Elementor 4.2.4 and Elementor Pro 4.2.3 are active in Site Health. The archive contains 29 published Posts with nonempty `_elementor_data`; 94 published Posts have no Elementor layout data. More decisively, Theme Builder template 918 is the active Single Post template, template 932 is the Posts Archive, template 945 is the global Header, and template 941 is the general Footer. Removing Elementor/Pro would remove shared Blog rendering even for the 94 non-Elementor Posts.

### Counts

- TOTAL PUBLISHED POSTS: **123**
- ELEMENTOR POSTS: **29**
- NON-ELEMENTOR POSTS: **94**
- Published Pages with Elementor data: **27 of 27**

## Elementor Blog widgets

Across the 29 Elementor Posts, stored widget counts are: text editor 89, image 54, spacer 51, divider 16, video 5, gallery 1, and social icons 1. No Essential Addons or HT Mega widget type was found inside Blog Posts.

Theme Builder supplies additional Pro widgets: theme post featured image/content, Posts, share buttons, post navigation, navigation menus and archive posts. Elementor Pro is therefore a hard dependency even where article bodies are simple.

## Article-body storage

Five recent Posts were sampled: IDs 5189, 5159, 5127, 5068 and 5009. All five contain meaningful article HTML in `wp_posts.post_content` and matching text in Elementor metadata. Five older Elementor Posts were also sampled: IDs 17, 3449, 3582, 3613 and 3677. All five contain meaningful `post_content`; their Elementor text is identical or very close, with minor length differences attributable to markup/settings.

The five oldest Posts overall (IDs 199, 197, 195, 193 and 191, from 2017–2018) have substantial native `post_content` and no Elementor layout data.

This means the prose is not trapped exclusively in Elementor for the sampled Posts. It does **not** make removal safe: images, spacing, video, gallery and social widgets plus global single/archive templates still depend on Elementor metadata and Pro rendering.

## Blog implementation

- Blog URL: `https://www.focusedschools.com/blog/`
- Posts page: ID 1191
- Permalink setting: `/%postname%/`; individual Posts therefore use root-level `/<post-slug>/`
- Archive: Elementor Pro template 932, condition `include/archive/post_archive`
- Single Post: Elementor Pro template 918, broad singular condition with page 36 excluded
- Header/footer: Elementor Pro 945 and 941
- Authors: 95 published Posts by “Grapevine Webmaster”; 28 by “Bryan Roberts”
- Categories: all 123 published Posts are Uncategorized; other category terms have zero Posts
- Tags: no active post-tag relationships found
- Featured images: all 123 published Posts have `_thumbnail_id`
- SEO: Yoast metadata is widespread (138 titles, 124 descriptions and 124 primary-category values across stored content); public pages output Yoast canonical, OpenGraph and schema markup
- Related Posts: template 918 uses Elementor Posts widgets; exact query settings must be preserved or explicitly redesigned

## Safe transition

The proposed mixed model is technically safe:

1. Keep Elementor and Elementor Pro active.
2. Keep template 918 for legacy Posts initially, or build a custom single template that detects nonempty `_elementor_data` and delegates legacy bodies to Elementor while rendering Gutenberg normally.
3. Author new Posts with Gutenberg and test headings, galleries, embeds, featured images, author/date, share links, navigation and related Posts.
4. Do not bulk-convert the 29 Elementor Posts during the one-week sprint. Their text is recoverable, but media/layout parity needs per-Post review.
5. Remove Elementor only after all dependent Pages, Posts, forms and Theme Builder locations have migrated and a complete URL/render comparison passes.
