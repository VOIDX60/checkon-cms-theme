# checkon-cms-theme

CheckOn's branded admin theme for SilverStripe 5's CMS: the logo/colour menu
header, section headings and highlighted menu items driven by config, and a
fix for the icon alignment in the collapsed (minimised) menu rail.

## Install

Not on Packagist - require it as a VCS repository:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/VOIDX60/checkon-cms-theme.git"
        }
    ],
    "require": {
        "voidx60/checkon-cms-theme": "^1.0"
    }
}
```

```bash
composer require voidx60/checkon-cms-theme
vendor/bin/sake dev/build flush=1
```

## Configuring the menu

Section headings and the highlighted "CTA" menu item are not hardcoded -
declare them in the host app's own `LeftAndMain` config (e.g.
`app/_config/admin-menu.yml`):

```yaml
SilverStripe\Admin\LeftAndMain:
  menu_section_headings:
    App-Admin-CompanyAdmin: 'Customers'
    SilverStripe-CMS-Controllers-CMSPagesController: 'Website'
    App-Admin-FoodSafetyContentAdmin: 'CheckOn app'
    SilverStripe-Admin-SecurityAdmin: 'System'
  menu_highlight_items:
    - App-Admin-CheckOnFrontendAdmin
```

- `menu_section_headings` puts a heading above the given menu item, in the
  order the map is declared. The first entry gets no divider line above it
  (it usually sits right under the brand header); every later entry gets a
  hairline separator.
- `menu_highlight_items` renders the given item as a solid CTA button (used
  in CheckOn for the "Open CheckOn" link back to the main app).
- Both use a menu item's *code* - the `Menu-<code>` id SilverStripe renders
  for it, i.e. the item's PHP class with `\` replaced by `-`
  (`SilverStripe\Admin\CMSMenu::get_menu_code()`). Grouping/order itself is
  still controlled the normal SilverStripe way, via each admin class's
  `menu_priority`.

Headings only render for menu items the current CMS user can actually see -
if nobody with access to the group's first item is logged in, the heading
doesn't appear (same as a section with no visible items).

## What this theme does not do

It doesn't touch page/element templates, only the CMS admin chrome. It also
doesn't attempt to be a generic drop-in for other SilverStripe sites - the
default brand text/logo/home-link are CheckOn's; fork or extend
`templates/SilverStripe/Admin/LeftAndMain_Menu.ss` if you need different
copy.
