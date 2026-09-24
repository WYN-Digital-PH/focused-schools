# Contact — Page Design Specification

**Page:** Contact
**Template:** `page-contact.php`
**Design system:** Focused Schools DS v1
**Source mockup:** `Focused Schools Contact.dc.html`
**Status:** Approved for development
**Last updated:** September 17, 2026

---

## 1. Overview

**Same form, same fields, a page that earns the send.** The existing form's purpose and required
fields are preserved exactly. What changes is everything around them: a compact hero instead of a
hero image, a sticky aside that answers "who am I writing to and when will they reply?", and a fully
specified validation and success system that the old page did not have.

Eleven blocks. Nine reused, one extended (the form field), one new (the success panel).

### Section hierarchy

| # | Section | Ground | Purpose |
|---|---|---|---|
| 01 | Compact hero | White | Label rail + H1 + one paragraph that sets the reply expectation. **No 21:9 media**, so the form starts high on the page. |
| 02 | Form + sticky aside | Paper `#f5f6f8` | The page's whole job. Form left at `1fr`, aside right at 400px holding direct contact, what-happens-next, and credentials. |
| 03 | Other ways to connect | White | Four cards for readers who are not ready to write yet. Each accent is its own section's color. |
| 04 | Footer | Teal | Reused verbatim; its CTA points at the form rather than off-page. |

**No hero photograph.** Every other page opens on 21:9 media; a contact page should open on the
form. The compact label-rail hero keeps the family resemblance without pushing the fields below the
fold. This is a deliberate, flagged departure.

### Component map

| Component | Source | Status |
|---|---|---|
| Sticky header / footer | Homepage, verbatim; Contact added to nav and active | Reused |
| Compact label-rail hero | Homepage belief rail, used as a page header | Reused |
| Form shell (white card) | Homepage contact form shell, 16px radius | Reused |
| **Form field** | **Homepage input + label row, help text, error state, select** | **Extended** |
| Error summary panel | DS v1 alert panel, coral-family red | Reused |
| **Success panel** | **No precedent — new state** | **New** |
| Teal aside card | Homepage teal band + mark watermark, card-scaled | Reused |
| Numbered steps list | Homepage commitments list, compressed | Reused |
| Credential / badge row | About page credential row | Reused |
| Reach cards | DS v1 link card with accent bar | Reused |
| Primary / secondary pills | DS v1 buttons, unchanged | Reused |

---

## 2. Hero

- 264px label rail (28px mark + "Contact us") + `1fr` content. Padding 72px / 80px.
- H1 68px / 1.0 / −0.03em / 700 teal, max 18ch: "Let's start a conversation."
- Body 19px / 1.65 `#51536a`, max 62ch, setting the reply expectation in the same breath as the ask.

---

## 3. Form column

### 3.1 Form shell

White card, 16px radius, 1px `#e3e9eb`, shadow-1, padding **44px 48px 48px**.
Header: H2 32px / 1.12 / 700 "Send us a message", helper line 15px / 1.65 explaining the required
marker, 32px padding + 1px `#eef1f2` rule, 34px to the first field.

### 3.2 Field inventory

| Field | Type | Required | Autocomplete / inputmode |
|---|---|---|---|
| Full name | text | **Yes** | `name` |
| Role / title | text | Optional | `organization-title` |
| Email | email | **Yes** | `email` / `inputmode="email"` |
| Phone | tel | Optional | `tel` / `inputmode="tel"` |
| District or school | text | **Yes** | `organization` |
| What can we help with? | select | Optional | — |
| How can we help? | textarea (6 rows) | **Yes** | — |
| Marketing consent | checkbox | Optional, **unchecked by default** | — |

Layout: three paired rows (`1fr 1fr`, 24px gap) — name/role, email/phone, district/interest — then
the full-width textarea, then the consent panel, then the submit row. Vertical group gap **26px**.

Select options are the three Services lanes plus "Not sure yet" and "Something else", read from the
Services CPT so the two pages cannot drift. Custom chevron at 16px right, `pointer-events: none`,
native `appearance` reset.

### 3.3 Field styling

One field definition, six states. Geometry comes straight from the approved homepage form.

