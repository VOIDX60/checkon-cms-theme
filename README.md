# checkon-cms-theme

CheckOn's branded admin theme for the SilverStripe CMS (5 and 6): a quiet, light,
business-style skin for the whole CMS (menu, header, buttons, page tree, forms,
tabs, tables, dialogs and the sign-in page), with section headings and a
highlighted menu item driven by config.

The CMS stays light on purpose: the framework's own screens have no dark theme
for this to match.

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

## Branding

The name, the line under it, where the logo links, the logo and the accent colour are
config, so the same theme works on any site. Unset, the admin reads as CheckOn's.

```yaml
Checkon\CmsTheme\Branding:
  name: 'Otoko'
  subtitle: 'Site admin'
  link: 'admin/pages'                              # where the logo goes; default 'admin'
  logo: 'vendor/module:client/images/logo.png'     # a "vendor/module:path" resource
  accent: '#0f766e'                                # #rrggbb; hover and tint colours follow
```

The browser-tab title ("... Admin - Pages") is SilverStripe's own
`SilverStripe\Admin\LeftAndMain.application_name`; set it alongside.

A new site needs nothing else: install, flush, and the whole CMS (menu, header, buttons,
page tree, forms, tabs, tables, dialogs, sign-in page) takes the skin. The menu headings and
highlighted link above are optional and only appear where a site configures them.

## Versions

Built and tested on SilverStripe 6. The CMS styling and menu work on 5 as well, but the
sign-in page restyle relies on SilverStripe 6's `silverstripe/login-forms`, so on 5 the sign-in
page stays stock.

## What this theme does not do

It doesn't touch page/element templates, only the CMS admin chrome.
