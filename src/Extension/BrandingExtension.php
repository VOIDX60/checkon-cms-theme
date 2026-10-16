<?php

namespace Checkon\CmsTheme\Extension;

use Checkon\CmsTheme\Branding;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;
use SilverStripe\Model\ArrayData;
use SilverStripe\View\Requirements;

/**
 * Makes the configured brand available to the CMS menu and sign-in templates as $CheckonBrand,
 * and applies the configured accent colour on those pages.
 */
class BrandingExtension extends Extension
{
    public function getCheckonBrand(): ArrayData
    {
        $config = Branding::config();
        $link = ltrim((string) $config->get('link'), '/');

        return ArrayData::create([
            'Name' => (string) $config->get('name'),
            'Subtitle' => (string) $config->get('subtitle'),
            'Link' => Director::absoluteURL($link),
            'LogoResource' => Branding::logoResource(),
        ]);
    }

    /** SilverStripe 6 runs this on the CMS and the sign-in page. */
    public function onInit(): void
    {
        $this->applyAccent();
    }

    /** SilverStripe 5's controllers run this before init. */
    public function onBeforeInit(): void
    {
        $this->applyAccent();
    }

    private function applyAccent(): void
    {
        $css = Branding::accentCss();
        if ($css !== '') {
            Requirements::customCSS($css, 'checkon-cms-theme-accent');
        }
    }
}
