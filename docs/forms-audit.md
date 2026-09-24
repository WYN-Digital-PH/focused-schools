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


## 8. Rebuilt-Routes Forms Pass (Home, About, Team, Services, Impact Stories, Podcast, Contact, Thanks, Blog)

Scope: identify every form that touches a rebuilt route and confirm the custom frontend does
not change its behaviour. No form system, recipient, action, redirect, CRM or newsletter
automation was added or changed. The live form inventory is `docs/05-forms-integrations.md`
(Elementor Pro widgets; save-to-database + email + redirect to `/thanks/`).

### 8.1 Where each form lands in the rebuilt theme

| Form (live source) | Rebuilt route | What the theme does | Status |
|---|---|---|---|
| Contact (page 1195; two duplicate responsive widgets `bbce0a5`, `d23fb11`) | `/contact/` | `page-contact.php` captures `the_content()` and places it unmodified inside the form card (`form-wrapper.php`, `variant=embedded`). No markup, field, action or redirect is generated or touched. | Layout applies only to form-only content; a full Elementor layout renders untouched (8.3.1). |
| `/thanks/` destination (page 36 live, 24 local) | `/thanks/` | `page-thanks.php` renders the design's confirmation panel; it has no form or redirect logic. Verified locally: `/thanks/` returns 200 and `/thanks` 301s to `/thanks/`. | OK; a live page holding its own message renders untouched (8.3.2). |
| General footer form (Theme Builder template 941, widget `1157a789`) | every route | **Not rendered.** The theme has no Elementor Theme Builder location support (no `elementor_theme_do_location()` in `header.php`/`footer.php`) and the approved footer design has no form, so the custom footer replaces template 941. The `footer-widgets` sidebar (§2) only helps if the form was a widget, but `docs/05` shows it is a Theme Builder template. | **Dropped by decision (8.3.3).** |
| Front-page popup (template 912, email only, save-to-database) | Home | Elementor Pro popups inject through `wp_footer()`, which fires exactly once per page (§3). Not restyled by the theme. | Expected to work; unverified (Elementor absent). |
| Home "Contact" block (`formShortcode` attribute) | Home | Renders a shortcode's output through `form-wrapper.php` if an editor supplies one; empty by default, so it shows a link to `/contact/` instead. Adds no form. | OK |
| Let's Get Started (1356), Careers (3702), Newsletter Sign Up (4798) | not rebuilt | No template; falls to `index.php` (title + `the_content()` inside `.fs-container`). The forms still render but are unstyled by the theme and lose any Elementor full-width/canvas layout. These pages do not exist locally (404). | Unverified; out of rebuilt scope |
| About, Team, Services, Impact Stories, Podcast, Blog (archive/single) | those routes | Contain no forms. Blog has no search form and no comment form (`get_search_form`/`comment_form` are not called). | No form |

