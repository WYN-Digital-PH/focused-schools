# Audit Report

Status: **placeholder** — sanitized template, no audit has been performed yet.

## 1. Purpose

This document records findings from periodic audits of the `focused-schools` codebase
(security, performance, accessibility, code quality). No sensitive data, credentials, or
production-specific details should ever be recorded in this file — sanitize any values
before committing.

## 2. Audit Log

| Date | Type | Scope | Summary | Status |
| ---- | ---- | ----- | ------- | ------ |
| —    | —    | —     | —       | —      |

## 3. Findings Template

Use this template for each finding:

```
### Finding: <short title>

- **Severity:** low / medium / high
- **Area:** theme / plugin / config / infrastructure
- **Description:** (sanitized — no secrets, credentials, or PII)
- **Recommendation:**
- **Status:** open / in progress / resolved
```

## 4. Guardrails for This File

- Never paste raw credentials, API keys, database contents, or personal data here.
- Redact URLs, IPs, or environment details that are not needed to understand the finding.
- Keep entries factual and scoped to the codebase — no client-identifying data.

## 5. Technical Audit Verification Checklist (Live / Staging)

Status: **in progress** — fill in as access to live/staging environments is confirmed.
Use sanitized placeholders for actual values (no real credentials, API keys, or PII).

### 5.1 Environment & Server

- [ ] **WordPress core version** — Live: `___` / Staging: `___`
- [ ] **PHP version** — Live: `___` / Staging: `___`
- [ ] **SiteGround caching behavior** (SG Optimizer / Dynamic Cache / Memcached) — enabled/disabled, notes: `___`
- [ ] **SiteGround security settings** (firewall, brute-force protection, SSL/HTTPS enforcement) — notes: `___`
- [ ] **Redirect behavior** (http → https, www vs non-www canonicalization) — verified: `___`
- [ ] **Staging/production domain isolation** (staging not publicly indexed, correct primary domain resolves) — notes: `___`

### 5.2 Core Dependencies

- [ ] **Active theme(s)** — confirmed: `___`
- [ ] **Active plugin list** — captured (name + version): `___`
- [ ] **Elementor** — active/inactive, version, license status: `___`
- [ ] **Elementor Pro** — active/inactive, version, license/expiration status: `___`
- [ ] **Yoast SEO** — active/inactive, behavior verified (meta output, XML sitemap, redirects module): `___`

### 5.3 Templates & Content

- [ ] **Blog archive template** — rendering source confirmed (theme template file / Elementor template / plugin-generated): `___`
- [ ] **Blog single post template** — rendering source confirmed: `___`
- [ ] **Buzzsprout integration** — embed method confirmed (shortcode, plugin, manual HTML embed): `___`
- [ ] **Podcast page/template** — rendering source and data feed confirmed: `___`

### 5.4 Integrations & Tracking

- [ ] **Form submission targets** — destination confirmed per form (email / CRM / webhook): `___`
- [ ] **Form confirmation behavior** — confirmed per form (redirect page / inline message): `___`
- [ ] **Analytics script injection** — method confirmed (theme header, plugin, Google Tag Manager) and IDs sanitized in notes: `___`
- [ ] **Tracking scope check** — confirmed tracking scripts fire appropriately on production only (not staging), or vice versa as intended: `___`

## 6. Notes

No audits have been run yet. This file exists as scaffolding for future audit results.
