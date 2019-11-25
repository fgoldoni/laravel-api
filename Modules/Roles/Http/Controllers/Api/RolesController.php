<?php

namespace Modules\Roles\Http\Controllers\Api;

use App\Flag;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Modules\Roles\Http\Requests\ApiStoreRoleRequest;
use Modules\Roles\Http\Requests\ApiUpdateRoleRequest;
use Modules\Roles\Services\RolesService;
use Modules\Roles\Transformers\RolesCollection;

class RolesController extends Controller
{
    /**
     * @var \Modules\Roles\Services\RolesService
     */
    private $rolesService;
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;
    /**
     * @var \Illuminate\Support\Str
     */
    private $str;
    /**
     * @var \Illuminate\Log\Logger
     */
    private $logger;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    public function __construct(RolesService $rolesService, ResponseFactory $response, Translator $lang, Str $str, Logger $logger, AuthManager $auth)
    {
        $this->rolesService = $rolesService;
        $this->response = $response;
        $this->lang = $lang;
        $this->str = $str;
        $this->logger = $logger;
        $this->auth = $auth;
    }

    public function getRoles()
    {
        try {
            $result['data'] = RolesCollection::collection($this->rolesService->all());
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e);
            $result['success'] = false;
            $result['exception'] = \get_class($e);
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function paginate(Request $request): JsonResponse
    {
        try {
            $result['data'] = $this->rolesService->paginate($request);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e);
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;

            return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
        }

        return $this->response->json($result['data'], $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $role = $this->rolesService->find($id);

            $result['role'] = $role;
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_PRESERVE_ZERO_FRACTION);
    }

    public function store(ApiStoreRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->rolesService->storeRole($request);

            $result['role'] = $role;
            $result['message'] = $this->lang->get('messages.created', ['attribute' => $role->name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_CREATED;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function update(ApiUpdateRoleRequest $request, $id): JsonResponse
    {
        try {
            $role = $this->rolesService->updateRole($request, $id);

            $result['role'] = $role;
            $result['message'] = $this->lang->get('messages.updated', ['attribute' => $role->name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }


    public function destroy(int $id): JsonResponse
    {
        try {
            $result['role'] = $this->rolesService->delete($id);
            $result['message'] = $this->lang->get('messages.deleted', ['attribute' => $result['role']->name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $result['role'] = $this->rolesService->restore($id);
            $result['message'] = $this->lang->get('messages.restored', ['attribute' => $result['role']->name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function forceDelete(int $id): JsonResponse
    {
        try {
            $result['role'] = $this->rolesService->forceDelete($id);
            $result['message'] = $this->lang->get('messages.destroyed', ['attribute' => $result['role']->name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }
}
