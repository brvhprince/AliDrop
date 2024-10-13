<?php
/**
 * Project: AliDrop
 * File: example.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 11:04 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

require_once __DIR__ . '/vendor/autoload.php';

use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Exceptions\ValidationException;
use Wanpeninsula\AliDrop\AliDrop;

$appKey = '509710';
$appSecret = 'dByevByjWtcMXs6pLyabAus3RxLXL965';

try {
    $aliDrop = new AliDrop($appKey, $appSecret);

    $products = $aliDrop->products()->search([
        'query' => 'iphone',
    ]);

    var_dump($products);
} catch (ValidationException|ApiException $e) {
}
