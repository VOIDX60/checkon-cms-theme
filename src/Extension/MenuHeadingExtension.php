<?php

namespace Checkon\CmsTheme\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

/**
 * Renders CMS menu section headings and highlighted ("CTA") menu items from
 * config, instead of a theme having to ship CSS hardcoded to a specific
 * host app's menu item codes (App-Admin-FoodSafetyContentAdmin etc).
 *
 * Host app config example (in the app's own LeftAndMain yml):
 *
 *   SilverStripe\Admin\LeftAndMain:
 *     menu_section_headings:
 *       App-Admin-CompanyAdmin: 'Customers'
 *       SilverStripe-CMS-Controllers-CMSPagesController: 'Website'
 *     menu_highlight_items:
 *       - App-Admin-CheckOnFrontendAdmin
 *
 * Menu item codes match the "Menu-<code>" id SilverStripe renders for each
 * item (SilverStripe\Admin\CMSMenu::get_menu_code() - the FQCN with
 * backslashes replaced by dashes).
 */
class MenuHeadingExtension extends Extension
{
    private static array $menu_section_headings = [];

    private static array $menu_highlight_items = [];

    public function init()
    {
        $headings = (array) $this->owner->config()->get('menu_section_headings');
        $highlights = (array) $this->owner->config()->get('menu_highlight_items');

        if (!$headings && !$highlights) {
            return;
        }

        $css = '';
        $firstHeadingCode = array_key_first($headings);

        foreach ($headings as $code => $label) {
            $selector = '#cms-menu #Menu-' . $this->cssIdentifier($code);
            $css .= sprintf(
                "%s::before { content: \"%s\"; }\n",
                $selector,
                $this->cssString((string) $label)
            );

            if ($code === $firstHeadingCode) {
                $css .= "{$selector}::before { border-top: 0; margin-top: 11px; padding-top: 0; }\n";
            }
        }

        foreach ($highlights as $code) {
            $selector = '#cms-menu #Menu-' . $this->cssIdentifier((string) $code);
            $css .= <<<CSS
{$selector} a {
  background: #172b32;
  border-color: #172b32;
  border-radius: 10px;
  color: #fff;
  font-weight: 700;
  margin-bottom: 10px;
}
{$selector} a:hover,
{$selector} a:focus {
  background: #0d624d;
  color: #fff;
}
{$selector} .menu__icon {
  color: #8ee0c5;
}

CSS;
        }

        Requirements::customCSS($css, 'checkon-cms-theme-menu-config');
    }

    private function cssIdentifier(string $code): string
    {
        return preg_replace('/[^A-Za-z0-9_-]/', '', $code) ?? '';
    }

    private function cssString(string $value): string
    {
        return addcslashes($value, "\"\\\n\r");
    }
}
