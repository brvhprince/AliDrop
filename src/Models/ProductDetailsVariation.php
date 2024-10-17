<?php
/**
 * Project: AliDrop
 * File: ProductDetailsVariation.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 17/10/2024 at 11:11 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class ProductDetailsVariation
{
    /**
     * @var string SKU attribute. Used for placing an order
     */
    public string $sku_attr;
    /**
     * @var string SKU id. can be used for aliexpress.logistics.buyer.freight.calculate request
     */
    public string $sku_id;
    /**
     * @var string Sale price
     */
    public string $sale_price;
    /**
     * @var string Regular price
     */
    public string $regular_price;
    /**
     * @var string currency code
     */
    public string $currency_code;
    /**
     * @var int stock
     */
    public int $stock;
    /**
     * @var string variation id. sku attribute unique key
     */
    public string $id;
    /**
     * @var ?string SKU code
     */
    public ?string $sku_code;
    /**
     * @var ?ProductVariationItem[] Product variation items
     */
    public ?array $variation;
    /**
     * @var ?int Promotion limit. The maximum number of quantity a user can buy during promotion
     */
    public ?int $promotion_limit;
    /**
     * @var bool Does the price include tax
     */
    public bool $price_include_tax;
    /**
     * @var ?string The limit strategy of promotion limit to use
     */
    public ?string $limit_strategy;
    /**
     * @var bool is stock available
     */
    public bool $stock_available;

    /**
     * @param array{
     *     ipm_sku_stock: int,
     *     offer_bulk_sale_price: string,
     *     sku_available_stock: int,
     *     sku_bulk_order: int,
     *     sku_stock: bool,
     *     sku_price: string,
     *     offer_sale_price: string,
     *     id: string,
     *     barcode?: string,
     *     currency_code: string,
     *     sku_code: string,
     *     sku_id: string,
     *     sku_attr: string,
     *     ean_code?: string,
     *     price_include_tax: bool,
     *     buy_amount_limit_set_by_promotion?: string,
     *     limit_strategy?: string,
     *     wholesale_price_tiers?: list<array{
     *     min_quantity: string,
     *     discount: string,
     *     wholesale_price: string,
     *     }>,
     *     ae_sku_property_dtos: array{
     *         ae_sku_property_d_t_o: list<array{
     *            sku_property_value: string,
     *            property_value_id: int,
     *            sku_property_name: string,
     *            sku_property_id: int,
     *            property_value_definition_name?: string,
     *            sku_image?: string
     *       }>
     *     }
     * } $sku
     */
    public function __construct(array $sku)
    {
        $this->sku_attr = $sku['sku_attr'];
        $this->sku_id = $sku['sku_id'];
        $this->sale_price = $sku['offer_sale_price'];
        $this->regular_price = $sku['sku_price'];
        $this->currency_code = $sku['currency_code'];
        $this->stock = $sku['sku_available_stock'];
        $this->id = $sku['id'];
        $this->sku_code = $sku['sku_code'] ?? null;
        $this->promotion_limit = $sku['buy_amount_limit_set_by_promotion'] ?? null;
        $this->price_include_tax = $sku['price_include_tax'];
        $this->limit_strategy = $sku['limit_strategy'] ?? null;
        $this->stock_available = $sku['sku_stock'];
        if (isset($sku['ae_sku_property_dtos']) && !empty($sku['ae_sku_property_dtos']['ae_sku_property_d_t_o'])) {
            $this->variation = array_map(function ($item) {
                return new ProductVariationItem($item);
            }, $sku['ae_sku_property_dtos']['ae_sku_property_d_t_o']);
        }
        else {
            $this->variation = null;
        }

    }

}
