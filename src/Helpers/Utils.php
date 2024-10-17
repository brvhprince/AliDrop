<?php
/**
 * Project: AliDrop
 * File: Utils.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 17/10/2024 at 11:59 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Helpers;

class Utils
{

    public static function strip_tags(string $html): string
    {
        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[/!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments
        );

        $string = preg_replace($search, '', $html);
        $string = html_entity_decode($string, ENT_QUOTES);
        // remove \n, \r, \t, \s
        $string = preg_replace('/\s+/', ' ', $string);
        return htmlspecialchars_decode($string, ENT_QUOTES);

    }

}
