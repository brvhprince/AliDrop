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

namespace Wanpeninsula\AliDrop\Services;

use Wanpeninsula\AliDrop\Api\Client;
use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Exceptions\ValidationException;
use Wanpeninsula\AliDrop\Repositories\OrderRepository;

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
     *     sku_id: string,
     *     shipping_service?: string,
     *     comment?: string
     *    }>
     * } $params
     * @return array{int}
     * @throws ValidationException|ApiException
     */
    public function placeOrder(array $params): array
    {
        return $this->orderRepository->placeOrder($params);
    }

}
