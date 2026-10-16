<?php

namespace Checkon\CmsTheme;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * The few things that make the admin look like a particular site's: its name, the line under it,
 * where the logo links to, the logo itself and the accent colour. Set them in the host site's
 * config; unset, the admin reads as CheckOn's.
 *
 *   Checkon\CmsTheme\Branding:
 *     name: 'Otoko'
 *     subtitle: 'Site admin'
 *     link: 'admin/pages'
 *     logo: 'vendor/module:client/images/logo.png'
 *     accent: '#0f766e'
 */
class Branding
{
    use Configurable;
    use Injectable;

    private static string $name = 'CheckOn';

    private static string $subtitle = 'Admin console';

    /** Where the logo links to, relative to the site root. */
    private static string $link = 'admin';

    /** A "vendor/module:path" resource, or empty for the theme's own logo. */
    private static string $logo = '';

    /** A #rrggbb colour; empty keeps CheckOn's orange. */
    private static string $accent = '';

    public static function logoResource(): string
    {
        return (string) static::config()->get('logo') ?: 'voidx60/checkon-cms-theme:client/images/logo.png';
    }

    /**
     * CSS that re-points the theme's accent variables at the configured colour, or '' when none is set
     * (or the value is not a plain hex colour, so config can never inject anything else into a page).
     */
    public static function accentCss(): string
    {
        $hex = strtolower(trim((string) static::config()->get('accent')));
        if (!preg_match('/^#([0-9a-f]{6})$/', $hex, $match)) {
            return '';
        }

        [$r, $g, $b] = array_map('hexdec', str_split($match[1], 2));
        $darker = fn (int $channel): int => (int) round($channel * 0.9);
        $hover = sprintf('#%02x%02x%02x', $darker($r), $darker($g), $darker($b));
        $text = sprintf('#%02x%02x%02x', (int) round($r * 0.78), (int) round($g * 0.78), (int) round($b * 0.78));

        return sprintf(
            ':root{--checkon-admin-accent:%1$s;--checkon-admin-accent-hover:%2$s;--checkon-admin-accent-text:%3$s;'
            . '--checkon-admin-accent-soft:rgba(%4$d,%5$d,%6$d,0.09);--checkon-admin-accent-border:rgba(%4$d,%5$d,%6$d,0.28);'
            . '--checkon-admin-ring:0 0 0 3px rgba(%4$d,%5$d,%6$d,0.18);--bs-primary:%1$s;--bs-primary-rgb:%4$d,%5$d,%6$d;}',
            $hex,
            $hover,
            $text,
            $r,
            $g,
            $b
        );
    }
}
