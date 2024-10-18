<?php
/**
 * Project: AliDrop
 * File: ProductDetailsProperties.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 17/10/2024 at 10:22 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Models;

class ProductDetailsProperties
{
    /**
     * @var ?int Attribute name id
     */
    public ?int $attr_name_id;
    /**
     * @var string Attribute name
     */
    public string $attr_name;
    /**
     * @var ?int Attribute value id
     */
    public ?int $attr_value_id;
    /**
     * @var string Attribute value
     */
    public string $attr_value;

    /**
     * @param array{
     *  attr_name_id?: int,
     *  attr_value_id?: int,
     *   attr_name: string,
     *     attr_value: string
     * } $property
     */
    public function __construct(array $property)
    {
        $this->attr_name_id = $property['attr_name_id'] ?? null;
        $this->attr_name = $property['attr_name'];
        $this->attr_value_id = $property['attr_value_id'] ?? null;
        $this->attr_value = $property['attr_value'];
    }

}
