<?php
declare(strict_types=1);
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

namespace Wanpeninsula\AliDrop\Repositories;

use Wanpeninsula\AliDrop\Api\Client;
use Wanpeninsula\AliDrop\Contracts\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected Client $apiClient;
    protected array $results = [];

    public function __construct(Client $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function processResults(string|bool $response): void
    {
        $this->results = $response ? json_decode($response, true) : [];
    }


}
