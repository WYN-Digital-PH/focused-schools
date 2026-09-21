# Pre-Migration Baseline QA

Status: **placeholder** — to be filled in as the baseline is captured.

## 1. Purpose

This document captures a snapshot of the live site's URLs, SEO signals, navigation,
content templates, forms, tracking, and key layouts **before** a migration or major
deployment, so the results can be compared against staging/production afterward.

Guardrail: never paste raw credentials, API keys, real email addresses, or PII into
this file. Use sanitized placeholders (e.g. `team@example.com`, `{{measurement_id}}`)
for anything sensitive.

## 2. URL & SEO Baseline

| Page | URL | HTTP Status | Canonical Tag | Page Title | Meta Description | Indexable (Y/N) | Notes |
| ---- | --- | ----------- | -------------- | ---------- | ----------------- | ---------------- | ----- |
| —    | —   | —           | —              | —          | —                  | —                 | —     |

## 3. Navigation Baseline

### 3.1 Primary Menu Structure

```
- Home
  - (placeholder submenu item)
- About
- Blog
- Podcast
- Contact
```

### 3.2 Critical Internal Links

| Link Label | Target URL | Found On Page | Verified (Y/N) | Notes |
| ---------- | ---------- | -------------- | --------------- | ----- |
| —          | —          | —              | —                | —     |

## 4. Content & Media Templates

### 4.1 Blog Archive (`/blog/`)

- Layout notes: _(placeholder)_
- Pagination behavior: _(placeholder)_
- Featured image / excerpt handling: _(placeholder)_

### 4.2 Blog Single Post

- Layout notes: _(placeholder)_
- Author/byline, date, categories display: _(placeholder)_
- Related posts / sidebar behavior: _(placeholder)_

### 4.3 Podcast / Buzzsprout Embed

- Embed method (shortcode / plugin / manual HTML): _(placeholder)_
- Player behavior (autoplay, episode list, RSS feed source): _(placeholder)_
- Fallback behavior if embed fails to load: _(placeholder)_

## 5. Forms & Tracking Baseline

### 5.1 Form Field Mapping

| Form Name | Field | Field Type | Required (Y/N) | Notes |
| --------- | ----- | ---------- | ---------------- | ----- |
| —         | —     | —          | —                 | —     |

### 5.2 Submission Endpoints & Notification Recipients

| Form Name | Submission Endpoint | Notification Recipient(s) (sanitized) | Notes |
| --------- | -------------------- | --------------------------------------- | ----- |
| —         | —                     | —                                        | —     |

### 5.3 Redirect Targets (e.g. `/thanks/`)

| Form Name | Redirect Target URL | Verified (Y/N) |
| --------- | -------------------- | --------------- |
| —         | —                     | —                |

### 5.4 GA4 Analytics Injection Check

- [ ] Injection method confirmed (theme header, plugin, Google Tag Manager)
- [ ] Measurement ID location confirmed (sanitized in notes, e.g. `{{measurement_id}}`)
- [ ] Confirmed which pages/templates fire the GA4 tag
- [ ] Confirmed no duplicate GA4 tags firing (theme + plugin conflict check)

## 6. Screenshot Archive Index

| Screenshot ID | Page/Component | File Path (placeholder) | Date Captured | Notes |
| -------------- | --------------- | ------------------------- | --------------- | ----- |
| —              | —               | —                          | —                | —     |

## 7. Sign-off

- **Captured by:** ______ / **Date:** ______
- **Reviewed by:** ______ / **Date:** ______
