# 03 — Content architecture

## Stored post types and counts

These are database rows in the supplied export. They establish stored content, although only a live runtime registry can prove every currently registered type.

| Post type | Published | Draft/other | Interpretation |
| --- | ---: | ---: | --- |
| `page` | 27 | 49 drafts | Primary pages, Team, Services, Podcast and Impact Stories |
| `post` | 123 | 1 draft, 2 auto-drafts | Blog |
| `attachment` | 0 | 708 inherited | Media library |
| `elementor_library` | 16 | 4 drafts | Theme Builder and reusable Elementor templates |
| `nav_menu_item` | 24 | 0 | Three menus, eight items each |
| `custom-css-js` | 3 | 0 | Injected script/CSS records |
| `custom_css` | 4 | 0 | Customizer CSS for historic/current themes |
| `wpslmaps` | 6 | 0 | WP Maps style records |
| `udb_admin_page` | 1 | 0 | Ultimate Dashboard content |
| `slick_slider` | 1 | 0 | Legacy slider content |
| `awl_filter_gallery` | 1 | 0 | Legacy/demo gallery content |
| `pnd` | 1 | 0 | Orphan/demo “Pines Down Under” content |
| `wp_global_styles` | 2 | 0 | Theme global-style records |
| `oembed_cache` | 7 | 0 | WordPress embed cache |

The last three legacy/demo types should be treated as cleanup candidates only after identifying their registering code and confirming no URL or template dependency.

## Taxonomies

- Blog categories: Uncategorized (all 123 published Posts), News & Events (0), News & Tips (0), and `null - null` (0).
- No `post_tag` term relationships appear in the export.
- Elementor library types classify archive, footer, header, popup, section, single and widget templates.
- Three native menu taxonomies exist: Main Menu, Header menu, Footer menu — no dropdown.
- `pnd_cat` contains Australia and Beer for legacy/demo content.
- `wp_theme` terms exist for Hello Elementor and its child theme.

The Blog taxonomy is effectively flat: every published Post is Uncategorized. Preserving category URLs is still required, but no meaningful editorial category model currently exists.

## Primary content models

| Area | Current type | Evidence | Conclusion |
| --- | --- | --- | --- |
| Home | Page ID 992, slug `home`; configured as front page | `_elementor_edit_mode=builder` and Elementor layout data | Normal Page rendered by Elementor |
| About | Page ID 3726, slug `about-our-mission-vision` | Elementor data includes HT Mega map and audio widget | Normal Page rendered by Elementor |
| Team | Page ID 1198, slug `team` | Team names, roles, images and quotations are repeated inside one Elementor Page | Normal Page; not a CPT |
| Services | Page ID 1187, slug `services` | Elementor headings, text, button and video widgets | Normal Page; not a CPT |
| Impact Stories | Landing Page ID 3785 plus individual story Pages such as 3737, 3821, 3833, 4369, 4476 and 4870 | All are `page` rows with Elementor metadata | Normal Pages; no CPT or relational archive |
| Podcast | Page ID 1189, slug `podcast` | Elementor HTML/video widgets, Buzzsprout player and manually embedded YouTube URLs | Normal Page plus external integrations |
| Blog | Native Posts; Posts Page ID 1191, slug `blog` | 123 published `post` rows; Elementor archive template 932 | Native Posts with mixed editors |
| Contact | Page ID 1195, slug `contact` | Elementor Pro form widgets and special footer condition | Normal Page with plugin-controlled forms |

## Recommendation by model

- Team does not yet justify a CPT. The current data is repeated but no independent URLs, taxonomy, filtering or querying requirement was demonstrated. Native blocks/patterns on the existing Page are simpler. Revisit only when staff require individual profiles or structured reuse.
- Impact Stories should remain Pages during this sprint. A later CPT may be justified by a defined archive, filters, consistent fields and a migration/redirect plan. Converting now adds risk without a current structural requirement.
- Services should remain the existing Page. Use block anchors for its three service sections while preserving any live legacy anchor links.
- Blog remains native Posts. Future Posts can use Gutenberg while legacy Elementor Posts remain supported.
