<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: ProductService.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:46 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Services;

use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Exceptions\ValidationException;
use Wanpeninsula\AliDrop\Api\Client;
use Wanpeninsula\AliDrop\Models\Product;
use Wanpeninsula\AliDrop\Models\SingleProduct;
use Wanpeninsula\AliDrop\Repositories\ProductRepository;

class ProductService
{
    protected ProductRepository $productRepository;

    public function __construct(Client $client)
    {
        $this->productRepository = new ProductRepository($client);
    }

    /**
     * Search for products
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return Product[]
     * @throws ApiException
     * @throws ValidationException
     */
    public function search(array $filters, int $page = 1, int $limit = 10): array
    {
        return $this->productRepository->searchProducts($filters, $page, $limit);
    }
    /**
     * Fetch product details
     * @param string $productId
     * @param array $params
     * @return SingleProduct
     * @throws ApiException
     * @throws ValidationException
     */
    public function single_product(string $productId, array $params = []): SingleProduct
    {
        return $this->productRepository->findProductById($productId, $params);
    }

}
