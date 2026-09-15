# Page Specs

One Markdown file per page, named after the page slug (e.g. `about.md`, `impact-stories.md`).
A page is ready for development only when its spec meets the **Design Ready** gate in
[`../../Focused-Schools-Project-Plan.md`](../../Focused-Schools-Project-Plan.md).

## Template

```markdown
# {Page title}

- URL: /{slug}/
- Page ID: {existing ID, or "new"}
- Status: Draft | Design Ready | In Development | QA Ready
- Design source: {Claude Design link / file}
- Approved by: {name, date}

## Copy
Final copy location, or "final copy inline below".

## Section hierarchy
1. {Section} — component: {component name}
2. ...

## Desktop
Notes on the approved desktop layout.

## Mobile behavior
Stacking, hidden/reordered elements, navigation changes.

## Dynamic content
Queries or content models used (e.g. `fs_team_member`, `fs_service`, `fs_impact_story`).

## Assets
Images, icons, video — and where they live.

## Legacy / SEO notes
Elementor dependencies, forms, Yoast metadata, redirects, anything that must be preserved.
```
