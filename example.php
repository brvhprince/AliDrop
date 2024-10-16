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
//    $product = $aliDrop->products()->single_product("1005007773422239");
//    $categories = $aliDrop->products()->categories();
//    $category = $aliDrop->products()->category("201376702");
//    $deliveryOptions = $aliDrop->products()->deliveryOptions([
//        "product_id" => "1005005939127124",
//        "quantity" => 1,
//        "sku_id" => "12000034939242508",
//    ]);

    $kuAttr = "14:10#PL2303;200000555:200006444#3";

    $egOrderNumber = "3043208031562816";
    $egOrderNumber2 = "3043104342182816";
    $egOrderNumber3 = "3043170441622816";
//    $placeOrder = $aliDrop->orders()->place_order([
//       "order_id" => "abc12344997aw",
//        "address" => "Abesim, Olistar SHS off Nkrankrom Road",
//        "city" => "Sunyani",
//        "province" => "Bono Region",
//        "postcode" => "00233",
//        "phone_number" => "553872291",
//        "phone_code" => "233",
//        "full_name" => "Prince Takyi Akomea",
//        "country" => "GH",
//        "items" => [
////            [
////                "product_id" => 1005005939127124,
////                "quantity" => 1,
////                "sku_attr" => $kuAttr, # sku attribute not sku id
////                "shipping_service" => "CAINIAO_FULFILLMENT_STD",
////                "comment" => "Please handle with care. This is a dropshipping order please do not include any promotional materials."
////            ],
//            [
//                "product_id" => 1005007773422239,
//                "quantity" => 1,
//                "sku_attr" => "136:173#Spiral shape", # sku attribute not sku id
//                "shipping_service" => "CAINIAO_FULFILLMENT_STD",
//                "comment" => "Please handle with care. This is a dropshipping order please do not include any promotional materials."
//            ]
//        ]
//    ]);

    $order = $aliDrop->orders()->order_details($egOrderNumber3);

    echo '<pre>';
    echo json_encode($order, JSON_PRETTY_PRINT);
    echo '</pre>';
} catch (ValidationException|ApiException $e) {
}

$callbackUrl = 'http://localhost:3030/callback.php';
$auth = "https://api-sg.aliexpress.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=$callbackUrl&client_id=$appKey";
$aut2 = "https://api-sg.aliexpress.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=http://localhost:3030/callback.php&client_id=509710";
