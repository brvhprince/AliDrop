<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: Product.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:48 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Models;

class Product
{
    /**
     * @var string Product categories separated by comma
     */
    public string $categories;
    /**
     * @var string Product id
     */
    public string $id;
    /**
     * @var string Product title
     */
    public string $title;
    /**
     * @var string Product image url
     */
    public string $image;
    /**
     * @var string Product link
     */
    public string $link;
    /**
     * @var int Product orders count
     */
    public int $orders;
    /**
     * @var ?string Product original price
     */
    public ?string $original_price;
    /**
     * @var string Product sale price
     */
    public string $sale_price;
    /**
     * @var float Product score/rating
     */
    public float $score;
    /**
     * @var array{
     *     thousandsChar: string,
     *     shipToCountry: string,
     *     decimalPointChar: string,
     *     cent: int,
     *     currencySymbolPosition: string,
     *     currencySymbol: string,
     *     showDecimal: bool,
     *     decimalStr: string,
     *     currencyCode: string,
     *     version: string,
     *     formatPrice: string,
     *     integerStr: string,
     * } sale price info
     */
    public array $price_info;


    /**
     * @param array{
     *     cateId: string,
     *     itemId: string,
     *     itemMainPic: string,
     *     itemUrl: string,
     *     orders: string,
     *     originalPriceFormat: string,
     *     salePrice: string,
     *     salePriceFormat: string,
     *     score: string,
     *     title: string,
     * } $productDetails
     */
    public function __construct(array $productDetails)
    {
        $this->categories = $productDetails['cateId'];
        $this->id = $productDetails['itemId'];
        $this->title = $productDetails['title'];
        $this->image = $productDetails['itemMainPic'];
        $this->link = $productDetails['itemUrl'];
        $this->orders = (int) $productDetails['orders'];
        $this->original_price = $productDetails['originalPriceFormat'] ?? null;
        $this->sale_price = $productDetails['salePriceFormat'];
        $this->score = (float) $productDetails['score'];
        $this->price_info = json_decode($productDetails['salePrice'], true);
    }
}
