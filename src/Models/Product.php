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
    public string $page;
    public string $limit;
    public string $total;
    /**
     * @var list<array{
     *         score: string,
     *         salePrice: string,
     *         cateId: string,
     *         salePriceFormat: string,
     *         orders: string,
     *         itemMainPic: string,
     *         title: string,
     *         originalPriceFormat: string,
     *         itemUrl: string
     *    }>
     */
    public array $products;

    public function __construct(array $data)
    {
        $this->page = $data['pageIndex'];
        $this->limit = $data['pageSize'];
        $this->total = $data['totalCount'];
        $this->products = $data['products'];
    }
}
