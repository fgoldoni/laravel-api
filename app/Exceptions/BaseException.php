<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class BaseException extends Exception
{
    protected $httpStatusCode = 500;

    protected $errorCode;

    protected $errorMessage;

    /**
     * BaseException constructor.
     *
     * @param                 $errorMessage
     * @param int             $errorCode
     * @param int             $httpStatusCode
     * @param \Throwable|null $previous
     */
    public function __construct(
        $errorMessage = null,
        $errorCode = 0,
        $httpStatusCode = 0,
        \Throwable $previous = null
    ) {
        Log::error($this);

        $this->errorMessage = $errorMessage ?: $this->errorMessage;
        $this->errorCode = $errorCode ?: $this->errorCode;
        $this->httpStatusCode = $httpStatusCode ?: $this->httpStatusCode;

        parent::__construct($this->errorMessage, $this->httpStatusCode, $previous);
    }

    public function getResponse(): JsonResponse
    {
        $exceptionName = (new ReflectionClass($this))->getShortName();

        return response()->json([
            'message'   => $this->getErrorMessage(),
            'exception' => $exceptionName,
            'success'   => false,
            'code'      => $this->getErrorCode(),
            'status'    => $this->getHttpStatusCode(),
        ], $this->getHttpStatusCode(), [
            'X-Error-Code'      => $this->getErrorCode(),
            'X-Error-Message'   => $this->getErrorMessage(),
            'X-Error-Exception' => $exceptionName
        ]);
    }

    public static function create($message = null, $errorCode = 0, $httpStatusCode = 400)
    {
        return new static($message, $errorCode, $httpStatusCode);
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
