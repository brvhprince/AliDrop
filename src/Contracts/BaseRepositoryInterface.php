<?php
/**
 * Project: AliDrop
 * File: BaseRepositoryInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 11:03 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Contracts;

use Wanpeninsula\AliDrop\Api\Client;

interface BaseRepositoryInterface
{
    public function __construct(Client $apiClient);

}
