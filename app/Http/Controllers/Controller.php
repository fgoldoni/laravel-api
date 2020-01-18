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

    protected function responseJson(array $result = [], int $status = Flag::STATUS_CODE_SUCCESS): JsonResponse
    {
        $result['success'] = true;
        $result['status'] = $status;

        return response()->json($result, $result['status'], [], JSON_PRESERVE_ZERO_FRACTION);
    }

    protected function responseJsonError(Exception $e): JsonResponse
    {
        if (method_exists(\get_class($e), 'getResponse')) {
            return $e->getResponse();
        }

        Log::error($e);

        $statusCode = (0 !== $e->getCode()) && (is_numeric($e->getCode())) ? $e->getCode() : Flag::STATUS_CODE_ERROR;

        $result = [
            'success'   => false,
            'exception' => \get_class($e),
            'message'   => $e->getMessage(),
            'status'    => $statusCode,
        ];

        return response()->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function parseRequest($request)
    {
        return [
            $request->get('per_page', 10),
            explode('|', $request->get('sort', 'id|asc')),
            $request->get('filter')
        ];
    }
}
