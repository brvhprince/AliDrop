<?php
/**
 * Project: AliDrop
 * File: FreightOption.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 15/10/2024 at 10:18 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class FreightOption
{

    /**
     * @param array{
     *     code: string,
     *      shipping_fee_currency: string,
     *      free_shipping: boolean,
     *     mayHavePFS: boolean,
     *     guaranteed_delivery_days: string,
     *     max_delivery_days: string,
     *     tracking: boolean,
     *     shipping_fee_format: string,
     *     estimated_delivery_time: string,
     *     delivery_date_desc: string,
     *     company: string,
     *     ship_from_country: string,
     *     min_delivery_days: string,
     *     available_stock: string,
     *     shipping_fee_cent: string,
     * } $option
     */

    /**
     * @var string Shipping company unique code
     */
    public string $code;
    /**
     * @var string Shipping company identifiable name
     */
    public string $company;
    /**
     * @var ?string Shipping fee currency
     */
    public ?string $currency;
    /**
     * @var boolean Is free shipping
     */
    public bool $free_shipping;
    /**
     * @var boolean Shipping may have PFS
     */
    public bool $PFS;
    /**
     * @var int Guaranteed delivery days of the shipping company
     */
    public int $guaranteed_delivery;
    /**
     * @var int Estimated maximum number of days for the product to be delivered
     */
    public int $max_delivery;
    /**
     * @var int Estimated minimum number of days for the product to be delivered
     */
    public int $min_delivery;
    /**
     * @var boolean Shipping company provides tracking
     */
    public bool $tracking;
    /**
     * @var ?string Shipping fee in currency format
     */
    public ?string $shipping_fee;
    /**
     * @var ?string Shipping fee in cent format
     */
    public ?string $shipping_fee_cent;
    /**
     * @var ?string Estimated delivery time
     */
    public ?string $estimated_delivery_time;
    /**
     * @var ?string Delivery date description
     */
    public ?string $delivery_date_desc;
    /**
     * @var string Shipping company country of origin
     */
    public string $shipping_country;
    /**
     * @var int product available stock
     */
    public int $stock;


    public function __construct(array $option)
    {

        $this->code = $option['code'];
        $this->company = $option['company'];
        $this->currency = $option['shipping_fee_currency'] ?? null;
        $this->free_shipping = $option['free_shipping'];
        $this->PFS = $option['mayHavePFS'];
        $this->guaranteed_delivery = $option['guaranteed_delivery_days'];
        $this->max_delivery = $option['max_delivery_days'];
        $this->min_delivery = $option['min_delivery_days'];
        $this->tracking = $option['tracking'];
        $this->shipping_fee = $option['shipping_fee_format'] ?? null;
        $this->shipping_fee_cent = $option['shipping_fee_cent'] ?? null;
        $this->estimated_delivery_time = $option['estimated_delivery_time'] ?? null;
        $this->delivery_date_desc = $option['delivery_date_desc'] ?? null;
        $this->shipping_country = $option['ship_from_country'];
        $this->stock = $option['available_stock'] ?? 0;

    }

}
