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

namespace Wanpeninsula\AliDrop\Repositories;

use Exceptions\ApiException;
use Exceptions\ValidationException;
use Models\Product;
use Wanpeninsula\AliDrop\Helpers\Localization;

class ProductRepository extends BaseRepository
{

    /**
     * Search products
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array|Product[]
     * @throws ValidationException|ApiException
     */
    public function searchProducts(array $filters, int $page = 1, int $limit = 10)
    {
//        $allowedFilters = ['query', 'local', 'country_code', 'category_id', 'sort_by', 'currency'];

        if (empty($filters['query'])) {
            throw new ValidationException('Enter a search query');
        }

        $query = $this->buildQuery($filters, $page, $limit);

        $response = $this->apiClient
            ->requestName('aliexpress.ds.text.search')
            ->requestParams($query, [
                'countryCode' => Localization::getInstance()->getCountryCodes(),
                'currency' => Localization::getInstance()->getCurrencyCodes(),
                'local' => Localization::getInstance()->getLanguageCodes(),
                'categoryId' => Localization::getInstance()->getCategoryIds(),
                'sortBy' => ['min_price,desc', 'min_price,asc', 'orders,desc', 'orders,asc', 'comments,desc', 'comments,asc']
            ])
            ->execute();

        return array_map(function($productData) {
            return new Product($productData);
        }, $response['data']);
    }

    public function findProductById($productId)
    {
        $response = $this->apiClient->get("/products/{$productId}");
        return new Product($response['data']);
    }

    /**
     * Format query structure for API
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function buildQuery(array $filters, int $page = 1, int $limit = 10): array
    {
        $query = [
            'keyWord' => $filters['query'],
            'local' => Localization::getInstance()->getLanguage(),
            'countryCode' => Localization::getInstance()->getCountryCode(),
            'currency' => Localization::getInstance()->getCurrency(),
            'pageSize' => $limit,
            'pageIndex' => $page
        ];

        if (!empty($filters['local'])) {
            $query['local'] = $filters['local'];
        }

        if (!empty($filters['country_code'])) {
            $query['countryCode'] = $filters['country_code'];
        }

        if (!empty($filters['currency'])) {
            $query['currency'] = $filters['currency'];
        }


        if (!empty($filters['category_id'])) {
            $query['categoryId'] = $filters['category_id'];
        }

        if (!empty($filters['sort_by'])) {
            $query['sortBy'] = $filters['sort_by'];
        }

        return $query;

    }

}
