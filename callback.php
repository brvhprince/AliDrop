<?php
/**
 * Project: AliDrop
 * File: c.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 13/10/2024 at 8:58 pm
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

    $code = $_GET['code'] ?? null;
    if ($code !== null) {
       $aliDrop->token()->generateAccessToken($code);
        echo "Access token: {$aliDrop->token()->access_token}";
    } else {
        echo "Current access token: {$aliDrop->token()->access_token}";
        echo "<br/> <br/>";
        $link = $aliDrop->token()->generateAccessLink();
        echo $link;
    }


} catch (ValidationException|ApiException $e) {
    echo $e->getMessage();
    exit;
}