| Property | Value |
|---|---|
| Input height | 48px |
| Radius | 8px |
| Border | 1px `#e0e6e9` |
| Horizontal padding | 14px |
| Input text | 15px / `#005C6D` |
| Placeholder | `#9aa4a8` |
| Label | 12px / 700 / 0.06em |
| Label → field | 10px |
| Optional tag | 9px / 0.12em caps `#9aa4a8` |
| Help / error text | 13px / 1.5 |
| Field → message | 8px |
| Textarea rows / padding | 6 / 13px 14px, `resize: vertical` |
| Focus halo | 3px `rgba(10,150,203,.22)` |
| Error border / ground | `#C0392B` / `#fdf7f6` |
| Required asterisk | `#C0392B` |
| Checkbox | 20px, `accent-color: #005C6D` |

### 3.4 Field states

| State | Treatment |
|---|---|
| **Default** | 1px `#e0e6e9` hairline on white, 48px tall, 8px radius |
| **Placeholder** | Text `#9aa4a8` — an example, **never a substitute for the label** |
| **Hover** | Border darkens to `#c8d2d6` at 160ms. Nothing else moves — hover is not the important state on an input |
| **Focus** | Border → teal `#005C6D` plus a 3px `rgba(10,150,203,.22)` halo. The input equivalent of the site's cerulean focus ring |
| **Error** | Border `#C0392B`, ground `#fdf7f6`, message in `#C0392B` at 13px, `aria-invalid="true"`, `aria-describedby` pointing at the message |
| **Error + focus** | Keeps the red border **and** adds the cerulean halo, so focus is still distinguishable inside an error state |
| **Disabled** | Ground `#f5f6f8`, text 55%, `cursor: not-allowed`. Used only while sending |

### 3.5 Validation rules

- **Validate on blur, never on keystroke.** Nobody should be told their email is invalid while they
  are still typing it.
- **Re-validate on input only once a field is already in error** — so the error clears the moment it
  is fixed, giving immediate reward for the correction.
- **On submit:** validate everything, render the error summary, move focus to **the summary** (not to
  the first field), and let the reader choose where to go.
- **Messages name the fix, not the failure:** "Enter a valid email address so we can reply" — not
  "Invalid input."
- **Never color alone:** every error carries a border change, a tinted ground, an icon in the
  summary, and text. Passes WCAG 1.4.1.
- **Required:** name, email, district/school, message. Role, phone, interest, and consent are
  optional — matching the current form's purpose, plus routing fields.
- **Email:** standard shape check only; no MX or disposable-domain blocking.
- **Message minimum 20 characters**, counter appears past 20, **soft limit 1500**. Never blocks
  typing.
- **`novalidate`** on the form so our messages replace the browser's inconsistent bubbles — but keep
  the real `type` attributes for mobile keyboards.
- Server-side validation still applies; the client rules are a courtesy, not the gate.

### 3.6 Error summary panel

- Ground `#fdf3f1`, 1px `#f0c8c1`, 12px radius, 20/22px padding, **30px above the first field**.
- 24px `#C0392B` circle with a white "!" (`aria-hidden`), then a 15px / 700 headline in `#8E2A1E`
  naming the count, then a list of underlined in-page links to each failing field.
- `role="alert"`, `tabindex="-1"`, receives focus on submit failure, scrolls into view with a 106px
  offset for the sticky header.

### 3.7 Submit row & button states

| State | Treatment |
|---|---|
| **Default** | Coral `#DD6237`, 48px, 999px radius, 13px / 700 / 0.09em uppercase white, "Send Message →" |
| **Hover** | `filter: brightness(.92)` at 160ms (≈`#C4522C`). No rise |
| **Active** | `scale(.99)` |
| **Focus** | 3px `#0A96CB` ring at 2px offset |
| **Sending** | Label → "Sending…", `aria-busy="true"`, disabled, 45% opacity, `cursor: not-allowed`. **No spinner-only states** |
| **Disabled** | Same as Sending; the button is never pre-disabled before a submit attempt |

Beside the button: the reply-time promise at 13px / 1.6, max 34ch — "We reply within two business
days. We never share your information."

### 3.8 Success panel (new)

- **Success replaces the form in place** — no redirect. The page keeps its URL, the aside stays, and
  the reader is not thrown to a bare thank-you page.
