<?php
/**
 * Project: AliDrop
 * File: ProductDetailsStoreInfo.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 17/10/2024 at 10:17 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Models;

class ProductDetailsStoreInfo
{

    /**
     * @var int Store id
     */
    public int $id;
    /**
     * @var string Store name
     */
    public string $name;
    /**
     * @var string Store country code can be used as 'ship from' country of the sku
     */
    public string $country;
    /**
     * @var float Store shipping rating
     */
    public float $shipping_rating;
    /**
     * @var float Store communication rating
     */
    public float $communication_rating;
    /**
     * @var float Store item as described rating
     */
    public float $item_as_described_rating;

    /**
     * @param array{
     *  store_id: int,
     *  shipping_speed_rating: string,
     *  communication_rating: string,
     *     store_name: string,
     *     store_country_code: string,
     *     item_as_described_rating: string,
     * } $storeInfo
     */
    public function __construct(array $storeInfo)
    {
        $this->id = $storeInfo['store_id'];
        $this->name = $storeInfo['store_name'];
        $this->country = $storeInfo['store_country_code'];
        $this->shipping_rating = $storeInfo['shipping_speed_rating'];
        $this->communication_rating = $storeInfo['communication_rating'];
        $this->item_as_described_rating = $storeInfo['item_as_described_rating'];
    }
}
