# Forms & Active Integrations Audit

Status: **audited, one real gap found and fixed**. Most verification steps in this task's
own checklist (§5) **could not be performed** — Elementor and Elementor Pro are not
installed in this local environment, no real forms of any of the 6 kinds exist here, and
none of their actual routes/implementations are visible to this environment. This
document reports what was genuinely verified versus what remains open, rather than
asserting untested claims.

## 1. The 6 Forms — Routes and Code Touchpoints

| Form | Known route | Code touchpoint in this theme | Finding |
|---|---|---|---|
| Contact Page Forms | `/contact/` | `page-contact.php` → `form-wrapper.php` | Already built (prior task). Passes `the_content()` through unmodified on both the Elementor branch and the fallback branch — no form markup is generated, altered, or assumed. |
| Footer Contact Form | Site-wide footer | `site-footer.php` | **Real gap found, now fixed** — see §2. |
| Let's Get Started | Unknown | None | No page/template exists for this. Likely a separate Elementor popup or page with no visibility from this environment. |
| Careers | Unknown | None | No `/careers/` page exists in this local database. Not governed by any custom theme template — falls back to WordPress's default hierarchy (`index.php`, or Elementor Theme Builder if a "Page" condition is active — see §4). |
| Newsletter Sign Up | Unknown | None | Same as Footer Contact Form if widget-based (§2 applies); otherwise no visibility. |
| Front-page Popup | Site-wide | None directly | Elementor Pro Popups inject via the `wp_footer` hook, not a template file. Confirmed this hook fires correctly, exactly once, on every page (see §3). |

## 2. Real Gap Found: No Footer Widget Area Existed

This theme had **never called `register_sidebar()`** — no widget area of any kind was
registered anywhere. `site-footer.php` rendered only navigation, social links, and
copyright text.

**Why this matters:** if the real site's Footer Contact Form or Newsletter Sign Up is
implemented as a widget (a Text/HTML/Elementor widget assigned to a footer sidebar via
Appearance → Widgets — a very common WordPress pattern), migrating to this theme would
have **silently dropped it entirely**. WordPress does not error when a widget is
assigned to a sidebar ID that doesn't exist in the active theme — it simply never
renders, with no warning anywhere. This is exactly the kind of quiet breakage this audit
was meant to catch.

**Fix applied:**
- `inc/setup.php` — registered a `footer-widgets` sidebar (standard `register_sidebar()`,
  the same category of change as the nav menu locations already registered — not a new
  form engine).
- `site-footer.php` — renders it via `is_active_sidebar()` / `dynamic_sidebar()`,
  positioned above the social links/copyright. Renders **nothing** unless a widget is
  actually assigned — no content was guessed, invented, or hardcoded.
- `form-wrapper.css` — extended its existing generic-input/Elementor-class selectors to
  also cover `.fs-site-footer__widgets`, so a form dropped into this sidebar gets the
  same consistent styling as the Contact page form, via the same CSS rules, not new
  logic.

**Verified live** (test-only, not committed): added a temporary Custom HTML widget
(a simple email input + submit button, simulating a newsletter-style form) to the new
sidebar, confirmed it rendered correctly — title, styled input, styled button, correct
position in the footer, zero console errors — then removed it, leaving the registered
sidebar empty and unused pending the real content.

## 3. Confirmed: Global Hook Integrity

`wp_head()`, `wp_body_open()`, and `wp_footer()` are each called **exactly once**, only
in `header.php`/`footer.php`. Every template in this theme routes through
`get_header()`/`get_footer()`, so these hooks fire consistently site-wide. This matters
because Elementor Pro's global mechanisms — Popups, Theme Builder header/footer
overrides, tracking script injection — typically rely on exactly these hooks. No
duplication, no missing calls, confirmed by direct grep across every theme template file.

## 4. What This Audit Cannot Cover — Elementor Pro's Theme Builder