- White card, 16px radius, 1px `#e3e9eb`, shadow-1, padding **56px 52px 60px**.
- 64px teal circle with a white check (`aria-hidden`), 32px below; eyebrow "Message sent"; H2 42px /
  1.06 / 700 max 22ch; body 17px / 1.75 max 58ch restating the two-day promise with the phone number
  as an underlined escape hatch.
- A 1px `#eef1f2` rule, then "While you wait" and two secondary pills — Impact Stories, Podcast.
  **A dead end after a conversion is a wasted moment.**
- `role="status"` live region; **focus moves to it**; the heading states what happened in plain words.
- **Fire the analytics conversion on render of this panel, not on button click.**
- **Autoresponder copy should match this panel exactly**, so the email confirms what the page said.
- Appears without entrance motion either way, since it follows an action rather than a scroll.

### 3.9 Submit failure

Network or server failure: the form is **preserved with every value intact**, and a `role="alert"`
panel offers **Try again** plus the mailto address. **Never lose what they typed.**

---

## 4. Aside column

Sticky at `top: 118px`, 400px wide, three cards at 20px gap.

1. **Teal contact card** — `#005C6D`, 16px radius, padding 36/34/38px, brand mark watermark at **8%**
   bottom-right (clipped, `aria-hidden`). Lime eyebrow "Reach us directly", then three rows (Email,
   Phone, LinkedIn): 10px / 700 / 0.12em uppercase key in `rgba(255,255,255,.66)` above a 19px / 700
   value, separated by `rgba(255,255,255,.22)` hairlines. Hover: value → lime `#A7CC14`.
2. **What happens next** — white card, padding 32/34/34px. Eyebrow, then a three-row numbered list
   (`30px + 1fr`, 16px gap, `#eef1f2` rules) with coral numerals: *We read it · We reply within two
   business days · We talk it through.*
3. **Credentials** — white card. 92px chamber badge beside a 14px / 1.65 line naming the MA DESE
   Approved Provider status and "partnering with districts nationwide since 2000."

---

## 5. Other ways to connect

- Header: eyebrow "Not ready to write yet?" + H2 58px / 1.02 / 700 max 18ch, 48px to the grid.
- Four cards at `repeat(4, 1fr)`, 20px gap, **220px minimum height**, padding 30/32/32px, white, 16px
  radius, 1px `#e3e9eb`, shadow-1, `justify-content: space-between`.
- Each card opens with a **26×3px accent bar in its destination's own color** — Impact Stories
  cerulean `#0A96CB`, Services coral `#DD6237`, Podcast raspberry `#BC3E8C`, Team lime `#A7CC14` —
  then a 24px / 700 title, a 15px note, and a footer CTA line with a coral arrow.
- Whole card is the link. Hover: shadow-2, 2px rise, border → teal at 240ms.
- Rendered from a capped page repeater (title, note, CTA label, link, accent).

---

## 6. Responsive behavior

Breakpoints match the rest of the site: **1240 / 1023 / 900 / 767**.

### Desktop ≥1241px

Form `1fr` + aside 400px at 80px gap; aside sticky at `top: 118px`. Paired fields two-up. Reach
cards 4-up. The form's first fields sit above the fold at 1440×900.

### ≤1240px

- **Contact grid collapses to one column**, 56px gap. The aside un-sticks and takes `order: 2`; the
  form takes `order: 1` — **the fields always come before the reference material.**
- Display 60px / H1 46px; label rails collapse to one column at 40px gap.

### ≤1023px

Primary nav + Menu label hide. Reach cards → 2-up. Footer → 2-up.

### ≤767px

- **Order:** hero → form → aside → other ways to connect.
- **All paired fields go single-column** with 22px row gaps. **No two-up fields on a phone, ever.**
- **Fields stay 48px tall and 15px** — 16px-equivalent input sizing prevents iOS zoom-on-focus;
  **never reduce the input font size on mobile.**
- **Correct keyboards:** `inputmode="email"`, `inputmode="tel"`, plus full `autocomplete` tokens so
  the OS can fill name, email, phone, and organization.
- **Submit button full-width, 48px**, with the reply-time line centered beneath it rather than beside
  it (`data-submit-row` switches to a stretched column at 16px gap).
