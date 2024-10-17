<?php
/**
 * Project: AliDrop
 * File: ProductDetailsPackagingInfo.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 17/10/2024 at 10:34 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class ProductDetailsPackagingInfo
{
    /**
     * @var int Product height in centimeters
     */
    public int $height;
    /**
     * @var int Product length in centimeters
     */
    public int $length;
    /**
     * @var float Product weight in kilograms
     */
    public float $weight;
    /**
     * @var int Product width in centimeters
     */
    public int $width;

    /**
     * @param array{
     *     base_unit: int,
     *     package_height: int,
     *     gross_weight: string,
     *     package_length: int,
     *     package_width: int,
     *     product_unit: int,
     *     package_type:bool
     * } $packagingInfo
     */
    public function __construct(array $packagingInfo)
    {
        $this->height = $packagingInfo['package_height'];
        $this->length = $packagingInfo['package_length'];
        $this->weight = $packagingInfo['gross_weight'];
        $this->width = $packagingInfo['package_width'];
    }

}
