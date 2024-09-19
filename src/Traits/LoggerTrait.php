<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: LoggerTrait.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:01 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Traits;

use Helpers\Logger;
trait LoggerTrait
{
    protected function log($level, $message, array $context = []): void
    {
        Logger::getInstance()->log($level, $message, $context);
    }

    protected function logError($message, array $context = []): void
    {
        Logger::getInstance()->error($message, $context);
    }

    protected function logInfo($message, array $context = []): void
    {
        Logger::getInstance()->info($message, $context);
    }

    protected function logDebug($message, array $context = []): void
    {
        Logger::getInstance()->debug($message, $context);
    }

    protected function logWarning($message, array $context = []): void
    {
        Logger::getInstance()->warning($message, $context);
    }

}