- **Error summary** sits directly above the first field and scrolls into view with a 106px offset for
  the sticky header.
- **Form shell padding** 48px → **28px 24px 32px**; section padding 96px → 64px.
- **Reach cards** 1-up; each keeps its 220px minimum height and full-card tap target.
- **Success panel** keeps its 64px check mark; heading drops to 30px and the two follow-up buttons
  stack full-width.
- Type step: H1 34px / H2 36px — the DS v1 mobile step, no new sizes.

---

## 7. Spacing specification

| Measure | Value |
|---|---|
| Container / gutter | 1400 / 24px |
| Hero padding | 72px / 80px |
| Form section padding | 96px / 112px |
| Form / aside columns | 1fr + 400px, 80px gap |
| Form shell padding | 44px 48px 48px |
| Form header → fields | 34px |
| Field group gap (vertical) | 26px |
| Paired field gap | 24px |
| Error summary → first field | 30px |
| Consent panel padding | 18px 20px |
| Submit row gap | 24px |
| Aside card gap | 20px |
| Aside card padding | 32–36px |
| Sticky aside offset | `top: 118px` |
| Success panel padding | 56px 52px 60px |
| Reach card min-height | 220px |
| Mobile section padding | 64px |
| Mobile form shell padding | 28px 24px 32px |
| Anchor scroll-margin | 106px |

---

## 8. Visual styles

**Type:** DM Sans only (400 / 500 / 700).

**Color:** teal `#005C6D` (ink, aside card, field focus) · coral `#DD6237` (submit, step numerals,
card arrows, active nav) · coral hover `#C4522C` · lime `#A7CC14` (aside eyebrow + link hover) ·
cerulean `#0A96CB` (focus ring and halo) · error `#C0392B` with ground `#fdf7f6` and summary ground
`#fdf3f1` / border `#f0c8c1` / ink `#8E2A1E` · paper `#f5f6f8` · field hairline `#e0e6e9` · hairlines
`#e3e9eb` / `#eef1f2` · placeholder `#9aa4a8` · body gray `#51536a`.

Error red `#C0392B` is a coral-family red — deliberately adjacent to the brand coral so the error
state reads as part of the system rather than a browser default.

**Shape & elevation:** 8px radius on inputs; 12px on the consent panel, error summary, and inner
panels; 16px on cards; 999px on buttons. Shadow-1 `0 1px 2px rgba(0,92,109,.08)`, shadow-2
`0 12px 28px rgba(0,92,109,.12)`.

---

## 9. Image guidance

- **This page is deliberately image-light.** One photograph competing with a form only slows the
  send. The teal aside card and the mark watermark carry the brand instead.
- **Chamber badge** is the only required raster: 92px wide in the aside, min 276px source for retina,
  transparent PNG or clean white ground.
- **Logo mark** at 8% opacity, bottom-right of the teal aside card, clipped — the same watermark
  treatment as the homepage footer and teal bands.
- **If a photograph is required later,** put it in the aside as a 4:5 figure above the teal card —
  never behind the form or as a 21:9 hero. A real team moment, not a headset stock photo.
- **No map embed.** Focused Schools works nationally and on site with districts; a pinned office map
  implies walk-in visits. Add one only if there is a genuine visitor address.
- Decorative marks get `alt=""`; the badge's alt text carries its membership year.

---

## 10. Interaction states summary

| Element | Default | Hover | Focus / active |
|---|---|---|---|
| Field | 1px `#e0e6e9` | Border → `#c8d2d6`, 160ms | Border teal + 3px `rgba(10,150,203,.22)` halo |
| Field (error) | `#C0392B` border, `#fdf7f6` ground | — | Red border **plus** cerulean halo |
| Submit | Coral | `brightness(.92)` | `scale(.99)`; ring; Sending = 45% opacity, disabled |
| Secondary pill | White, 1px `#d3dde1` | Border → teal, ground → paper | Ring |
| Aside contact link | White value | → lime `#A7CC14`, 160ms | Ring; rows keep translucent hairlines |
| Reach card | Shadow-1 | Shadow-2, 2px rise, border → teal, 240ms | Whole card is the link; ring |
| Checkbox | 20px, `accent-color: #005C6D` | — | Label fully clickable via `for` |
| Active nav item | Contact: 700, 2px coral underline at 8px offset | — | `aria-current="page"` |

