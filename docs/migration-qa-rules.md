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

## 5. Open Questions

- (none yet — add items here as migration/QA needs are identified)
