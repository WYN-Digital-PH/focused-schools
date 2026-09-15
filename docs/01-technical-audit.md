# 01 — Technical audit

Audit date: 2026-09-14  
Mode: read-only  
Sources: supplied `.wpress` export dated 2026-09-10, supplied WordPress Site Health Info dated 2026-09-10, and public production responses. The backup's `active_plugins` option is empty, so production activation comes from Site Health rather than that unreliable export field.

## Environment

| Item | Evidence |
| --- | --- |
| WordPress | 7.1 (Site Health and export metadata) |
| PHP | 8.2.33, Apache module, 64-bit; 768 MB server limit and 256 MB WordPress frontend limit |
| Database | MySQL 8.4.6-6; mysqli/mysqlnd |
| Production URL | `https://www.focusedschools.com/`; HTTPS and pretty permalinks enabled |
| Active theme | Hello Elementor Child 1.0.1 (`hello-theme-child-master`) |
| Parent theme | Hello Elementor 3.0.1; Site Health offered 3.5.1 |
| Multisite | No |
| Search visibility | Public (`blog_public=1`); public robots file allows crawling |
| Cache | SiteGround Speed Optimizer has dynamic cache enabled; public response returned `x-cache-enabled: True` and `x-proxy-cache: HIT` |
| Image processing | GD 2.3.3; no Imagick; JPEG, PNG, GIF, WebP and AVIF available |
| Storage | Site Health reports 9.29 GB total: 5.97 GB WordPress path, 2.38 GB plugins, 750 MB uploads, 194 MB database |

There are no must-use plugins or WordPress drop-ins in the archive. No standalone custom plugin was identified. The custom implementation lives mainly in the child theme, WordPress Custom CSS records, Simple Custom CSS and JS records, Elementor templates, and plugin settings.

## Themes and custom functionality

The child theme's `functions.php` contains:

- an editable Media Library alt-text column backed by AJAX;
- a disabled body-ID experiment;
- a Yoast meta-description filter that truncates output to 105 bytes.

The alt-text AJAX handler reads raw request values, lacks nonce verification and a capability check, and outputs stored alt text without escaping. It should not be copied as-is. The Yoast truncation can split multibyte text and should be reviewed before retirement because it affects live SEO output.

Custom CSS records supply inherited typography, list spacing, Blog link colors, hidden submenus, and a tablet hero offset. Simple Custom CSS and JS injects GA4 measurement ID `G-1MG2HCZGRN` in the head, a Squarespace-specific image-padding script, and an old background-color scroll script. The latter two appear obsolete or narrowly scoped but require page-level verification before retirement.

## Active production plugins

Site Health lists 22 active plugins:

Advanced Database Cleaner; AI Agent by SiteGround; Akeeba Backup CORE; All-in-One WP Migration and Backup; Buzzsprout Podcasting; Duplicate Page and Post; Elementor; Elementor Pro; Essential Addons for Elementor; HT Mega Addons; Jetpack; Multiple Domain; Real-Time Find and Replace; Redirection; Security Optimizer; Simple Custom CSS and JS; Speed Optimizer; Ultimate Dashboard; Version Control for jQuery; WordPress Importer; WP Maps; Yoast SEO.

The archive contains files for 21 of these; All-in-One WP Migration is absent even though the supplied file was created by that plugin. Six inactive plugins are present: Advanced Custom Fields, Better Search Replace, GA Google Analytics, Image Hover Effects Elementor Addon, Shortcoder, and User Role Editor.

Detailed necessity and removal risk are in `02-plugin-dependency-matrix.md`.

## Performance and security observations

- Dynamic caching is active at the SiteGround proxy. Browser cache, gzip, CSS/JS minification, JS defer, Memcached, and current image optimization are disabled in the stored Speed Optimizer options. This is evidence of configuration, not a performance score.
- Site Health reports PHP OPcache unavailable. Confirm at the host because this can increase PHP execution cost.
- Site Health reports 834 unoptimized/non-converted images. No CDN or Cloudflare configuration was found; public responses are served through SiteGround's proxy cache.
- Security Optimizer enforces file-editor disabling, XML-RPC disabling, feed disabling, protected system folders, username hiding, XSS headers, and a five-attempt login limit. Preserve equivalent controls.
- Jetpack has no active modules or connection tokens and reports failed outbound HTTP/HTTPS tests to WordPress.com. It has no demonstrated frontend responsibility in this audit.
- No SMTP plugin was found. Elementor forms use WordPress/PHP mail behavior; delivery observability is weak and must be tested on staging.
- The backup contains stored credentials, API keys, plugin licence data, historic session material, and user password hashes. Do not commit or distribute the archive or derived SQL. Rotate exposed reusable credentials and review whether public-browser keys are appropriately restricted. Values are intentionally omitted here.
- PHP 8.2 receives security fixes only through 2026-12-31. A PHP upgrade is prudent after plugin compatibility testing, not during an untested one-week rebuild.

## Limitations

This was not a penetration test. Production admin, host logs, DNS/CDN panels, mail logs, cron events, current database queries, and third-party account dashboards were unavailable. Conclusions are bounded accordingly.
