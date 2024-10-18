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

namespace Pennycodes\AliDrop\Contracts;

use Pennycodes\AliDrop\Models\OrderDetails;
use Pennycodes\AliDrop\Models\TrackingDetails;

interface OrderRepositoryInterface
{
    public function placeOrder(array $params): array;
    public function getOrder(int $order_id): OrderDetails;
    public function trackOrder(int $order_id, ?string $language): TrackingDetails;

}
