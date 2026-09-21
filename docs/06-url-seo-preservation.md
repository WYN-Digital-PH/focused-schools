# 06 â€” URL and SEO preservation

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
- Changing the Posts Page, permalini¬ÍÑÉÕÑÕÉ”°…Ñ•½Éä‰…Í”½ÈÁ½ÍĞÑåÁ”¡…¹•Ì	±½œUI1Ì¸(´½¹Ù•ÉÑ¥¹œ%µÁ…ĞMÑ½É¥•Ì¥¹Ñ¼„AP¡…¹•ÌÉ•İÉ¥Ñ”ÉÕ±•Ì…¹Á½ÍÍ¥‰±ä…É¡¥Ù”½Í¥¹±”UI1Ì¸(´±•µ•¹Ñ½ÈQ¡•µ”	Õ¥±‘•È½¹‘¥Ñ¥½¹Ì…¸…ÕÍ”‘ÕÁ±¥…Ñ”½Èµ¥ÍÍ¥¹œ…¹½¹¥…°½¹Ñ•¹Ğ¥˜¹•ÜÑ•µÁ±…Ñ•Ì½Ù•É±…À±•…ä±½…Ñ¥½¹Ì¸(´I•µ½Ù¥¹œe½…ÍĞİ¥Ñ¡½ÕĞµ¥É…Ñ¥¹œ¥ÑÌÍÑ½É•µ•Ñ…‘…Ñ„¡…¹•Ì…¹½¹¥…±Ì°Í¡•µ„°Í½¥…°ÁÉ•Ù¥•İÌ°É½‰½ÑÌ…¹Í¥Ñ•µ…ÀUI1Ì¸(´I•µ½Ù¥¹œÑ¡”¡¥±Ñ¡•µ”Ì€ÄÀÔµ‰åÑ”µ•Ñ„µ‘•ÍÉ¥ÁÑ¥½¸™¥±Ñ•È…¸¡…¹”Í¹¥ÁÁ•ÑÌ¸Q¡”™¥±Ñ•È¥Ì™±…İ•‰ÕĞ¥ÑÌ•™™•ĞµÕÍĞ‰”½µÁ…É•‰•™½É”É•Ñ¥É•µ•¹Ğ¸(´Q¡”!•…‘•Èµ•¹Ô½¹Ñ…¥¹Ì•Ù¥‘•¹”½˜„ÍÑ…±”‰½ÕĞÑ…É•Ğ€¡ÍÕ•ÍÌµÍÑ½É¥•ÌÅ€¤¥¸Ñ¡”É•ÍÑ½É•‘…Ñ„İ¡¥±”ÁÕ‰±¥ŒÁÉ½‘ÕÑ¥½¸¹…Ù¥…Ñ¥½¸ÕÉÉ•¹Ñ±äÉ•Í½±Ù•ÌÑ¼€½…‰½ÕĞµ½ÕÈµµ¥ÍÍ¥½¸µÙ¥Í¥½¸½€¸áÁ½ÉĞÑ¡”…ÑÕ…°ÁÉ½‘ÕÑ¥½¸µ•¹ÕÌ…Ğµ¥É…Ñ¥½¸Ñ¥µ”…¹É•Í½±Ù”Ñ¡¥Ì‘¥ÍÉ•Á…¹äİ¥Ñ¡½ÕĞ¡…¹¥¹œÑ¡”…ÁÁÉ½Ù•…¹½¹¥…°UI0¸((ŒŒAÉ•Í•ÉÙ…Ñ¥½¸ÁÉ½•‘ÕÉ”()áÁ½ÉĞ„ÁÉ½‘ÕÑ¥½¸UI0‰…Í•±¥¹”¥µµ•‘¥…Ñ•±ä‰•™½É”ÍÑ…¥¹œµ¥É…Ñ¥½¸ìÉ…İ°ÍÑ…ÑÕÌ°…¹½¹¥…°°Ñ¥Ñ±”°‘•ÍÉ¥ÁÑ¥½¸°¥¹‘•á…‰¥±¥Ñä…¹É•‘¥É•Ğ‘•ÍÑ¥¹…Ñ¥½¸ìÕÁ‘…Ñ”½¹Ñ•¹Ğ¥¸Á±…”ìÁÉ•Í•ÉÙ”%Ì½Í±ÕÌìÉ••¹•É…Ñ”e½…ÍĞ¥¹‘•á…‰±•Ì½¹±ä½¸ÍÑ…¥¹œì½µÁ…É”Í¥Ñ•µ…ÁÌ…¹„™Õ±°É…İ°ì…‘½¹”µÑ¼µ½¹”€ÌÀÄÉ•‘¥É•ÑÌ½¹±ä™½È•áÁ±¥¥Ñ±ä…ÁÁÉ½Ù•Õ¹…Ù½¥‘…‰±”¡…¹•Ì¸