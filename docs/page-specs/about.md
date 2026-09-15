# Page Spec: About (Our Mission & Vision)

Status: **approved** — ready for implementation. Do not redesign; this document is the
source of truth for `page-about-our-mission-vision.php`.

## 1. Page Identity

- **Page ID:** `3726` (existing WordPress Page — must be preserved, never recreated)
- **Slug / canonical URL:** `/about-our-mission-vision/`
- **Title:** "About Our Mission & Vision"

## 2. Sections (in order)

1. **Hero** — page title, intro copy, primary CTA button.
2. **Content/Image Split (Mission)** — copy left, feature image right.
3. **Content/Image Split (Vision)** — feature image left, copy right.
4. **Team Grid** — section heading "Our Leadership Team", dynamic `fs_team_member` query.
5. **CTA Banner** — closing banner "Ready to transform your school leadership?".

## 3. Copy / Content

Production-ready placeholder copy (approved for implementation; not final marketing copy,
but not a "TODO" stub either):

- **Hero heading:** page title ("About Our Mission & Vision"), sourced from the actual
  page's title (`get_the_title()`), not hardcoded — falls back to the literal string
  above only if no page is resolved (e.g. this local environment, where Page 3726
  doesn't exist).
- **Hero intro copy:** "We partner with district and school leaders to build the
  systems, skills, and culture that turn ambitious goals into lasting results." Sourced
  from the page's excerpt (`get_the_excerpt()`) with this text as the fallback.
- **Mission heading:** "Our Mission"
- **Mission body:** "Focused Schools exists to help school systems translate strategy
  into daily practice. We work side-by-side with leaders to strengthen instructional
  systems, build leadership capacity, and create the conditions every student needs to
  thrive."
- **Vision heading:** "Our Vision"
- **Vision body:** "We envision a future where every school system has the leadership
  capacity, coherent systems, and culture of continuous improvement needed to deliver
  excellent outcomes for every student, in every classroom, every year."
- **Team section eyebrow:** "Who We Are"
- **Team section heading:** "Our Leadership Team"
- **CTA Banner heading:** "Ready to transform your school leadership?"
- **CTA Banner description:** "Let's talk about the challenges your district is facing
  and how we can help you build lasting change."
- **CTA Banner button label/URL:** Site Settings `cta_label`/`cta_url`, falling back to
  "Contact Us" / `#` if unset (same fallback pattern as the Home page).

Any additional Gutenberg/native content an editor adds directly to Page 3726's content
editor renders in a reading-width block between the Hero and the Mission section (same
"preserve existing plain content" pattern used on the Home page) — this page spec
defines the five structured sections above, not the entirety of what can ever appear on
the page.

## 4. Dynamic Content Sources

- **Team Grid:** `WP_Query` for post_type `fs_team_member`, ordered by `menu_order`,
  rendered via the existing `team-card.php` component inside `.fs-card-grid` (same
  pattern as the Home page's Team section).
- **Hero CTA / CTA Banner CTA:** `focused_schools_get_setting( 'cta_label' )` /
  `focused_schools_get_setting( 'cta_url' )`.
- **Mission/Vision copy:** static, defined in the template per §3 above — this is
  page-specific approved content, not global business information, so it does not
  belong in Site Settings.

## 5. Images

Mission and Vision each use the Content/Image Split component's `image_id` prop (see
`docs/component-specs.md`). No real attachment IDs exist in this environment's media
library, so both render with `image_id` omitted — the component's existing contract
already handles a missing image gracefully (no broken `<img>`, the media column simply
doesn't render). Real attachment IDs should be substituted once images are uploaded on
the actual site.

## 6. Legacy & Page Preservation

- **Template file:** `page-about-our-mission-vision.php` — WordPress's native
  slug-based template hierarchy (`page-{slug}.php`) is used instead of hardcoding the
  ID anywhere in code. This is the same "preserve via mechanism, not by referencing the
  ID" approach used for the Home page (`front-page.php`). If the real page's slug ever
  changes, this template silently stops applying rather than silently applying to the
  wrong page — that is a deliberate safety property, not a bug.
- **Elementor coexistence:** same runtime detection as `front-page.php` —
  `_elementor_edit_mode === 'builder'` on the queried page renders only its filtered
  `the_content()` output; otherwise the five structured sections render. Unlike
  `front-page.php`, this template can rely on the standard Loop directly (`have_posts()`
  / `the_post()`), since a `page-{slug}.php` template is always scoped to that exact
  page via URL routing — there's no "Settings → Reading" ambiguity like the site's root
  URL has.
- **Known limitation:** identical to the Home page — Page 3726 does not exist in this
  local environment (confirmed via direct database query), so the Elementor-detection
  branch could not be verified against real data. Verify against staging/production
  before deploying.
- **Container widths:** all five main sections use the uniform wide container
  (`.fs-container.fs-container--wide`, 1200px), matching the Home page fix.
