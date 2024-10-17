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
     * @var string Product height in centimeters
     */
    public string $height;
    /**
     * @var string Product length in centimeters
     */
    public string $length;
    /**
     * @var string Product weight in kilograms
     */
    public string $weight;
    /**
     * @var string Product width in centimeters
     */
    public string $width;

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
