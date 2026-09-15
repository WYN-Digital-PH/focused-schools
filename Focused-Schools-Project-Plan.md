# Focused Schools — AI-Assisted WordPress Website Refresh

## Objective
Refresh the Focused Schools WordPress website using an accelerated AI-assisted workflow while preserving professional design quality, client editability, SEO/URL integrity, accessibility, performance, maintainability, and existing legacy functionality.

Warning: Undefined array key 1 in C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-ai-assistant\hostinger-ai-assistant.php on line 90

Warning: Undefined array key 2 in C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-ai-assistant\hostinger-ai-assistant.php on line 90

Warning: Undefined array key 1 in C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\hostinger-easy-onboarding.php on line 52

Warning: Undefined array key 2 in C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\hostinger-easy-onboarding.php on line 52

Fatal error: Uncaught Error: Typed static property Hostinger\WpHelper\Utils::$apiTokenFile must not be accessed before initialization in C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-ai-assistant\vendor\hostinger\hostinger-wp-helper\src\Utils.php:118 Stack trace: #0 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\AmplitudeEvents\Amplitude.php(40): Hostinger\WpHelper\Utils::getApiToken() #1 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\Admin\Onboarding\AutocompleteSteps.php(33): Hostinger\EasyOnboarding\AmplitudeEvents\Amplitude->__construct() #2 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\Bootstrap.php(116): Hostinger\EasyOnboarding\Admin\Onboarding\AutocompleteSteps->__construct() #3 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\Bootstrap.php(43): Hostinger\EasyOnboarding\Bootstrap->load_onboarding_dependencies() #4 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\Bootstrap.php(37): Hostinger\EasyOnboarding\Bootstrap->load_dependencies() #5 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\EasyOnboarding.php(16): Hostinger\EasyOnboarding\Bootstrap->run() #6 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\includes\EasyOnboarding.php(20): Hostinger\EasyOnboarding\EasyOnboarding->bootstrap() #7 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\loader.php(67): Hostinger\EasyOnboarding\EasyOnboarding->run() #8 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-easy-onboarding\hostinger-easy-onboarding.php(110): require_once('C:\\Users\\shaii\\...') #9 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-settings.php(597): include_once('C:\\Users\\shaii\\...') #10 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-config.php(103): require_once('C:\\Users\\shaii\\...') #11 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-load.php(50): require_once('C:\\Users\\shaii\\...') #12 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-admin\admin.php(35): require_once('C:\\Users\\shaii\\...') #13 C:\Users\shaii\Local Sites\focused-schools\app\public\wp-admin\index.php(10): require_once('C:\\Users\\shaii\\...') #14 {main} thrown in C:\Users\shaii\Local Sites\focused-schools\app\public\wp-content\plugins\hostinger-ai-assistant\vendor\hostinger\hostinger-wp-helper\src\Utils.php on line 118
There has been a critical error on this website. Please check your site admin email inbox for instructions. If you continue to have problems, please try the support forums.

Learn more about troubleshooting WordPress.
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
