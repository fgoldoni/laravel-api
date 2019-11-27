<?php

namespace Modules\Roles\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function __construct(RolesService $rolesService, Translator $lang, Str $str, AuthManager $auth)
    {
        $this->rolesService = $rolesService;
        $this->lang = $lang;
        $this->str = $str;
        $this->auth = $auth;
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
            $data = $this->rolesService->paginate($request);

            return $this->responseJson(['data' => $data]);
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
