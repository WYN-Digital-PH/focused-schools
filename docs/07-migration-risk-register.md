# 07 — Migration risk register

| Existing feature | Current implementation | Dependency | Proposed treatment | Risk |
| --- | --- | --- | --- | --- |
| Global header/footer | Elementor Pro templates 945/941 plus special footers | Elementor + Pro + native menus | Build theme equivalents; keep legacy locations until every dependent route passes QA | BLOCKER |
| Single Blog Posts | Template 918; 29 Posts also have Elementor layouts | Elementor + Pro | Mixed renderer: legacy Elementor, new Gutenberg | BLOCKER |
| Blog archive | Posts Page 1191 and Elementor archive 932 | Elementor Pro + native Posts | Rebuild archive while preserving `/blog/` and query behavior | HIGH |
| Forms | Elementor Pro stored submissions, email and redirects | Elementor Pro, host mail, `/thanks/` | Preserve actions and recipients; staged delivery test | BLOCKER |
| SEO | Yoast canonicals, metadata, schema, robots and sitemaps | Yoast + stored postmeta/indexables | Keep Yoast and compare crawl output | BLOCKER |
| URL structure | Static front page and `/%postname%/` | Core settings, IDs, slugs, menus | Update existing records in place; no rewrite changes | BLOCKER |
| Podcast audio | Buzzsprout raw player plus plugin feed setting | Buzzsprout service/plugin | Keep initially; verify mismatched show/feed identifiers | HIGH |
| Podcast video | Manually embedded Elementor YouTube widgets | Elementor; YouTube channel/playlist | Preserve now; automate later as a separate project | MEDIUM |
| About maps/audio | HT Mega map and WordPress audio widgets | HT Mega/external map; media | Replace deliberately or keep until page QA | HIGH |
| Impact Stories | Normal Elementor Pages | Elementor + existing slugs | Keep Pages during sprint | HIGH |
| Team | Repeated content inside one Elementor Page | Elementor Page data/media | Migrate to native blocks on same Page; no CPT now | MEDIUM |
| Analytics | GA4 injected via custom-code plugin | Simple Custom CSS and JS | Move once, verify one pageview, avoid duplication | HIGH |
| Security hardening | SiteGround Security Optimizer | Plugin/host | Preserve or document equivalent before removal | HIGH |
| Caching | SiteGround dynamic cache; public cache hit | Speed Optimizer/host | Keep; define purge step for staging/launch | HIGH |
| Redirects | Redirection installed; zero archived rules | Plugin plus unknown server rules | Fresh production export and crawl before removal | MEDIUM |
| Multiple domains | Plugin configured only for primary domain | Multiple Domain | Confirm DNS/business requirement | MEDIUM |
| jQuery behavior | Version Control for jQuery | Legacy theme/add-ons/custom scripts | Staged disable regression after migration | HIGH |
| Backups | Akeeba plus All-in-One reported active | Backup plugins/host | Choose one tested rollback path; keep until restore test | HIGH |
| Custom child-theme AJAX | Insecure Media Library alt editor | Child theme code | Do not migrate as-is; securely reimplement only if required | HIGH |
| Secrets in backup | Credentials/API/licence/session material stored in export | Historic configuration | Restrict archive, rotate reusable credentials, never commit | BLOCKER |
| Storage footprint | 9.29 GB total; 2.38 GB plugins | Backups/caches/unknown large files | Inventory safely outside sprint; no blind deletion | MEDIUM |

## One-week sprint blockers

1. No verified production-fresh backup and restore drill tied to t
2. No full production URL/canonical/redirect crawl baseline.
3. Elementor Pro forms have not been tested end-to-end on staging, and no SMTP/delivery trace is documented.
4. The mixed Elementor/Gutenberg Blog renderer needs representative visual and functional regression coverage.
5. Podcast ownership/configuration must reconcile the Buzzsprout player ID, feed ID and YouTube playlist.
6. Credentials present in the backup require restricted handling and rotation assessment.
7. The seven-page scope must be frozen; the supplied copy also references supporting/legacy Pages and “keep as is” areas.

These do not prevent productive implementation in one week. They prevent treating one week as a safe, fully validated production cutover without dedicated QA and rollback time.
