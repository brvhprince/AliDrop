<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: RepositoryInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 21/09/2024 at 11:42 am
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Pennycodes\AliDrop\Contracts;

interface RepositoryInterface
{
    public function buildQuery(array $filters, int $page = 1, int $limit = 10): array;
}
