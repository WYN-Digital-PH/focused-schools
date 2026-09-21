# 02 — Plugin dependency matrix

Production activation is taken from the supplied Site Health report. “Required now” means removal before migration would have a demonstrated or plausible impact that has not been cleared by testing.

| Active plugin | Current evidence / apparent job | Required now | Treatment after rebuild | Removal risk |
| --- | --- | --- | --- | --- |
| Advanced Database Cleaner 4.2.0 | Database maintenance; no frontend widget or template dependency found | No demonstrated runtime need | Use only under an approved maintenance procedure, then remove if operationally unnecessary | Low |
| AI Agent by SiteGround 1.3.0 | Administrative AI assistant; database log table exists | No demonstrated frontend need | Candidate for removal after owner confirms it is unused | Low |
| Akeeba Backup CORE 8.0.0.1 | Backup profiles, schedules and storage tables exist | Yes until replacement backup and restore test exist | Keep through launch/rollback; later consolidate backup tooling | High |
| All-in-One WP Migration 7.110 | Site Health says active; generated the supplied archive, but plugin files are absent from it | Migration-only | Confirm production installation; retain during migration if it is the chosen rollback/export tool | Medium |
| Buzzsprout Podcasting 2.0.4 | Stores RSS feed `feeds.buzzsprout.com/2172941.rss`; plugin CSS appears on `/podcast/` | Initially yes | Keep until the Podcast page and feed/player behavior are rebuilt and verified | Medium |
| Duplicate Page and Post 2.9.7 | Editorial convenience only; no content runtime dependency found | No | Candidate for removal after launch | Low |
| Elementor 4.2.4 | 27 published Pages, 29 Posts, Theme Builder templates, Podcast videos and global layouts | Yes | Keep for legacy content during phased migration | Blocker |
| Elementor Pro 4.2.3 | Header, footer, archive, single-post, form, posts, share and navigation widgets | Yes | Keep while any listed template/form remains live | Blocker |
| Essential Addons 6.8.3 | Frontend assets/localization load; no EA-specific widget type found in stored Elementor data | Until staged disable test | Candidate for removal after a complete dependency and visual test | Medium |
| HT Mega Addons 3.2.5 | `htmega-google-map-addons` used on published pages 3638 and 3726 | Yes for those pages | Replace maps with an approved native/embed solution or keep | High |
| Jetpack 16.1.3 | No active modules; disconnected; outbound tests fail | No demonstrated feature need | Candidate for removal after confirming no hidden monitoring/account requirement | Low |
| Multiple Domain 1.0.7 | Config contains only `www.focusedschools.com`; local PHP 8.2 test logged a null-to-string deprecation | Unclear | Verify domain/DNS requirement; remove if only canonical production domain remains | Medium |
| Real-Time Find and Replace 4.3 | Runtime text replacement capability; no exact active rule was established | Until rule audit | Export rules, document purpose, then retire if empty/obsolete | High |
| Redirection 5.10.0 | Tables exist but archive contains zero redirect items; public server redirects may exist elsewhere | Until host/server redirect audit | Keep during URL comparison; remove only if rules are conclusively absent or migrated | Medium |
| Security Optimizer 1.6.8 | Multiple active hardening settings evidenced in options and response headers | Yes | Keep or replace with documented equivalent controls | High |
| Simple Custom CSS and JS 3.54 | Injects GA4 and two custom scripts | Yes until code is moved | Move valid GA/behavior into controlled theme/plugin or tag manager; retire obsolete scripts | High |
| Speed Optimizer 7.8.2 | SiteGround dynamic cache enabled; proxy cache hit observed | Yes on current host | Keep while hosted on SiteGround unless equivalent cache integration replaces it | High |
| Ultimate Dashboard 3.8.17 | Custom admin page post type exists | No demonstrated frontend need | Keep only if editors use its dashboard customization | Low |
| Version Control for jQuery 4.0.2 | Alters jQuery selection/version behavior | Until frontend compatibility audit | Remove only after legacy Elementor/add-on regression tests | High |
| WordPress Importer 0.9.6 | Import utility only | No runtime need | Remove after migration if no ongoing import workflow | Low |
| WP Maps 4.9.9 | Six map-style posts and plugin tables/settings exist; no `wpgmza` shortcode found in published content | Unclear | Search widgets/options and visually test likely map pages; retire only after proving unused | Medium |
| Yoast SEO 28.4 | Canonicals, metadata, OpenGraph, schema, robots and XML sitemap are live | Yes | Keep through launch; migrate only with explicit metadata parity testing | Blocker |

## Inactive plugins

| Plugin | Evidence-based treatment |
| --- | --- |
| Advanced Custom Fields 6.8.9 | No active ACF content architecture was established. Do not activate merely to build Team or Services. Remove after confirming no field data/templates are expected. |
| Better Search Replace 1.4.11 | Migration utility; keep off production except controlled use. |
| GA Google Analytics | GA4 is currently injected by Simple Custom CSS and JS. Avoid enabling a duplicate tracker. |
| Image Hover Effects Elementor Addon | No matching widget type found. Candidate for removal. |
| Shortcoder | No valid published shortcode dependency found. Bracketed prose produced false-positive text matches. Candidate for removal after shortcode registry check. |
| User Role Editor | Administrative tool. Keep inactive unless a documented role workflow requires it. |

## Minimum launch set

The demonstrated launch-critical set is Elementor, Elementor Pro, Yoast SEO, Security Optimizer, Speed Optimizer, and the selected backup tool. Buzzsprout, HT Mega, Simple Custom CSS and JS, Version Control for jQuery, Redirection, Multiple Domain, and WP Maps remain conditional until their specific content or rules are migrated or disproved.
