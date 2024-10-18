<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: ApiClientInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 9:08 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Contracts;

use Wanpeninsula\AliDrop\Exceptions\ApiException;

interface ApiClientInterface
{
    /**
     * @param string $appKey
     * @param string $secretKey
     * @param string $accessToken
     * @throws ApiException
     */
    public function __construct(string $appKey, string $secretKey, string $accessToken);

    public function requestName(string $name);

    public function requestParams(array $params, array $validationRules);
    function requestParam(string $key, string $value);
    public function execute();

    public function readTimeout(string $timeout);
    public function setBaseUrl(string $url): void;

}
