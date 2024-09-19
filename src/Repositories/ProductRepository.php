<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: ProductRepository.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:47 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Repositories;

use Api\Client;

class ProductRepository extends BaseRepository
{

    public function getAllProducts($page = 1, $limit = 10)
    {
        $response = $this->apiClient->get('/products', ['page' => $page, 'limit' => $limit]);
        return array_map(function($productData) {
            return new Product($productData);
        }, $response['data']);
    }

    public function findProductById($productId)
    {
        $response = $this->apiClient->get("/products/{$productId}");
        return new Product($response['data']);
    }

}
