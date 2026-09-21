# 05 — Forms and integrations

## Form inventory

All demonstrated forms are Elementor Pro Form widgets. No SMTP plugin, CRM action, webhook action, Mailchimp action, ActiveCampaign action, ConvertKit action, MailerLite action or configured reCAPTCHA key was found.

| Location | Page/template | Form behavior | Recipient / confirmation |
| --- | --- | --- | --- |
| General footer | Template 941, widget `1157a789` | Name, phone, email, message; saves submission, sends email, redirects | Business inbox; `/thanks` |
| Contact | Page 1195, widgets `bbce0a5` and `d23fb11` | Duplicate responsive form layouts; saves submission, emails, redirects | Business inbox; `/thanks/` |
| Let's Get Started | Page 1356, widget `29f9b96` | Detailed lead form including district, school, contact preference and services; saves, emails, redirects | Business inbox; `/thanks/` |
| Careers | Page 3702, widget `bbce0a5` | Contact-style form; saves, emails, redirects | Business inbox; `/thanks/` |
| Newsletter Sign Up | Page 4798 | One email-only form plus one duplicated contact-style form; saves, emails, redirects | Staff/business inboxes; `/thanks/` |
| Front-page popup | Template 912, widget `35e0b6b4` | Email-only “Contact Form”; save-to-database only | No email action; no external list integration evidenced |
| Reusable Contact template | Template 392 | Three fields but empty submit-actions array | Appears incomplete or unused; do not treat as functional |

Email recipients are documented at a role level in this public report; exact addresses remain in the source configuration and should be validated privately before staging tests.

## Contact form

The live Contact Page displays two copies of the same form because separate Elementor responsive sections exist. Stored actions are `save-to-database`, `email`, and `redirect`. The Page uses a dedicated Elementor footer condition, and the confirmation destination is the existing published `/thanks/` Page (ID 36). Removing Elementor Pro breaks submission handling, stored submissions and redirect behavior.

No SMTP plugin was found. The Akeeba configuration references local PHP mail settings, but this does not prove current production mail transport. Obtain a real mail-delivery trace or SiteGround mail configuration and run a controlled staging test before launch.

## Join our mailing list

The “Newsletter Sign Up” Page does not demonstrate a mailing-list platform integration. Its email-only form saves an Elementor submission, emails a staff recipient, and redirects to `/thanks/`. The popup similarly saves to the database only. Elementor's global Mailchimp/API fields are empty.

Therefore this is currently lead capture, not verified newsletter subscription automation. Preserve the stored submissions and staff notification during the sprint. Any Mailchimp/CRM automation would be new functionality requiring separate approval, consent wording and data-flow review.

## Spam and privacy

No configured reCAPTCHA keys were found, and no form-specific webhook or honeypot setting was established from the stored widget settings. Elementor may provide platform-level protections, but the evidence does not prove they are enabled. Confirm spam controls, retention for Elementor's `e_submissions` tables, privacy notices and deletion workflow before launch.

## Other integrations

- Analytics: GA4 `G-1MG2HCZGRN` is injected in the document head by Simple Custom CSS and JS. Avoid duplicate analytics during the rebuild.
- Maps: HT Mega map widgets appear on pages 3638 and 3726. WP Maps data/settings also exist, but a published-content shortcode dependency was not found.
- Podcast: Buzzsprout and YouTube are detailed below.

## Podcast

`/podcast/` is normal Page ID 1189 using Elementor. It loads a raw Buzzsprout large-player script for Buzzsprout show ID 1770397. The Buzzsprout plugin stores RSS feed `https://feeds.buzzsprout.com/2172941.rss`, so the script player and plugin feed settings refer to different numeric identifiers and must be reconciled with the account owner.

The same Page manually stores approximately twenty Elementor YouTube video widgets. Later entries include playlist ID `PLntAE9m90gzDHfD8VebNCbSgIlRPd19Ya`; social links point to channel `UCg8W-jIlwxFCrVsU15gWFqA`. Public HTML confirms the Buzzsprout script and YouTube widgets.

Keep Buzzsprout initially. A future playlist-driven video section is feasible through YouTube's playlist feed/API or an approved server-side cache, but it should be a separate enhancement. Confirm playlist ownership, ordering, API quota/credentials, fallback behavior, privacy-enhanced embeds and editorial control before implementation.
