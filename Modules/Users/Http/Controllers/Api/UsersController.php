<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Flag;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Modules\Roles\Services\Contracts\RolesServiceInterface;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Http\Requests\ApiUpdateUserRequest;
use Modules\Users\Services\Contracts\UsersServiceInterface;
use Modules\Users\Transformers\UsersCollection;

class UsersController extends Controller
{
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
     * @var \Modules\Users\Services\Contracts\UsersServiceInterface
     */
    private $usersService;
    /**
     * @var \Illuminate\Log\Logger
     */
    private $logger;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Modules\Roles\Services\Contracts\RolesServiceInterface
     */
    private $rolesService;

    public function __construct(UsersServiceInterface $usersService, RolesServiceInterface $rolesService, ResponseFactory $response, Translator $lang, Str $str, Logger $logger, AuthManager $auth)
    {
        $this->response = $response;
        $this->lang = $lang;
        $this->usersService = $usersService;
        $this->logger = $logger;
        $this->str = $str;
        $this->auth = $auth;
        $this->rolesService = $rolesService;
    }

    public function getUsers(): JsonResponse
    {
        try {
            $data = UsersCollection::collection($this->usersService->getUsers());

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = [];
        try {
            $result['data'] = $this->usersService->paginate($request);

            return $this->response->json($result['data'], Flag::STATUS_CODE_SUCCESS, [], JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            $user = $this->usersService->transform(
                $this->usersService->firstOrNew([
                    'first_name' => '',
                    'last_name'  => '',
                    'email'      => '',
                ])
            );

            return $this->responseJson(['user' => $user]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiStoreUserRequest $request
     *
     * @return JsonResponse
     */
    public function store(ApiStoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->usersService->storeUser($request);
            $this->usersService->sync($user->id, 'roles', $request->get('roles'));

            $result['user'] = $user;
            $result['message'] = $this->lang->get('messages.created', ['attribute' => $user->full_name]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }


    public function show(int $id)
    {
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $user = $this->usersService->findUser($id);
            $result['user'] = $this->usersService->transform($user);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiUpdateUserRequest $request
     * @param  $id
     *
     * @return JsonResponse
     */
    public function update(ApiUpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $result['user'] = $this->usersService->updateUser($request, $id);
            $result['message'] = $this->lang->get('messages.updated', ['attribute' => $result['user']->first_name]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $result['user'] = $this->usersService->delete($id);
            $result['message'] = $this->lang->get('messages.deleted', ['attribute' => $result['user']->first_name]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        try {
            $result['user'] = $this->usersService->forceDelete($id);
            $result['message'] = $this->lang->get('messages.destroyed', ['attribute' => $result['user']->first_name]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $result['user'] = $this->usersService->restore($id);
            $result['message'] = $this->lang->get('messages.restored', ['attribute' => $result['user']->first_name]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
