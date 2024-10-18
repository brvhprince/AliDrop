<?php
/**
 * Project: AliDrop
 * File: OrderService.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 16/10/2024 at 10:40 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Services;

use Pennycodes\AliDrop\Api\Client;
use Pennycodes\AliDrop\Exceptions\ApiException;
use Pennycodes\AliDrop\Exceptions\ValidationException;
use Pennycodes\AliDrop\Models\OrderDetails;
use Pennycodes\AliDrop\Models\TrackingDetails;
use Pennycodes\AliDrop\Repositories\OrderRepository;

class OrderService
{
protected OrderRepository $orderRepository;
    public function __construct(Client $client)
    {
        $this->orderRepository = new OrderRepository($client);
    }

    /**
     * Places an order
     * @param array{
     *     order_id: string,
     *     address: string,
     *     city: string,
     *     country: string,
     *     full_name: string,
     *     phone_number: string,
     *     province: string,
     *     phone_code: string,
     *     address2?: string,
     *     contact_person?: string,
     *     locale?: string,
     *     location_tree_address_id?: string,
     *     cpf?: string,
     *     rut_no?: string,
     *     postcode?: string,
     *     passport_no?: string,
     *     passport_no_date?: string,
     *     passport_organization?: string,
     *     tax_number?: string,
     *     foreigner_passport_no?: string,
     *     is_foreigner?: string,
     *     vat_no?: string,
     *     tax_company?: string,
     *     items: list<array{
     *     product_id: int,
     *     quantity: int,
     *     sku_attr: string,
     *     shipping_service?: string,
     *     comment?: string
     *    }>
     * } $params
     * @return array{int}
     * @throws ValidationException|ApiException
     */
    public function place_order(array $params): array
    {
        return $this->orderRepository->placeOrder($params);
    }

    /**
     * Get order details
     * @param int $order_id
     * @return OrderDetails
     * @throws ApiException|ValidationException
     */
    public function order_details(int $order_id): OrderDetails
    {
        return $this->orderRepository->getOrder($order_id);
    }
    /**
     * Get order details
     * @param int $order_id
     * @param ?string $language
     * @return TrackingDetails
     * @throws ApiException|ValidationException
     */
    public function track_order(int $order_id, ?string $language = null): TrackingDetails
    {
        return $this->orderRepository->trackOrder($order_id, $language);
    }

}
