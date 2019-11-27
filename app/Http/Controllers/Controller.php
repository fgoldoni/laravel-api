<?php

namespace App\Http\Controllers;

use App\Flag;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function responseJson(array $result = []): JsonResponse
    {
        $result['success'] = true;
        $result['status'] = Flag::STATUS_CODE_SUCCESS;

        return response()->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    protected function responseJsonError(Exception $e): JsonResponse
    {
        Log::error($e);

        $result = [
            'success'   => false,
            'exception' => \get_class($e),
            'message'   => $e->getMessage(),
            'status'    => $e->getCode(),
        ];

        return response()->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }
}
