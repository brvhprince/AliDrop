<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: ValidationInterface.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:39 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Contracts;

use Exceptions\ValidationException;

interface ValidationInterface
{
    /**
     * Validate that a value is in a list of allowed values
     *
     * @param mixed $value The value to validate
     * @param array $allowedValues List of allowed values
     * @param string $fieldName Name of the field being validated (for error messages)
     * @throws ValidationException
     */
    public static function validateInList(string $value, array $allowedValues, string $fieldName): void;

    /**
     * Validate multiple fields against their respective allowed values
     *
     * @param array $data Associative array of field names and their values
     * @param array $rules Associative array of field names and their allowed values
     * @throws ValidationException
     */
    public static function validateMultiple(array $data, array $rules): void;

}