**Dead prototype code (removed).** `assets/js/components/site.js` built a "mini contact
form" (`[data-fs-mini-contact]`) and `home.js` handled a `[data-hm-form]` form; both called
`preventDefault()` and showed a success message **without sending anything**. Neither was
enqueued (confirmed against the Home page's loaded scripts) and no template contained their
hooks, so they were never live, but loading either would have swallowed submissions and
reported false success. Both files were deleted in the forms-audit cleanup commit.

**Theme JavaScript does not intercept submits.** Every enqueued script was checked: none
attaches a `submit` listener or calls `preventDefault()` on a form (`podcast-video.js` and
`site-header.js` only handle their own links/buttons). `wp_head`, `wp_body_open` and
`wp_footer` each fire once, which Elementor's scripts, popups and AJAX submit depend on. The
theme does not deregister jQuery or restyle bare `input`/`button` elements globally (only
`:focus-visible` outlines, `style.css`).

### 8.2 The nine requested tests

| # | Test | Result |
|---|---|---|
| 1 | Render form | **Not testable locally** (no Elementor). Verified with a stand-in form using Elementor's class names: it renders inside the card at 375–1440px with no overflow. A real Elementor form must be checked on staging. |
| 2 | Required-field validation | **Not testable.** Error styling exists for Elementor's documented classes and was seen on the stand-in only. |
| 3 | Submit | **Not testable.** |
| 4 | Submission saved | **Not testable.** No `e_submissions` tables exist locally. |
| 5 | Notification email received | **Not testable.** See 8.4. |
| 6 | Redirect | **Not testable.** Structurally, no theme code references any redirect, so the theme cannot alter it. |
| 7 | `/thanks/` remains valid | **Verified:** 200 on `/thanks/`, 301 from `/thanks`. |
| 8 | Mobile behaviour | **Verified for layout only** (stand-in form: rows collapse at 767px, full-width submit, no overflow). Real form behaviour unverified. |
| 9 | Error behaviour | **Not testable** against a real form. |

### 8.3 Decisions

1. **Contact page structure (decided).** The theme layout (hero, form card, aside, link cards)
   applies only when the page's content is nothing but a form: Elementor `form`/`shortcode`
   widgets only, or bare shortcodes / `<form>` markup / an empty page
   (`focused_schools_is_form_only_content()` in `inc/form-content.php`). Shortcodes are
   matched by shape, so a form shortcode still counts when its plugin is inactive. If the page
   holds a full Elementor layout (any other widget type), written copy, or media, its content
   renders on its own as `the_content()` with none of the template's wrappers: no nesting, no
   duplicate hero, and the form and its Elementor scripts are untouched. Elementor-built
   pages render bare in `<main id="content">`; other pages render inside `.fs-container`.
   Tested locally on 12 combinations (empty, shortcode, HTML form, one or two form widgets,
   shortcode widget, form + heading + text widgets, copy, image; Contact and Thanks): all
   chose the expected branch. A saved-template (`template`) widget counts as a layout, since
   its contents can't be inspected.
2. **Thank You page (decided).** Same rule. The design's confirmation panel shows while the
   page holds no content of its own. If the live page 36 holds its message in Elementor or as
   copy, that content renders untouched, so visitors never see two confirmations. To adopt
   the design's panel on production, empty the live page's content (and Elementor mode) at
   migration time.
3. **Footer form (decided: dropped).** The approved Design System v1 footer has no form, so
   Theme Builder template 941 (widget `1157a789`) is intentionally not rendered. Elementor
   Theme Builder header/footer compatibility hooks are deliberately **not** added to the
   theme. Business owners should know general-footer enquiries no longer have an on-page
   form; the Contact page form remains, and existing stored submissions are unaffected. The
   `footer-widgets` sidebar (§2) stays available but empty.

### 8.4 Mail-delivery uncertainty

- **Local:** Mailpit (UI `http://localhost:10012/`, SMTP 10013) is running and empty. Any
  notification sent from this site would land there, not in a real inbox. `admin_email` is
  `dev-email@wpengine.local`, a placeholder that predates this project.
- **Production:** unproven. `docs/05` found no SMTP plugin and no mail-delivery trace; the
  Akeeba config references PHP mail settings, which does not prove the current transport.
  Recipient addresses are held in Elementor's form settings and were not read or changed.
  Notification delivery, sender/From address, SPF/DKIM/DMARC alignment and spam-folder
  placement all need confirming from a real send.

### 8.5 Staging test protocol (needs Elementor Pro active and a real inbox)

1. Confirm what page 1195 and page 36 contain in Elementor (form only, or a full layout):
   that decides which branch of 8.3.1–8.3.2 each page takes. Expect page 36 (message text)
   to render untouched, and page 1195 to take the theme layout only if it holds just the form.
2. Load `/contact/` at desktop, 768 and 375px: exactly one form visible at each width
   (the two Elementor copies are responsive-hidden), all fields present, no console errors.
3. Submit empty: required fields block submission and show inline errors.
4. Submit a valid test message (unique subject text): the button shows its sending state and
   the browser lands on `/thanks/` with the design panel.
5. Elementor → Submissions: the entry exists with every field value.
6. The notification arrives at the configured recipient: record arrival time, From address,
   Reply-To, and whether it went to spam. Repeat once from a different network/mail provider.
7. Force an error (temporarily offline, or an invalid email past client checks): the error
   message appears, the typed values are kept, and nothing is saved twice.
8. Repeat 4–6 for the popup form, Let's Get Started, Careers
   and Newsletter Sign Up.
9. Confirm GA4 (`G-1MG2HCZGRN`, injected by Simple Custom CSS and JS) fires once, not twice.

### 8.6 Files changed in this pass

- `docs/forms-audit.md` (this section).
- `inc/form-content.php` (new) and `functions.php` (one `require_once`): form-only detection.
- `page-contact.php`, `page-thanks.php`: pass-through branch for non-form-only content.
- `assets/js/components/site.js`, `assets/js/components/home.js`: deleted (unused fake forms).
- No form settings, recipients, actions, redirects or Theme Builder hooks were changed or added.
