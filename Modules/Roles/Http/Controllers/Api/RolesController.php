<?php

namespace Modules\Roles\Http\Controllers\Api;

use App\Flag;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Modules\Roles\Http\Requests\ApiStoreRoleRequest;
use Modules\Roles\Http\Requests\ApiUpdateRoleRequest;
use Modules\Roles\Services\Contracts\RolesServiceInterface;
use Modules\Roles\Transformers\RolesCollection;

class RolesController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;
    /**
     * @var \Illuminate\Support\Str
     */
    private $str;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Modules\Roles\Services\Contracts\RolesServiceInterface
     */
    private $rolesService;
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;

    public function __construct(RolesServiceInterface $rolesService, ResponseFactory $response, Translator $lang, Str $str, AuthManager $auth)
    {
        $this->lang = $lang;
        $this->str = $str;
        $this->auth = $auth;
        $this->rolesService = $rolesService;
        $this->response = $response;
    }

    public function getRoles()
    {
        try {
            $data = RolesCollection::collection($this->rolesService->getRoles());

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function paginate(Request $request): JsonResponse
    {
        try {
            $result['data'] = $this->rolesService->paginate($request);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;

            return $this->response->json($result['data'], $result['status'], [], JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $role = $this->rolesService->getRole($id);

            return $this->responseJson(['role' => $this->rolesService->transform($role)]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(ApiStoreRoleRequest $request): JsonResponse
    {
        try {
            $result = [
                'role'    => $this->rolesService->storeRole($request),
                'message' => $this->lang->get('messages.created', ['attribute' => 'Role'])
            ];

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function update(ApiUpdateRoleRequest $request, $id): JsonResponse
    {
        try {
            $result = [
                'role'    => $this->rolesService->updateRole($request, $id),
                'message' => $this->lang->get('messages.updated', ['attribute' => 'Role'])
            ];

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result = [
                'role'    => $this->rolesService->delete($id),
                'message' => $this->lang->get('messages.deleted', ['attribute' => 'Role'])
            ];

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function restore(int $id): JsonResponse
    {
        try {
            $result = [
                'role'    => $this->rolesService->restore($id),
                'message' => $this->lang->get('messages.restored', ['attribute' => 'Role'])
            ];

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function forceDelete(int $id): JsonResponse
    {
        try {
            $result = [
                'role'    => $this->rolesService->forceDelete($id),
                'message' => $this->lang->get('messages.destroyed', ['attribute' => 'Role'])
            ];

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
