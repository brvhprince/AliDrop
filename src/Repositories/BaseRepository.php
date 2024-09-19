<?php
/**
 * Project: AliDrop
 * File: BaseRepository.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 11:02 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Repositories;

use Api\Client;
use Contracts\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected Client $apiClient;

    public function __construct(Client $apiClient)
    {
        $this->apiClient = $apiClient;
    }


}