**Focus-visible, everything:** 3px `#0A96CB` at 2px offset. **Never removed — on a form page this is
not optional.**

**Entrances:** fade + 28px rise, 700ms, once; Intersection Observer at `threshold: 0.08`,
`rootMargin: 0 0 -6% 0`, with a 900ms safety timeout restoring opacity. Disabled under
`prefers-reduced-motion`. The success panel appears without motion either way.

---

## 11. Developer implementation notes (WordPress)

- **Keep the existing form plugin.** Whatever handles the current form — Gravity Forms, WPForms, CF7
  — stays, along with its notification routing, spam protection, and entry storage. **Do not rebuild
  the handler; restyle it.**
- **Restyle via a scoped wrapper** (`.fs-form`) targeting the plugin's own markup, **not** by
  overriding plugin core CSS. Field classes map 1:1 to §3.3.
- **Preserve the current required set and recipient rules first;** the four routing fields (role,
  phone, district, interest) are **additive**. If the live form has fewer fields, add them — do not
  remove any that exist.
- **Interest select** options read from the Services CPT so the two pages cannot drift; falls back to
  a hardcoded list if the CPT is empty.
- **Success without redirect:** use the plugin's AJAX confirmation and render the success panel into
  the form's container. If the plugin can only redirect, redirect back to `?sent=1` and render the
  panel server-side from that param — same panel, same copy.
- **Spam:** honeypot plus a timing check, or Turnstile. **No visible CAPTCHA** — it is the single
  largest drop-off on a low-volume B2B form.
- **Consent checkbox** writes to the mailing list only on opt-in, stored with a timestamp. Unchecked
  by default, always.
- **Editable in WordPress:** hero heading and paragraph, form heading and helper line, the three
  "what happens next" steps, the reply-time promise, the aside contact values, and the four reach
  cards (title, note, CTA, link) as a capped repeater. **Validation messages live in one filterable
  array**, not scattered in templates.
- **Reply-time promise appears twice** — under the submit button and in the success panel. Pull both
  from **one theme-option field** so they can never disagree.
- **A11y:** one `h1`; every input has a real `<label for>` (**no placeholder-as-label**); the error
  summary is `role="alert"` and focusable; the success panel is `role="status"`; the asterisk key is
  explained in text before the first field; the skip link targets `#main`; `#form` carries
  `scroll-margin-top: 106px`.
- **Analytics:** fire the conversion on success-panel render, and **log validation-error events by
  field name** — that is how you find out which field is costing you submissions.
- **Mockup caveat:** the state tabs in the mockup's appendix are review chrome and do not ship.
  Validation in the mockup is illustrative; the real handler owns server-side validation too.

---

## 12. Assumptions & missing assets

1. **No Contact copy in the doc.** The hero, form headings, helper text, validation messages, success
   copy, and reach cards are all the designer's. Every string is replaceable.
2. **"Within two business days"** is invented and **the single most important thing to confirm** — it
   appears twice and it is a promise to real people.
3. **Current field list unconfirmed.** Name, email, phone, message were assumed from the homepage
   form; role, district/school, and interest were added for routing. If the live form differs, its
   fields win and these are additive.
4. **Four routing fields added.** Each is a small tax on the sender; if the team will not act on the
   routing, cut interest and role and the form gets shorter.
5. **Consent checkbox is new** and assumes a mailing list exists. If there is no list, remove it —
   do not collect permission you cannot honor.
6. **Email and phone** are the ones used site-wide (hello@focusedschools.com, 844-957-2466). Confirm
   these are the right inbox and line for inbound inquiries.
7. **No office address or hours** supplied, so the aside carries none and there is no map. A
   visitable address would become a fourth row in the teal card.
8. **Privacy policy link** not included — "We never share your information" carries it for now. If a
   policy page exists, that line should link to it.
9. **Form plugin unknown.** The restyle approach assumes a plugin-rendered form; if it is a custom
   handler, the same field spec applies unchanged.
10. **No hero photograph by choice.** The one page in the set that opens without 21:9 media — a
    deliberate call, flagged because it is a visible departure from the family.