Same caveat documented in `docs/blog-coexistence.md` §4, and it applies here with equal
force: Elementor Pro's **Theme Builder** is a separate, site-wide template-override
system, independent of the per-post `_elementor_edit_mode` check used throughout this
theme's page templates. If a Theme Builder condition manages the site header, footer, a
popup, or a specific page (e.g. Careers) globally, it can render independently of
anything in this theme's own files — regardless of what `header.php`/`footer.php`/a page
template does or doesn't do. **This cannot be inspected or tested here, since Elementor
is not installed in this local environment.** Before relying on any finding in this
document for the real site, confirm directly against the actual Elementor Pro
configuration on staging/production.

## 5. Verification Steps — What Could and Could Not Be Done

| Step (as specified) | Result |
|---|---|
| Required field validation / error messaging under custom theme CSS | **Partially addressed, not verified live.** Defensive CSS for Elementor's documented `.elementor-message`/`.elementor-message-danger`/`.elementor-message-success`/`.elementor-field-group.elementor-error` classes already exists (added in the Contact page task, extended to the footer widget area in §2) — but with no real Elementor form to trigger actual validation states, this remains best-effort and unverified. |
| Form submission saves to DB (Elementor → Submissions) | **Cannot be tested.** Elementor Pro is not installed; there is no Submissions feature to check in this environment. |
| Submission redirects to `/thanks/` | **Cannot be tested**, for the same reason. Structurally confirmed instead: no code in this theme implements, intercepts, or references any redirect logic — `page-contact.php`'s docblock explicitly states the redirect is configured entirely within the form's own settings, external to any template file. Nothing here could break it, but nothing here can prove it still works either. |
| Analytics scripts not duplicated across templates | ✅ **Verified.** Full-theme and full-plugin grep for `gtag`, `google-analytics`, `googletagmanager`, `analytics`, `fbq` returned zero matches anywhere in this codebase. No tracking scripts exist in custom theme/plugin code at all — if analytics are present on the real site, they come entirely from Elementor/a separate plugin/a Site Settings-injected snippet, none of which this theme duplicates. |
| Document mail-delivery constraints | See §6. |

## 6. Mail Delivery in This Local Environment

Local by Flywheel bundles **Mailpit** for this site (web UI on port 10012, SMTP
catch-all on port 10013) — confirmed reachable and already containing a real message: a
WordPress core "Your Site is Experiencing a Technical Issue" recovery email (correlates
with the transient PHP parse-error incident noted in earlier tasks' error-log checks,
already resolved). This is concrete, existing proof that `wp_mail()` calls made through
the actual site stack correctly reach Mailpit here, rather than attempting real outbound
delivery.

**Constraint for form-notification testing:** any Elementor form notification email sent
in this local environment will be caught by Mailpit, not delivered to a real inbox —
expected and correct for local development, but means **email content/formatting/from-
address must be verified by checking Mailpit** (`http://localhost:10012/` while the Local
site is running), not by checking a real mailbox. This applies equally to Elementor form
notifications as it does to WordPress's own core emails.

**Separately noted, not a code issue:** `admin_email` in this environment is set to
`dev-email@wpengine.local` — an unusual, migration-artifact-looking value (not a real
address) that predates this project's work. Worth flagging to confirm the real site's
`admin_email` is correctly set before relying on any admin-facing notification email.

**A direct CLI `wp_mail()` test was attempted and failed** — not a WordPress issue, but
an environment one: bootstrapping `wp-load.php` via Herd's PHP CLI resolves
`wp-config.php`'s `DB_HOST=localhost` differently than Local's own PHP-FPM process does
(this project has used direct `mysqli` connections on Local's actual MySQL port, 10017,
throughout, for exactly this reason). The existing real email in Mailpit already answers
the relevant question, so this wasn't pursued further.

## 7. Files Changed

- `inc/setup.php` — registered `footer-widgets` sidebar
- `template-parts/components/site-footer.php` — renders it conditionally
- `assets/css/components/site-footer.css` — widget container spacing
- `assets/css/components/form-wrapper.css` — extended existing selectors to cover the
  footer widget area

No form engine, CRM integration, or newsletter automation was built, per the task's
explicit constraints. No email recipients, actions, or redirect URLs were touched.
