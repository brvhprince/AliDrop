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

use Wanpeninsula\AliDrop\Exceptions\ApiException;
use Wanpeninsula\AliDrop\Api\Client;
use Wanpeninsula\AliDrop\Services\ProductService;

class AliDrop
{

    private Client $apiClient;

    /**
     * @throws ApiException
     */
    public function __construct(string $appKey, string $secretKey)
    {
        $this->apiClient = new Client($appKey, $secretKey);
    }

    public function products(): ProductService
    {
        return new ProductService($this->apiClient);
    }
    public function setBaseUrl(string $url): void
    {
        $this->apiClient->setBaseUrl($url);
    }

}
