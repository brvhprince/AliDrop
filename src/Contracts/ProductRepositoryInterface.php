<?php
/**
 * Project: AliDrop
 * File: ProductRepositoryInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 21/09/2024 at 11:43 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Contracts;

use Wanpeninsula\AliDrop\Models\SingleProduct;

interface ProductRepositoryInterface extends RepositoryInterface
{

    public function searchProducts(array $filters, int $page = 1, int $limit = 10): array;
    public function findProductById(string $productId, array $params): SingleProduct;
}
