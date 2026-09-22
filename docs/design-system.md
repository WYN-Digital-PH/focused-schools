# Design System

Status: **approved (2026 rebrand)**. Tokens below are sourced from the approved rebrand
reference (`https://focused-schools-rebrand.vercel.app/`, confirmed by the project owner
2026-09-22) and are now reflected in `wp-content/themes/focused-schools/theme.json`.

## 1. Purpose

This document holds the visual design system for `focused-schools`: colors, typography,
spacing, and other shared design tokens used by the theme.

## 2. Colors

| Token (theme.json slug) | Value | Usage |
| ------------------------ | ------- | ----- |
| `base`     | `#ffffff` | Page background ("Paper") |
| `contrast` | `#005c6d` | Body text ("Ink" — House Teal, not black; a deliberate brand choice) |
| `primary`  | `#dd6237` | Primary CTA buttons ("Coral" — all `.fs-btn--primary` actions) |
| `secondary`| `#005c6d` | Secondary buttons, header/footer accents (House Teal) |
| `accent`   | `#0a96cb` | Links, highlights, small accents (Cerulean) |
| `lime`     | `#a7cc14` | Decorative accent only (Lime) |
| `gold`     | `#f3c145` | Decorative accent / focus-ring on dark backgrounds (Gold) |
| `raspberry`| `#bc3e8c` | Decorative accent only (Raspberry) |
| `ink-soft` | `#51536a` | Secondary/muted text |
| `paper-soft` | `#f5f6f8` | Subtle section backgrounds |

Primary/secondary button color roles: `button-primary` = coral fill, white text.
`button-secondary` = white fill, teal text/border, inverts to teal fill/white text on
hover. Both are implemented as the existing `fs-btn--primary`/`fs-btn--secondary`
variants in `template-parts/components/button.php`.

## 3. Typography

| Token | Font | Usage |
| ----- | ---- | ----- |
| `system` (font-family slug) | DM Sans, falling back to the system UI stack | All body/heading text site-wide |

DM Sans is loaded from Google Fonts (`wp_enqueue_style`, see `inc/enqueue.php`) rather
than self-hosted — this project has no font files to self-host, and Google Fonts is
already an accepted external dependency pattern for WordPress themes. Weights loaded:
400 (regular), 700 (bold), 900 (black, used for large display headings).

Font sizes (`fontSizes` in theme.json) are unchanged from the existing scale: small
(0.9rem), medium (1rem), large (1.5rem), x-large (2.25rem).

## 4. Spacing / Layout

Unchanged from the existing scale — see `theme.json`: small (0.5rem), medium (1rem),
large (2rem), x-large (3rem), 2x-large (4rem). Content width 720px, wide width 1200px
(`.fs-container` / `.fs-container--wide`).

## 5. Breakpoints

No formal breakpoint tokens exist yet (components use ad hoc `@media` queries, mobile
first). Not changed by the 2026 rebrand — a future task if a shared breakpoint scale is
needed.

## 6. Iconography & Imagery

Rebrand reference uses inline SVG for all icons (chevron/arrow, social glyphs) — no icon
font. Photography is documentary-style (real people, not stock-looking corporate
photography) — see `docs/page-specs/home.md` for the Home page's specific placeholder
image list and required alt text.

## 7. Notes

This file now reflects real, approved values. Any further palette/typography change
should update this file first, then `theme.json` to match — same workflow as before,
just no longer gated on "no design decisions yet."
