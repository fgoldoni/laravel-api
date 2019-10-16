<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class ErrorException.
 */
class TestErrorException extends Exception
{
    /**
     * Report or log an exception.
     */
    public function report()
    {
        Log::error($this->getMessage());
    }
}
