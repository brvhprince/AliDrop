<?php
declare(strict_types=1);
/**
 * Project: AliDrop
 * File: Validator.php
 * Author: wanpeninsula
 * Organization: Colorbrace LLC
 * Author URI: https://www.pennycodes.dev
 * Created: 19/09/2024 at 10:12 pm
 *
 * Copyright (c) 2024 Colorbrace LLC. All rights reserved.
 */

namespace Wanpeninsula\AliDrop\Helpers;

use Contracts\ValidationInterface;
use Exceptions\ValidationException;

class Validator implements ValidationInterface
{
    /**
     * Validate that a value is in a list of allowed values
     *
     * @param mixed $value The value to validate
     * @param array $allowedValues List of allowed values
     * @param string $fieldName Name of the field being validated (for error messages)
     * @throws ValidationException
     */
    public static function validateInList(string $value, array $allowedValues, string $fieldName): void
    {
        if (!in_array($value, $allowedValues, true)) {
            throw new ValidationException(
                "Invalid value for {$fieldName}. Allowed values are: " . implode(', ', $allowedValues),
                422,
                null,
                ['field' => $fieldName, 'value' => $value, 'allowed_values' => $allowedValues]
            );
        }
    }

    /**
     * Validate multiple fields against their respective allowed values
     *
     * @param array $data Associative array of field names and their values
     * @param array $rules Associative array of field names and their allowed values
     * @throws ValidationException
     */
    public static function validateMultiple(array $data, array $rules): void
    {
        $errors = [];

        foreach ($rules as $field => $allowedValues) {
            if (isset($data[$field])) {
                try {
                    self::validateInList($data[$field], $allowedValues, $field);
                } catch (ValidationException $e) {
                    $errors[$field] = $e->getMessage();
                }
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(
                "Validation failed for multiple fields",
                422,
                null,
                $errors
            );
        }
    }
}
