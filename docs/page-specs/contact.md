# Page Spec: Contact & Thank You

Status: **approved** — ready for implementation. Written from the task's own explicit
requirements plus the established page structure, since no separate design spec was
available. Source of truth for `page-contact.php` and `page-thanks.php`.

## 1. Page Identity

- **Page IDs:** not provided in the task (like Podcast). Not a blocker — both templates
  apply via WordPress's native `page-{slug}.php` hierarchy, which never references an ID.
- **Slugs / canonical URLs:** `/contact/` and `/thanks/`
- **Template files:** `page-contact.php`, `page-thanks.php`

## 2. Form Preservation — the central design constraint

The actual contact form (Elementor Pro, per the project plan) is **never generated,
modified, or replaced** by these templates. Two layers, in priority order:

1. If the real Contact page is Elementor-built (`_elementor_edit_mode === 'builder'`),
   `page-contact.php` renders only `the_content()` — Elementor's own saved output,
   completely untouched — exactly like every other page template's Elementor branch.
2. Otherwise, `the_content()` (whatever shortcode/widget/block the page actually
   contains) is passed unmodified into the `form-wrapper.php` component's `inner` prop,
   purely for consistent visual styling (spacing, input/button styling matching the
   design tokens). This makes no assumption about which form plugin is in use.

**Redirect to `/thanks/`** is configured entirely within the Elementor form's own
"Actions After Submit" settings — external to any theme file. `page-thanks.php` does not
implement or verify this redirect; it only provides the destination page's presentation.

## 3. Contact Page Sections (non-Elementor fallback path)

1. **Hero** — page title, intro copy. Data-driven (`get_the_title()`/`get_the_excerpt()`).
2. **Contact Info block** — new `contact-info.php` component, Site Settings-driven
   (`business_name`, `phone`, `email`, `address`) — see §4.
3. **Form** — `the_content()` wrapped in `form-wrapper.php`.

No closing CTA Banner — deliberately. The page's entire purpose already is the call to
action; a second "contact us" banner below the actual form would be redundant.

## 4. Contact Info Component

New `template-parts/components/contact-info.php`, no args (pulls directly from
`focused_schools_get_setting()`, same no-args pattern as `site-footer.php`). Renders
inside a semantic `<address>` element: business name, formatted address (multi-line,
`nl2br()`), a `tel:` link (digits/`+` only, stripped of formatting for the `href`) and a
`mailto:` link. Omits itself entirely if all four Site Settings fields are empty
(plugin-inactive graceful degradation, same as every other Site-Settings-dependent
component).

## 5. Thank You Page Sections

1. **Hero-style confirmation** — page title/excerpt (data-driven, same fallback
   pattern), no CTA button (nothing to click through to from a confirmation page).
2. `the_content()` — any additional native content an editor adds.

No dynamic data beyond the page's own title/excerpt/content.

## 6. Accessibility

- Form input `:focus-visible` styling is already global (`style.css`'s base rule already
  targets `input`/`select`/`textarea`) — confirmed, not re-implemented.
- Error-message styling for Elementor's own validation output (`.elementor-message`,
  `.elementor-message-danger`, `.elementor-field-group.elementor-error` or similar) is
  best-effort defensive CSS in `form-wrapper.css` — **could not be verified live**,
  since Elementor is not installed in this local environment. Verify against
  staging/production, where a real Elementor form exists.
- Keyboard navigation through the surrounding page (skip link, nav, contact info links)
  uses the same site-wide patterns already verified on every other page.

## 7. Container Widths

Hero and Contact Info use the uniform wide container
(`.fs-container.fs-container--wide`, 1200px). The form wrapper itself keeps its existing
narrower reading-width container (`form-wrapper.php`'s own internal `.fs-container`,
720px) — appropriate for a form's input column width, unlike the wide
grid/visual sections elsewhere.

## 8. Exact-Fidelity Pass Against `Focused Schools Contact.dc.html`

