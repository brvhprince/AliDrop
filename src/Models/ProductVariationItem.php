<?php
/**
 * Project: AliDrop
 * File: ProductVariationItem.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 17/10/2024 at 11:11 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class ProductVariationItem
{
    /**
     * @var string Product variation value
     */
    public string $value;
    /**
     * @var ?string Product variation image url
     */
    public ?string $image;
    /**
     * @var ?string Product variation custom value definition. If available use this instead of the value
     */
    public ?string $custom_value;
    /**
     * @var int Product variation value id. This is a custom id
     */
    public int $value_id;
    /**
     * @var int Product variation property id
     */
    public int $property_id;
    /**
     * @var string Product variation property name
     */
    public string $name;

    /**
     * @param array{
     *     sku_property_value: string,
     *     property_value_id: int,
     *     sku_property_name: string,
     *     sku_property_id: int,
     *     property_value_definition_name?: string,
     *     sku_image?: string,
     * } $variation
     */
    public function __construct(array $variation)
    {
        $this->value = $variation['sku_property_value'];
        $this->image = $variation['sku_image'] ?? null;
        $this->custom_value = $variation['property_value_definition_name'] ?? null;
        $this->value_id = $variation['property_value_id'];
        $this->property_id = $variation['sku_property_id'];
        $this->name = $variation['sku_property_name'];
    }

}
