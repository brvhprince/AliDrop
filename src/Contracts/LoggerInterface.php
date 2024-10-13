<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: LoggerInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:40 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Contracts;

interface LoggerInterface
{
    public function log(string $level, string $message, array $context = []): void;

    public function error(string $message, array $context = []): void;

    public function info(string $message, array $context = []): void;

    public function debug(string $message, array $context = []): void;

    public function warning(string $message, array $context = []): void;

}
