# Migration & QA Rules

Status: **placeholder** — baseline guidelines only, no migrations defined yet.

## 1. Purpose

This document defines the rules and checklist for content/data migrations and QA on
`focused-schools`. It exists to prevent data loss and ensure changes are verifiable
before they reach production.

## 2. General Migration Rules

- Never run migrations directly against production data without a tested rollback plan.
- Never modify or delete `wp-content/uploads/`, `wp-config.php`, or database dumps as
  part of a migration script.
- All migration scripts must be idempotent (safe to re-run without duplicating data).
- Back up the database before any migration that writes to it.
- Document the purpose and scope of each migration in its commit message or PR
  description.

## 3. Migration Checklist (template)

- [ ] Migration scope defined (which content types / data are affected)
- [ ] Backup taken / rollback plan documented
- [ ] Migration tested on a local or staging copy first
- [ ] Idempotency verified (safe to re-run)
- [ ] Data spot-checked after running
- [ ] Relevant docs (`architecture.md`, `component-specs.md`) updated if content model changed

## 4. QA Rules

- [ ] Changes reviewed against the design system (`design-system.md`) and component
      specs (`component-specs.md`)
- [ ] No PHP notices/warnings/errors introduced (check debug log)
- [ ] No console errors introduced in the browser
- [ ] Responsive behavior checked (mobile / tablet / desktop)
- [ ] Accessibility basics checked (headings, alt text, keyboard navigation)
- [ ] Verified on a non-production environment before sign-off

## 5. Staging Environment Safety Controls

- [ ] **Search engine restriction**
  - [ ] WordPress "Discourage search engines from indexing this site" enabled on staging
  - [ ] `robots.txt` on staging disallows all crawling (`Disallow: /`)
  - [ ] `noindex, nofollow` meta tag/header verified in staging page source
- [ ] **Form & email safety**
  - [ ] Real lead-notification emails disabled or redirected to a test inbox on staging
  - [ ] Form submissions on staging clearly logged/flagged as test data
  - [ ] Any third-party CRM/webhook integrations point to a sandbox/test endpoint, not production
- [ ] **Canonical & database URL isolation**
  - [ ] Staging `siteurl`/`home` values isolated from production (not sharing the same options)
  - [ ] Canonical tags on staging point to the staging domain, not production
  - [ ] Search-replace of URLs performed correctly when cloning the database (no stray production URLs left in staging content/options)
  - [ ] No staging URLs leak into production (e.g., via cached templates or hardcoded links)

## 6. Backup & Rollback Protocol

### 6.1 Full Site Backup Requirements (before any deployment)

- [ ] Full database backup taken and stored in a known, accessible location
- [ ] Full file backup taken (`wp-content/themes/focused-schools/`, `wp-content/plugins/focused-schools-core/`, and any other modified paths)
- [ ] Backup includes a timestamp and short description of the pre-deployment state
- [ ] Backup integrity spot-checked (e.g., database dump opens/imports without errors)
- [ ] Backup location and retrieval steps documented/communicated to the team

### 6.2 Rollback Procedure (if issues occur)

1. Stop further changes — do not deploy on top of a known issue.
2. Identify the scope of the problem (files only, database only, or both).
3. Restore from the most recent verified backup:
   - [ ] Restore the database backup taken in 6.1
   - [ ] Restore the file backup taken in 6.1
4. Clear any caching layers (SiteGround caching, CDN, browser cache) after restoring.
5. Verify the site loads correctly post-rollback (spot-check key pages, forms, and templates).
6. Document what went wrong, what was rolled back, and the timestamp of the rollback.
7. Do not re-attempt the deployment until the root cause is understood and a fix has been tested on staging.

## 7. Verification Checklist for Production vs. Staging

- [ ] Confirm which environment you are viewing before testing (check the URL/domain)
- [ ] WordPress & PHP versions match expectations for each environment (see `docs/audit/audit-report.md`)
- [ ] Staging shows noindex/robots restrictions; production does not
- [ ] Forms on staging use test/sandboxed destinations; production forms use real destinations
- [ ] Analytics/tracking scripts fire only on production (unless intentionally testing tracking on staging)
- [ ] Content/data differences between staging and production are expected and documented (not accidental drift)
- [ ] Sign-off recorded before promoting staging changes to production

## 9. Impact Stories Legacy Page Bridge (WP-CLI Migration Utility)

A WP-CLI command tags approved, pre-existing legacy Pages with
`_fs_legacy_impact_story = 1` so they can be recognized as impact stories without
migrating their content, slug, or post type into the new `fs_impact_story` post type.
Implemented in `wp-content/plugins/focused-schools-core/modules/impact-stories/`
(`Legacy_Bridge` class + `CLI_Command` class).

### 9.1 Usage

```
wp focused-schools tag-legacy-impact-stories --ids=12,45,67          # dry run (default)
wp focused-schools tag-legacy-impact-stories --ids=12,45,67 --write  # applies, asks to confirm
```

- `--ids` is a comma-separated list of Page IDs, supplied at run time — this list is
  site-specific, human-approved data and is never hardcoded into plugin code.
- Without `--write`, the command only prints a report table (ID, Title, Exists, Is Page,
  Already Tagged, Eligible) and makes no changes.
- With `--write`, the same report is printed, then WP-CLI's own `--confirm` prompt blocks
  until you confirm (or pass the global `--yes` flag) before anything is written.
- A Page is only eligible if it exists, is `post_type === 'page'`, and is not already
  tagged. Anything else is skipped and reported as ineligible rather than silently
  ignored.

### 9.2 Guardrails

- **Dry-run by default.** No flag is required to preview; `--write` is required to change
  anything.
- **Never alters `post_type`, `post_name` (slug), post content, or Yoast (or any other)
  metadata.** The `Legacy_Bridge` class only ever calls `update_post_meta()` for its own
  `_fs_legacy_impact_story` key — it has no code path that calls `wp_update_post()` or
  touches any other meta key.
- Follow the standard rules in §2 and §6 (back up before running with `--write`,
  idempotent by design since already-tagged pages are reported as ineligible and left
  alone on re-run).

## 10. Open Questions

- (none yet — add items here as migration/QA needs are identified)
