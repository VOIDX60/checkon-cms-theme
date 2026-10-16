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

    /** SilverStripe 6 runs this hook from LeftAndMain::init(). */
    public function onInit(): void
    {
        $this->applyMenuConfig();
    }

    /** SilverStripe 5 called the extension's own init(). */
    public function init(): void
    {
        $this->applyMenuConfig();
    }

    private function applyMenuConfig(): void
    {
        $headings = (array) $this->getOwner()->config()->get('menu_section_headings');
        $highlights = (array) $this->getOwner()->config()->get('menu_highlight_items');

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
            $expanded = '#cms-menu:not(.collapsed) #Menu-' . $this->cssIdentifier((string) $code);
            $css .= <<<CSS
{$selector} a {
  background: var(--checkon-admin-ink);
  border: 1px solid var(--checkon-admin-ink);
  border-radius: var(--checkon-admin-radius);
  box-shadow: 0 1px 2px rgba(33, 32, 28, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.08) !important;
  color: #fff;
  font-weight: 600;
  margin-bottom: 12px;
  min-height: 40px;
  padding-right: 14px;
  transition: background 120ms ease, border-color 120ms ease, transform 120ms ease;
}
{$selector} a:hover,
{$selector} a:focus {
  background: #34322d;
  border-color: #34322d;
  color: #fff;
  transform: translateY(-1px);
}
{$expanded} a .text {
  color: #fff;
  font-size: 13px;
  margin-left: 34px !important;
  white-space: nowrap;
}
{$expanded} a::after {
  color: var(--checkon-admin-accent);
  flex: none;
  margin-right: 2px;
  content: "\\2197";
  font-size: 15px;
  font-weight: 700;
  margin-left: auto;
}
{$selector} .menu__icon {
  color: var(--checkon-admin-accent);
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