§3 and §7 above describe the earlier scaffold and are superseded where they differ. The
`.dc` source now exists; the pages were rebuilt against it. Sections: hero, form + aside,
"Get to know how we work first" link cards. No closing CTA.

**Page identity.** Local Contact is page ID 23 and Thank You is 24; the live IDs (1195 for
Contact) are never referenced. Templates resolve by slug, so ID, slug and URL are preserved.

**Redirect / success (decision).** The `.dc` shows an in-place success with no redirect; the
project rule is to keep the existing `/thanks/` redirect, so it is unchanged (configured in
the Elementor form's own "Actions After Submit"). The design's success presentation is applied
on `/thanks/` instead: same hero and aside as `/contact/`, with the confirmation panel
(check mark, "Message sent", "Thank you — we have your message.", two-business-day copy,
phone from Site Settings, "While you wait" links) where the form would be.

**Contact page.**
- Hero: `rail-text` gained optional `heading_tag` (h1), `heading_size` (68), `heading_gap`,
  `padding_top` and `body_max_ch`, all defaulting to the previous values (Home/About/Team
  verified unchanged). H1 steps 68 / 46 / 34px at 1240 / 767. Eyebrow is the page title,
  the intro is the page excerpt (design copy as fallback).
- Form card: white 16px-radius card with the "Send us a message" header. `form-wrapper`
  gained a `variant: embedded` that renders only the styled content region and restyles the
  form to the `.dc` geometry (48px inputs, 8px radius, 12px labels, 2-column rows collapsing
  at 767px, coral pill submit, Elementor error/success states). The default variant (Home
  contact section, footer widgets) is untouched.
- Aside (`contact-aside`, new): "Reach us directly" (teal card; email, phone and LinkedIn
  from Site Settings via a new `contact-info` layout `direct`), "What happens next" (three
  steps) and "Credentials" (membership badge). Sticky at 118px above 1240px; stacks after
  the form at 1240px.
- Link cards (`link-cards`, new): Impact Stories / Services / Podcast / Team, 4 / 2 / 1
  columns at 1023 / 767.
- The theme layout applies only when the page's content is just a form (Elementor `form` /
  `shortcode` widgets, bare shortcodes, `<form>` markup, or empty). A full Elementor layout,
  written copy or media renders untouched as the page's own content, with no wrappers
  (`focused_schools_is_form_only_content()`, `inc/form-content.php`; see
  `docs/forms-audit.md` §8.3). `/thanks/` follows the same rule: the confirmation panel shows
  only while the page holds no content of its own.

**Not implemented (owned by the form plugin).** The `.dc`'s error-summary panel, message
counter, "Sending…" label and in-place success are client-side behaviours of a mock form;
Elementor owns validation and submission state, so only its own inline errors/messages are
styled. The consent checkbox, role, phone, district and interest fields exist only if the live
form has them — the theme cannot add fields. Interest options in the `.dc` come from the
Services CPT; that select is an Elementor field and is not driven from the theme.

**Copy held in the theme (translatable strings, not editable in wp-admin):** the hero heading,
form helper line, reply-time note, three steps, credentials text ("Massachusetts DESE Approved
Provider … since 2000"), the reach-card copy and the success copy. The credentials line is
business information taken from the design; if it should be editable it needs a Site Settings
field.

**Known differences.** The design's reply-time note sits beside the submit button; here it sits
under the form because the button is inside the plugin's markup. The shared hero mobile tier is
720px elsewhere but 767px for this page's hero (per the `.dc`).

**Verified locally** with a stand-in form using Elementor's class names (restored afterwards):
no overflow at 375–1440px (8 widths, both pages), columns 2 → 1 at 1241px, form rows 2 → 1
at 767px, link cards 4 / 2 / 1, tab order runs skip link → nav → every field → submit → aside
links → link cards, computed input/button/label values match the `.dc`, no console errors,
PHPCS clean, no PHP warnings. **Not verified:** a real Elementor Pro form (not installed
locally): its markup and class names, validation messages, submission, and the redirect to
`/thanks/`. This needs a pass on staging or production.
