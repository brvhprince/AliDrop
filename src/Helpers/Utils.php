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

namespace Pennycodes\AliDrop\Helpers;

use Pennycodes\AliDrop\Models\ProductDetailsVariation;

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

    /**
     * Extract variation attributes from the ProductDetailsVariation object
     *
     * @param ProductDetailsVariation[] $data
     * @return array
     */
    public static function extractVariationAttributes(array $data): array
    {
        // Initialize an empty array to store the result
        $result = [];

        // Iterate through each item in the data
        foreach ($data as $item) {
            // Iterate through each variation in the 'variation' array
            if ($item->variation === null) {
                continue;
            }

            // convert the variation to an array
            foreach ($item->variation as $variation) {
                $name = $variation->name;
                $value = $variation->custom_value ?? $variation->value; // Use custom_value if not null
                $image = $variation->image ?? null;

                // Check if the variation name already exists in the result array
                $existingIndex = array_search($name, array_column($result, 'name'));

                if ($existingIndex === false) {
                    // If it doesn't exist, create a new entry for this variation
                    $result[] = [
                        'name' => $name,
                        'options' => [
                            [
                                'value' => $value,
                                'image' => $image
                            ]
                        ]
                    ];
                } else {
                    // If it exists, check if the option already exists for this attribute
                    $existingOptions = array_column($result[$existingIndex]['options'], 'value');

                    // Add the option only if it's not a duplicate
                    if (!in_array($value, $existingOptions)) {
                        $result[$existingIndex]['options'][] = [
                            'value' => $value,
                            'image' => $image
                        ];
                    }
                }
            }
        }

        return $result;
    }


}
