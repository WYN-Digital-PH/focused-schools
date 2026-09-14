# Focused Schools — AI-Assisted WordPress Website Refresh

## Objective
Refresh the Focused Schools WordPress website using an accelerated AI-assisted workflow while preserving professional design quality, client editability, SEO/URL integrity, accessibility, performance, maintainability, and existing legacy functionality.

## AI Roles
- Claude Design: extract the visual system from the approved homepage and create approved internal-page mockups.
- Claude Code: assist the WordPress developer with repository setup, backend/content models, custom theme implementation, migration helpers, testing, and code review.
- Human team: owns architecture, design approval, code quality, QA, deployment, and client communication.

## Frozen Architecture
- Custom hybrid theme: `focused-schools`
- Site plugin: `focused-schools-core`
- Gutenberg for rebuilt/new page content
- Team CPT: `fs_team_member`
- Services CPT: `fs_service`
- Impact Stories CPT: `fs_impact_story`
- Blog remains native WordPress Posts
- Elementor and Elementor Pro remain active for legacy compatibility during phased migration
- Yoast SEO, Buzzsprout, SiteGround security/cache, and other required legacy dependencies remain until explicitly migrated and QA-approved

## Content Model Decisions
### Team
Use a structured CPT so the client can add a Team Member without editing the Team landing page. No individual Team URLs initially.

### Services
Use a CPT so the client can manage the three service lanes centrally and so future substantive service-detail pages can be enabled later. Do not expose thin single-service pages during the initial release.

### Impact Stories
Use a CPT for all new Impact Stories so publishing automatically updates the landing page. Existing legacy Impact Story Pages retain their existing Page IDs, post type, slugs, URLs, and Yoast metadata during this sprint.

### Podcast
Preserve Buzzsprout and the current podcast functionality first. YouTube playlist automation is a stretch/Phase 1.1 enhancement and must not delay launch.

### Forms
Preserve existing Elementor Pro forms during this sprint. Do not replace them with a custom form framework.

## Critical Preservation Rules
- Do not change existing primary Page IDs.
- Do not change slugs/permalink structure.
- Preserve `/blog/` and root-level Blog Post URLs using `/%postname%/`.
- Do not bulk-convert legacy Elementor Blog Posts.
- Do not bulk-convert existing Impact Story Pages.
- Do not remove Elementor/Elementor Pro while legacy Blog/forms/templates depend on them.
- Do not remove Yoast without explicit metadata parity testing.
- Do not commit `.wpress`, SQL, credentials, uploads, WordPress core, API keys, or secrets to Git.

## Internal Sprint Target
Production candidate in approximately 7 working days. Keep the client-facing timeline at approximately 3 weeks to preserve revision and contingency buffer.

## Parallel Workstreams
1. Project Management & access
2. Claude Design / design system and internal pages
3. WordPress backend / content architecture
4. Theme and frontend implementation
5. Legacy integration and compatibility
6. QA, SEO, performance, launch

## Quality Gates
### Design Ready
Copy final, section hierarchy final, desktop approved, mobile behavior documented, components mapped, assets available, human approval complete.

### Backend Ready
Admin UX works, data saves correctly, sanitization/capability checks are in place, client workflow is simple, frontend helper/query behavior is documented.

### QA Ready
Final content present, responsive implementation complete, links/images working, dynamic queries correct, no relevant PHP warnings or browser-console errors.

### Launch Ready
Staging approved, forms tested end-to-end, actual emails received, Blog/legacy content tested, URLs/canonicals preserved, Buzzsprout tested, responsive/accessibility QA passed, production backup and rollback ready.

## Operating Rules
- No direct development on production.
- Maximum 3 development tasks In Progress at once.
- If blocked more than 30 minutes, document the blocker, tag Project Coordinator/Briggs, and move to another approved task.
- Human review is mandatory for all AI-generated design/code.
