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
use Wanpeninsula\AliDrop\Models\Categories;
use Wanpeninsula\AliDrop\Models\FreightOption;
use Wanpeninsula\AliDrop\Models\Product;
use Wanpeninsula\AliDrop\Models\SingleCategory;
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
     * @param array{
     *     query: string,
     *     local?: string,
     *     country_code?: string,
     *     currency?: string,
     *     category_id?: string,
     *     sort_by?: string,
     * } $filters
     * @param int $page
     * @param int $limit
     * @return array{page: int, limit: int, total: int, products: Product[]}
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
     * @param array{
     *     language?: string,
     *     country_code?: string,
     *     currency?: string,
     *     remove_personal_benefit?: string,
     * } $params
     * @return SingleProduct
     * @throws ApiException
     * @throws ValidationException
     */
    public function single_product(string $productId, array $params = []): SingleProduct
    {
        return $this->productRepository->findProductById($productId, $params);
    }
    /**
     * Fetch categories
     * @return Categories
     * @throws ApiException
     * @throws ValidationException
     */
    public function categories(): Categories
    {
        return $this->productRepository->fetchCategories(null);
    }
    /**
     * Fetch category
     * @param string $categoryId
     * @return SingleCategory
     * @throws ApiException
     * @throws ValidationException
     */
    public function category(string $categoryId): SingleCategory
    {
        return $this->productRepository->fetchCategories($categoryId);
    }

    /**
     * Fetch category
     * @param array{
     *     product_id: string,
     *     sku_id: string,
     *     quantity: int,
     *     currency?: string,
     *     country_code?: string,
     *     language?: string,
     *     locale?: string,
     * } $filters
     * @return FreightOption[]
     * @throws ApiException
     * @throws ValidationException
     */
    public function deliveryOptions(array $filters): array
    {
        return $this->productRepository->fetchFreightOptions($filters);
    }

}
