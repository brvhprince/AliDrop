<?php
/**
 * Project: AliDrop
 * File: AliDrop.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 21/09/2024 at 12:06 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop;

use Wanpeninsula\AliDrop\Api\Token;
use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Api\Client;
use Wanpeninsula\AliDrop\Helpers\Localization;
use Wanpeninsula\AliDrop\Helpers\Utils;
use Wanpeninsula\AliDrop\Services\OrderService;
use Wanpeninsula\AliDrop\Services\ProductService;

class AliDrop
{

    private Client $apiClient;
    private Token $token;

    /**
     * @throws ApiException
     */
    public function __construct(string $appKey, string $secretKey)
    {
        $this->token = new Token($appKey, $secretKey);
        $this->apiClient = new Client($appKey, $secretKey, $this->token->access_token);
    }

    public function products(): ProductService
    {
        return new ProductService($this->apiClient);
    }
    public static function utils(): Utils
    {
        return new Utils();
    }
    public function localization(): Localization
    {
        return new Localization();
    }
    public function token(): Token
    {
        return $this->token;
    }
    public function orders(): OrderService
    {
        return new OrderService($this->apiClient);
    }
    public function setBaseUrl(string $url): void
    {
        $this->apiClient->setBaseUrl($url);
    }

}
