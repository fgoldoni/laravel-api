<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class BaseException extends Exception
{
    /**
     * @var int
     */
    protected $httpStatusCode = 500;

    /**
     * @var int
     */
    protected $errorCode = 500;

    /**
     * @var string
     */
    protected $errorMessage;


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

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
