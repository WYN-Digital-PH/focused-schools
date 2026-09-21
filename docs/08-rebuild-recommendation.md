# 08 — Rebuild recommendation

## Decision

The proposed hybrid architecture is appropriate **if it is implemented as a phased coexistence model**. It is unsafe as a big-bang Elementor removal.

1. **Can we safely use a custom theme?** Yes. Evidence: content is stored in native Pages/Posts, with no business-content CPT dependency. The custom theme must continue to honor Elementor Theme Builder for legacy routes until migration completes.
2. **Can new pages stop using Elementor?** Yes. Gutenberg can own rebuilt Pages through `theme.json` and reusable patterns. Use existing Page IDs and slugs.
3. **Does Elementor need to remain installed for legacy Blog Posts?** Yes. Twenty-nine Posts have Elementor data, and all Posts currently depend on Elementor Pro's single, archive, header and footer templates.
4. **Should existing Blog Posts be migrated?** Not during the one-week sprint. Five recent and five older Elementor Posts show meaningful body content in `post_content`, so later migration is feasible, but media/layout and global-template behavior need per-Post QA.
5. **Does Team deserve a CPT?** No based on current evidence. It is one Page with repeated cards and no demonstrated individual URLs, filters or relational reuse.
6. **Does Impact Stories deserve a CPT now or later?** Later only if a structured archive/filtering requirement is approved. Keep the existing Pages now to avoid URL and SEO risk.
7. **Should Services remain a Page?** Yes. It is currently one Page and the new copy defines three anchored service sections rather than independently queryable records.
8. **How should Podcast work?** Preserve the current Buzzsprout player and manually embedded videos first. Later, replace the manual video grid with a cached dedicated YouTube playlist integration after ownership, ordering, quota and fallback requirements are approved.
9. **Which plugins must remain?** Elementor, Elementor Pro, Yoast SEO, Security Optimizer, Speed Optimizer and a tested backup solution. Initially retain Buzzsprout, HT Mega, Simple Custom CSS and JS, Version Control for jQuery, Redirection, Multiple Domain and WP Maps until their evidenced responsibilities are migrated or ruled out.
10. **Which plugins can be removed after launch?** Likely Advanced Database Cleaner, AI Agent, Duplicate Page/Post, Jetpack, WordPress Importer, Ultimate Dashboard, unused Elementor add-ons, redundant migration tooling and the inactive utilities. Each removal requires a staged disable test and owner confirmation; this audit did not deactivate anything.
11. **What blocks a one-week internal production sprint?** Form delivery, a fresh backup/restore drill, URL/canonical crawl parity, mixed Blog rendering QA, podcast configuration reconciliation, credential handling, and a frozen scope.
12. **What should not change during this sprint?** Permalinks, slugs, Page/Post IDs, taxonomies, Blog bodies, Impact Story content type, Podcast service, form recipients/actions, Yoast metadata, redirects, security controls, kaching, PHP version, Elementor activation, or production data architecture.

## Recommended sequence

1. Freeze scope to the seven primary Pages and record their existing IDs/URLs.
2. Take and restore a fresh staging backup; restrict backup access and address exposed credentials.
3. Establish crawl, form, Blog and Podcast baselines.
4. Build the independent hybrid theme, design tokens and native patterns.
5. Migrate one existing Page at a time without changing its ID or slug.
6. Keep Elementor's legacy renderer and Pro templates for Blog/remaining Pages.
7. Rebuild header/footer carefully, retaining form behavior and native menu objects.
8. Run responsive, accessibility, PHP-warning, console, SEO, analytics, form and podcast QA.
9. Obtain human approval, take a launch backup, deploy with rollback, then monitor.
10. Conduct plugin removal and legacy content migration as separate, evidence-driven follow-up work.

## Architecture boundary

Use Gutenberg for newly rebuilt primary Pages, native Posts for Blog, native menus, `theme.json`, and a small site-core plugin only for data or behavior that must survive a theme change. Do not create Team, Service or Impact Story CPTs during this sprint. Do not build a custom REST API, React blocks, page-builder replacement framework or YouTube automation as part of the core rebuild.

## Evidence confidence

High-confidence conclusions come from matching Site Health, database metadata and public HTML: Elementor dependency, content types, forms, podcast embeds, permalinks, caching and Yoast output. Plugin-removal candidates are lower-confidence until staged deactivation and owner/workflow confirmation. No production state was changed during this audit.
