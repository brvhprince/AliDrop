<?php
/**
 * Project: AliDrop
 * File: OrderRepositoryInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 16/10/2024 at 9:57 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Contracts;

use Wanpeninsula\AliDrop\Models\OrderDetails;

interface OrderRepositoryInterface
{
    public function placeOrder(array $params): array;
    public function getOrder(int $order_id): OrderDetails;

}
