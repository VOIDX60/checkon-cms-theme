<?php

namespace Checkon\CmsTheme\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

/** Brings the CheckOn skin to the CMS sign-in page, which is not part of the CMS shell. */
class LoginStylesExtension extends Extension
{
    public function onBeforeInit(): void
    {
        Requirements::css('voidx60/checkon-cms-theme:client/css/login.css');
    }
}
