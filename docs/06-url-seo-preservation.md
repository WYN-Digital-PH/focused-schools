# 06 — URL and SEO preservation

## Current behavior

- Home uses static Page ID 992.
- Blog uses Posts Page ID 1191 at `/blog/`.
- Permalinks use `/%postname%/`; Blog Posts are root-level URLs.
- Category and tag bases are empty, so WordPress defaults apply.
- Yoast SEO 28.4 outputs canonicals, OpenGraph, Twitter metadata, breadcrumb/WebPage/WebSite/Organization schema, robots rules and XML sitemaps.
- Public `robots.txt` allows crawling and points to `/sitemap_index.xml`.
- The sitemap index currently lists `post-sitemap.xml` and `page-sitemap.xml`.
- The Redirection plugin's archive table contains zero redirect items. This does not rule out SiteGround, Apache, plugin-runtime or historical redirects.

## Primary URL inventory

| Page ID | Current URL | Treatment |
| ---: | --- | --- |
| 992 | `/` (stored slug `home`) | Preserve front-page assignment and ID |
| 3726 | `/about-our-mission-vision/` | Preserve |
| 1198 | `/team/` | Preserve |
| 1187 | `/services/` | Preserve, including required anchors |
| 3785 | `/impact-stories/` | Preserve |
| 1189 | `/podcast/` | Preserve |
| 1191 | `/blog/` | Preserve as Posts Page |
| 1195 | `/contact/` | Preserve |
| 36 | `/thanks/` | Preserve form confirmation target |
| 1356 | `/lets-get-started/` | Preserve |
| 3638 | `/special-reports/` | Preserve |
| 3702 | `/careers/` | Preserve |
| 4798 | `/newsletter-sign-up/` | Preserve |
| 4827 | `/focused-schools-is-an-approved-dese-approved-partner-again/` | Preserve despite awkward slug |

Published Impact Story URLs that must remain Pages and retain their slugs include:

- `/removing-barriers-together-memorial-middle-schools-path-to-improvement/`
- `/a-model-for-community-engagement-data-days-in-champaign-unit-4-school-district/`
- `/data-driven-impact-westminster-center-schools-story/`
- `/building-a-culture-of-service-in-springfield-public-schools/`
- `/leveraging-data-to-improve-attendance-and-outcomes-at-royalston-community-school/`
- `/opening-new-doors-for-college-and-career-readiness-at-warren-high-school/`

There are also published service/detail Pages at `/change-of-leader/`, `/path-to-fidelity/`, `/from-data-collection-to-data-action/`, and `/strategic-planning-support/`.

## Risks

- Creating replacement Pages instead of updating existing IDs can change URLs, menu object references, Yoast indexables and inbound-link history.
- Changing the Posts Page, permalink structure, category/tag base, or any of the slugs in
  the inventory above would 404 previously-indexed URLs, break inbound/external backlinks,
  and lose accumulated search ranking for that URL — none of these should change without
  explicit approval, per `AGENTS.md`'s URL Preservation rule.

> **Editorial note (found during the Legacy Blog Compatibility audit):** the rest of this
> "Risks" section was lost to file corruption — the content on disk past this point was
> binary/garbled, not valid text, and `git log` shows only one commit for this file
> (`df00632`, "add docs"), which already contains the same corruption — there is no clean
> version in history to recover from. Truncated the unreadable tail rather than
> fabricating replacement content. If the original risk list is needed, it will have to be
> rewritten from scratch or sourced from whoever authored the original migration-planning
> documents.
