<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: ApiException.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 9:24 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Exceptions;

use ReturnTypeWillChange;

class ApiException extends \Exception
{
    public function __construct($message, $code, \Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    #[ReturnTypeWillChange] public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}
