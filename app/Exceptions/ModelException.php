<?php

namespace App\Exceptions;

use App\ErrorCodes;

/**
 * Class NotModelException.
 */
class ModelException extends BaseException
{
    public static function notModelException($model): self
    {
        return static::create(
            "Class {$model} must be an instance of Illuminate\\Database\\Eloquent\\Model",
            ErrorCodes::ERROR_CODE_NOT_MODEL,
            500
        );
    }
}
