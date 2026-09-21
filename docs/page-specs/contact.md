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
