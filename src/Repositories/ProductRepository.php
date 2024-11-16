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

namespace Pennycodes\AliDrop\Repositories;

use Pennycodes\AliDrop\Exceptions\ApiException;
use Pennycodes\AliDrop\Exceptions\ValidationException;
use Pennycodes\AliDrop\Models\Categories;
use Pennycodes\AliDrop\Models\FreightOption;
use Pennycodes\AliDrop\Models\Product;
use Pennycodes\AliDrop\Models\SingleCategory;
use Pennycodes\AliDrop\Models\SingleProduct;
use Pennycodes\AliDrop\Traits\LoggerTrait;
use Pennycodes\AliDrop\Contracts\ProductRepositoryInterface;
use Pennycodes\AliDrop\Helpers\Localization;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    use LoggerTrait;

    /**
     * Search products
     * @param array $filters
     * @param int $page
     * @param int $limit
     * @return array
     * @throws ApiException
     * @throws ValidationException
     */
    public function searchProducts(array $filters, int $page = 1, int $limit = 10): array
    {

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
                'sortBy' => ['min_price,desc', 'min_price,asc', 'orders,desc', 'orders,asc', 'comments,desc', 'comments,asc']
            ])
            ->execute();

        $this->processResults($response);

        if (empty($this->results) || (
            !isset($this->results['aliexpress_ds_text_search_response'])) ||
            $this->results['aliexpress_ds_text_search_response']['msg'] ||
            $this->results['aliexpress_ds_text_search_response']['code'] != '00') {
            $this->logError('Failed to fetch products', $this->results);
            throw new ApiException('Failed to fetch products', 427);
        }
        try {

            $searchResults = $this->results['aliexpress_ds_text_search_response']['data'];
            $returnData = [
                'page' => (int) $searchResults['pageIndex'],
                'limit' => (int) $searchResults['pageSize'],
                'total' => (int) $searchResults['totalCount'],
                'products' => []
            ];

            foreach ($searchResults['products']['selection_search_product'] ?? [] as $productData) {
                $returnData['products'][] = new Product($productData);
            }
        return $returnData;
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to fetch products', 427, $e);
        }
    }

    /**
     * @param string $productId
     * @param array $params
     * @return SingleProduct
     * @throws ApiException
     * @throws ValidationException
     */
    public function findProductById(string $productId, array $params): SingleProduct
    {
        if (empty($productId)) {
            throw new ValidationException('Enter a product ID');
        }
        $query = [
            'product_id' => $productId,
            'target_language' => Localization::getInstance()->getLanguage(),
            'ship_to_country' => Localization::getInstance()->getCountryCode(),
            'target_currency' => Localization::getInstance()->getCurrency(),
            'remove_personal_benefit' => 'false',
        ];

        if (!empty($params['language'])) {
            $query['target_language'] = $params['language'];
        }
        if (!empty($params['country_code'])) {
            $query['ship_to_country'] = $params['country_code'];
        }
        if (!empty($params['currency'])) {
            $query['target_currency'] = $params['currency'];
        }
        if (isset($params['remove_personal_benefit'])) {
            $query['remove_personal_benefit'] = $params['remove_personal_benefit'];
        }
        $response = $this->apiClient
            ->requestName('aliexpress.ds.product.get')
            ->requestParams($query, [
                'ship_to_country' => Localization::getInstance()->getCountryCodes(),
                'target_currency' => Localization::getInstance()->getCurrencyCodes(),
                'target_language' => Localization::getInstance()->getLanguageCodes(),
                'remove_personal_benefit' => ['true', 'false']
            ])
            ->execute();

        $this->processResults($response);

        if (empty($this->results) || (!isset($this->results['aliexpress_ds_product_get_response'])) || $this->results['aliexpress_ds_product_get_response']['rsp_code'] != '200') {
            $this->logError('Failed to fetch product details', $this->results);
            throw new ApiException('Failed to fetch product details', 427);
        }
        try {
            return new SingleProduct($this->results['aliexpress_ds_product_get_response']['result']);

        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to fetch product details', 427, $e);
        }
    }

    /**
     * @param ?string $categoryId - fetch single category if not null
     * @return Categories|SingleCategory
     * @throws ApiException
     * @throws ValidationException
     */
    public function fetchCategories(?string $categoryId): Categories|SingleCategory
    {
        $query = [
            'language' => Localization::getInstance()->getLanguage(),
        ];

        if (!empty($categoryId)) {
            $query['categoryId'] = $categoryId;
        }

        $response = $this->apiClient
            ->requestName('aliexpress.ds.category.get')
            ->requestParams($query, [
                'language' => Localization::getInstance()->getLanguageCodes()
            ])
            ->execute();

        $this->processResults($response);
        if (empty($this->results) || (!isset($this->results['aliexpress_ds_category_get_response'])) || $this->results['aliexpress_ds_category_get_response']['resp_result']['resp_code'] != '200') {
            $this->logError('Failed to fetch categories', $this->results);
            throw new ApiException('Failed to fetch categories', 427);
        }
        try {
            $rawCategories = $this->results['aliexpress_ds_category_get_response']['resp_result']['result']['categories']['category'];
             return $categoryId ? new SingleCategory($rawCategories) : new Categories($rawCategories);

        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to fetch product categories', 427, $e);
        }
    }

    /**
     * @param array $filters
     * @return FreightOption[]
     * @throws ApiException
     * @throws ValidationException
     */
    public function fetchFreightOptions(array $filters): array
    {
        $query = $this->buildShippingQuery($filters);
        $response = $this->apiClient
            ->requestName('aliexpress.ds.freight.query')
            ->requestParams([
                'queryDeliveryReq' => json_encode($query)
            ])
            ->execute();

        $this->processResults($response);
        if (empty($this->results) || (!isset($this->results['aliexpress_ds_freight_query_response'])) || $this->results['aliexpress_ds_freight_query_response']['result']['code'] != '200') {
            $this->logError('Failed to freight options', $this->results);
            throw new ApiException('Failed to fetch freight options', 427);
        }
        try {
            $options = $this->results['aliexpress_ds_freight_query_response']['result']['delivery_options']['delivery_option_d_t_o'];
             return array_map(function($option) {
            return new FreightOption($option);
             }, $options);

        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to fetch product categories', 427, $e);
        }
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

    /**
     * Format query structure for Shipping API
     * @param array $filters
     * @return array
     * @throws ValidationException
     */
    public function buildShippingQuery(array $filters): array
    {
        if (empty($filters['product_id'])) {
            throw new ValidationException('Product id is required');
        }
        if (empty($filters['sku_id'])) {
            throw new ValidationException('Selected product sku is required');
        }
        if (empty($filters['quantity'])) {
            throw new ValidationException('Product quantity to be shipped is required');
        }

        $query = [
            'language' => Localization::getInstance()->getLanguage(),
            'locale' => Localization::getInstance()->getLanguage(),
            'shipToCountry' => Localization::getInstance()->getCountryCode(),
            'currency' => Localization::getInstance()->getCurrency(),
            'quantity' => $filters['quantity'],
            'productId' => $filters['product_id'],
            'selectedSkuId' => $filters['sku_id'],
        ];

        if (!empty($filters['currency'])) {
            $query['currency'] = $filters['currency'];
        }

        if (!empty($filters['country_code'])) {
            $query['shipToCountry'] = $filters['country_code'];
        }

        if (!empty($filters['language'])) {
            $query['language'] = $filters['language'];
            $query['locale'] = $filters['language'];
        }

        if (!empty($filters['locale'])) {
            $query['locale'] = $filters['locale'];
        }

        return $query;

    }

}
