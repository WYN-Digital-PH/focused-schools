# Focused Schools — Codex Instructions

## Mission
Build a maintainable custom WordPress implementation for Focused Schools. Client editing experience, accessibility, performance, URL preservation, security, and maintainability are first-class requirements.

## Sources of Truth
Read before making architectural changes:
- `docs/architecture.md`
- `docs/content-model.md`
- `docs/migration-plan.md`
- `docs/design-system.md`
- `docs/component-specs.md`
- `docs/legacy-dependencies.md`
- matching file in `docs/page-specs/`

## Architecture
Custom hybrid WordPress theme: `wp-content/themes/focused-schools`

Site functionality plugin: `wp-content/plugins/focused-schools-core`

New/rebuilt primary pages use Gutenberg.

The site-core plugin owns:
- Global Site Settings
- Team content architecture
- Services content architecture
- Impact Stories content architecture
- migration helpers for explicitly approved legacy content
- Podcast YouTube integration if/when approved

The theme owns:
- HTML presentation
- templates
- reusable components
- CSS and responsive behavior
- `theme.json`
- editor styling

## Existing Site Compatibility
Do not remove/deactivate without explicit approval:
- Elementor
- Elementor Pro
- Yoast SEO
- current SiteGround security controls
- current SiteGround caching
- Buzzsprout
- other plugins still required by documented legacy content

Legacy Blog content and forms must continue to work during the phased migration.

## Content Models
- Team: `fs_team_member`
- Services: `fs_service`
- Impact Stories: `fs_impact_story`

Do not create additional CPTs without approval.

### Team
No public single pages initially.

### Services
Do not expose/index individual service-detail pages until substantive approved content exists.

### Impact Stories
Existing legacy Impact Story Pages retain their Page IDs, slugs, URLs, post type, content, and Yoast metadata. New stories use `fs_impact_story`.

## URL Preservation
Do not change:
- Page IDs
- Post IDs
- slugs
- permalink structure
- taxonomy URLs
- Blog Posts Page
without explicit approval.

Current Blog structure is `/blog/` with individual Posts at root-level `/%postname%/` URLs.

## Forms
Do not build a custom forms framework during this sprint. Existing Elementor Pro forms remain until separately approved for migration.

## Podcast
Buzzsprout remains the audio source. YouTube playlist automation is feature-flagged/stretch work and must never become a launch blocker. Never commit API credentials.

## WordPress Standards
Prefer native WordPress APIs.
- sanitize input
- validate where appropriate
- escape output contextually
- use capability checks
- use nonces for state-changing requests
- avoid direct DB writes when a WordPress API exists
- follow WordPress Coding Standards

## Frontend
Do not invent new colors, fonts, spacing systems, component variants, or breakpoint systems outside the approved design docs.

## AI Development Rules
Before coding:
1. Read the relevant docs.
2. Inspect existing code.
3. State the proposed implementation.
4. Identify files to change.
5. Identify migration/compatibility risks.

During coding:
- make the smallest maintainable change
- reuse existing components
- avoid unnecessary abstraction
- avoid duplicated CSS/PHP
- do not add frameworks without approval

After coding:
1. Run appropriate checks.
2. Review the diff.
3. Verify affected frontend/admin behavior.
4. Report unresolved risks.
5. Update documentation if architecture/behavior changed.

## Prohibited
Do not:
- modify WordPress core
- commit secrets/backups/databases
- change slugs/permalinks
- deploy directly to production
- remove legacy plugins without staged QA and approval
- build custom React blocks unless Core blocks/patterns cannot meet the requirement
- create custom REST APIs without a demonstrated need

## Definition of Done
A feature is complete only when it is readable, maintainable, secure, responsive, accessible, tested, consistent with the approved design system, free from relevant PHP warnings/console errors, and human-reviewed.
