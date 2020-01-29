<?php

namespace App\Exceptions;

use App\ErrorCodes;
use App\Flag;

/**
 * Class AttachmentException.
 */
class AttachmentException extends BaseException
{
    public static function notFileReceiveException(): self
    {
        return static::create(
            'This content can not receive a file',
            ErrorCodes::ERROR_FILE_NOT_RECEIVE,
            Flag::STATUS_CODE_ERROR
        );
    }

    public static function undefinedMethodException($model): self
    {
        return static::create(
            "Method attachments undefined of {$model}",
            ErrorCodes::ERROR_FILE_NOT_RECEIVE,
            Flag::STATUS_CODE_ERROR
        );
    }
}
