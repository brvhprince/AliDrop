<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: ValidationException.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:09 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Exceptions;

use ReturnTypeWillChange;

class ValidationException extends \Exception
{
    protected ?array $extra = null;

    public function __construct($message = "", $code = 422, \Throwable $previous = null, array $extra = null)
    {
        parent::__construct($message, $code, $previous);
        $this->extra = $extra;
    }

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function setExtra(?array $extra): void
    {
        $this->extra = $extra;
    }

    #[ReturnTypeWillChange] public function __toString() {
        if ($this->extra) {
            return __CLASS__ . ": [{$this->code}]: {$this->message} \n [Extra data]: "
                . print_r($this->extra, true) . "\n";
        } else {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
    }


}
