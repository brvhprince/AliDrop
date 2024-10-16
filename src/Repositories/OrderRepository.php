<?php
/**
 * Project: AliDrop
 * File: OrderRepository.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 16/10/2024 at 9:56 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Repositories;

use Wanpeninsula\AliDrop\Contracts\OrderRepositoryInterface;
use Wanpeninsula\AliDrop\Helpers\Localization;
use Wanpeninsula\AliDrop\Models\ExternalOrderItem;
use Wanpeninsula\AliDrop\Models\OrderDetails;
use Wanpeninsula\AliDrop\Models\TrackingDetails;
use Wanpeninsula\AliDrop\Traits\LoggerTrait;
use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Exceptions\ValidationException;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    use LoggerTrait;

    /**
     * Place an order
     * @param array $params
     * @return array{int}
     * @throws ValidationException|ApiException
     */
    public function placeOrder(array $params): array
    {

        $query = [
            'logistics_address' => $this->buildLogisticsAddress($params),
            'product_items' => $this->buildProductItems($params['items']),
        ];
        if (!empty($params['order_id'])) {
            $query['out_order_id'] = $params['order_id']; // outer order id, used for idempotent checkout
        }

        $response = $this->apiClient
            ->requestName('aliexpress.ds.order.create')
            ->requestParams([
                'param_place_order_request4_open_api_d_t_o' => json_encode($query)
            ])
            ->execute();

        $this->processResults($response);
        if (empty($this->results) || (!isset($this->results['aliexpress_ds_order_create_response'])) || !$this->results['aliexpress_ds_order_create_response']['result']['is_success']) {
            $this->logError('Failed to place order', $this->results);
            throw new ApiException('Failed to place order', 427);
        }
        try {
            return $this->results['aliexpress_ds_order_create_response']['result']['order_list'];

        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to place order', 427, $e);
        }
    }

    /**
     * Get order details
     * @param int $order_id
     * @return OrderDetails
     * @throws ValidationException
     * @throws ApiException
     */
    public function getOrder(int $order_id): OrderDetails
    {
        if (empty($order_id)) {
            throw new ValidationException('Order ID is required');
        }
        $query = [
            'order_id' => $order_id,
        ];

        $response = $this->apiClient
            ->requestName('aliexpress.trade.ds.order.get')
            ->requestParams([
                'single_order_query' => json_encode($query)
            ])
            ->execute();

        $this->processResults($response);
        if (empty($this->results) || (!isset($this->results['aliexpress_trade_ds_order_get_response'])) || !$this->results['aliexpress_trade_ds_order_get_response']['result']) {
            $this->logError('Failed to fetch order details', $this->results);
            throw new ApiException('Failed to fetch order details', 427);
        }
        try {
            $res = $this->results['aliexpress_trade_ds_order_get_response']['result'];
            return new OrderDetails($res);

        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to fetch order details', 427, $e);
        }
    }

    /**
     * Get order details
     * @param int $order_id
     * @param ?string $language
     * @return TrackingDetails
     * @throws ValidationException
     * @throws ApiException
     */
    public function trackOrder(int $order_id, ?string $language): TrackingDetails
    {
        if (empty($order_id)) {
            throw new ValidationException('Order ID is required');
        }
        $query = [
            'ae_order_id' => $order_id,
            'language' => Localization::getInstance()->getLanguage(),
        ];
        if ($language !==null) {
            $query['language'] = $language;
        }

        $response = $this->apiClient
            ->requestName('aliexpress.ds.order.tracking.get')
            ->requestParams($query, [
                'language' => Localization::getInstance()->getLanguageCodes()
            ])
            ->execute();

        $this->processResults($response);
        if (empty($this->results) || (!isset($this->results['aliexpress_ds_order_tracking_get_response'])) || $this->results['aliexpress_ds_order_tracking_get_response']['result']['ret'] !== true) {
            $this->logError('Failed to fetch order tracking details', $this->results);
            throw new ApiException('Failed to fetch order tracking details', 427);
        }
        try {
            $res = $this->results['aliexpress_ds_order_tracking_get_response']['result']['data'];
            // log
            $this->logInfo('Order tracking details', $res);
            return new TrackingDetails($res['tracking_detail_line_list']['tracking_detail'][0]);

        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            throw new ApiException('Failed to fetch order tracking details', 427, $e);
        }
    }

    /**
     * Prepare shipping address for order
     * @param array $params
     * @return array
     * @throws ValidationException
     */
    private function buildLogisticsAddress(array $params): array
    {
        $requiredFields = ['address', 'city', 'country', 'full_name', 'phone_number', 'province', 'phone_code'];

        foreach ($requiredFields as $field) {
            if (!isset($params[$field])) {
                throw new ValidationException("Field $field is required");
            }
        }

        $logisticsAddress = [
            'address' => $params['address'],
            'city' => $params['city'],
            'contact_person' => $params['contact_person'] ?? $params['full_name'],
            'country' => $params['country'],
            'full_name' => $params['full_name'],
            'mobile_no' => $params['phone_number'],
            'phone_country' => $params['phone_code'],
            'province' => $params['province'],
        ];

        $locale = $params['locale'] ?? null;
        if ($locale) {
            $logisticsAddress['locale'] = $locale;
        }

        $address2 = $params['address2'] ?? null;
        if ($address2) {
            $logisticsAddress['address2'] = $address2;
        }

        $locationTreeAddressId = $params['location_tree_address_id'] ?? null;
        if ($locationTreeAddressId) {
            $logisticsAddress['location_tree_address_id'] = $locationTreeAddressId;
        }

        $cpf = $params['cpf'] ?? null; //required for Brazil
        if ($cpf) {
            $logisticsAddress['cpf'] = $cpf;
        }

        $rutNo = $params['rut_no'] ?? null; //required for Chile
        if ($rutNo) {
            $logisticsAddress['rut_no'] = $rutNo;
        }

        $postcode = $params['postcode'] ?? null;
        if ($postcode) {
            $logisticsAddress['zip'] = $postcode;
        }

        $passportNo = $params['passport_no'] ?? null;
        if ($passportNo) {
            $logisticsAddress['passport_no'] = $passportNo;
        }

        $passportNoDate = $params['passport_no_date'] ?? null;
        if ($passportNoDate) {
            $logisticsAddress['passport_no_date'] = $passportNoDate;
        }

        $passportOrganization = $params['passport_organization'] ?? null;
        if ($passportOrganization) {
            $logisticsAddress['passport_organization'] = $passportOrganization;
        }

        $taxNumber = $params['tax_number'] ?? null;
        if ($taxNumber) {
            $logisticsAddress['tax_number'] = $taxNumber;
        }

        $foreignerPassportNo = $params['foreigner_passport_no'] ?? null;
        if ($foreignerPassportNo) {
            $logisticsAddress['foreigner_passport_no'] = $foreignerPassportNo;
        }

        $isForeigner = $params['is_foreigner'] ?? null;
        if ($isForeigner) {
            $logisticsAddress['is_foreigner'] = $isForeigner;
        }

        $vatNo = $params['vat_no'] ?? null;
        if ($vatNo) {
            $logisticsAddress['vat_no'] = $vatNo;
        }

        $taxCompany = $params['tax_company'] ?? null;
        if ($taxCompany) {
            $logisticsAddress['tax_company'] = $taxCompany;
        }

        return $logisticsAddress;
    }

    /**
     * Prepare order items
     * @param array $items
     * @return array
     * @throws ValidationException
     */
    private function buildProductItems(array $items): array
    {
        $externalOrderItems = [];
        $requiredFields = ['product_id', 'quantity', 'sku_attr'];
        foreach ($items as $item) {
            foreach ($requiredFields as $field) {
                if (!isset($item[$field])) {
                    throw new ValidationException("Field $field is required");
                }
            }
            $externalOrderItem = new ExternalOrderItem(
                $item['product_id'],
                $item['sku_attr'],
                $item['quantity'],
                $item['shipping_service'],
                $item['comment']
            );
            $externalOrderItems[] = [
                'product_id' =>$externalOrderItem->getProductId(),
                'sku_attr' => $externalOrderItem->getSkuId(),
                'product_count' => $externalOrderItem->getQuantity(),
                'logistics_service_name' => $externalOrderItem->getLogisticsServiceName(),
                'order_memo' => $externalOrderItem->getComments(),
            ];
        }
        return $externalOrderItems;
    }

}
