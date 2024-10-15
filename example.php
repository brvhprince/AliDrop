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

//    $products = $aliDrop->products()->search([
//        'query' => 'iphone',
//    ]);
//    $product = $aliDrop->products()->single_product("1005005939127124");
//    $categories = $aliDrop->products()->categories();
//    $category = $aliDrop->products()->category("201376702");
    $deliveryOptions = $aliDrop->products()->deliveryOptions([
        "product_id" => "1005005939127124",
        "quantity" => 1,
        "sku_id" => "12000034939242508",
    ]);

    echo '<pre>';
    echo json_encode($deliveryOptions, JSON_PRETTY_PRINT);
    echo '</pre>';
} catch (ValidationException|ApiException $e) {
}

$callbackUrl = 'http://localhost:3030/callback.php';
$auth = "https://api-sg.aliexpress.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=$callbackUrl&client_id=$appKey";
$aut2 = "https://api-sg.aliexpress.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=http://localhost:3030/callback.php&client_id=509710";
