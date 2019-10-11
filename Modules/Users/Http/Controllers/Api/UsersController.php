<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Flag;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Http\Requests\ApiUpdateUserRequest;
use Modules\Users\Services\Contracts\UsersServiceInterface;

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

    public function __construct(UsersServiceInterface $usersService, ResponseFactory $response, Translator $lang, Str $str, Logger $logger)
    {
        $this->response = $response;
        $this->lang = $lang;
        $this->str = $str;
        $this->usersService = $usersService;
        $this->logger = $logger;
    }

    public function getUsers(): JsonResponse
    {
        try {
            $result['data'] = $this->usersService->getUsers();
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e);
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function paginate(Request $request): JsonResponse
    {
        try {
            $result['data'] = $this->usersService->paginate($request);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e);
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result['data'], $result['status'], [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            $user = $this->usersService->firstOrNew([
                'first_name' => '',
                'last_name'  => '',
                'email'      => '',
            ]);

            $result['user'] = $this->usersService->transform($user);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_PRESERVE_ZERO_FRACTION);
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
            $user = $this->usersService->store($request);

            $result['user'] = $user;
            $result['message'] = $this->lang->get('messages.created', ['attribute' => $user->full_name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_CREATED;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id)
    {
        return view('users::show');
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $user = $this->usersService->find($id);

            $result['user'] = $this->usersService->transform($user);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_PRESERVE_ZERO_FRACTION);
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
            $user = $this->usersService->update($request, $id);

            $result['user'] = $user;
            $result['message'] = $this->lang->get('messages.updated', ['attribute' => $user->first_name]);
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
            $result['user'] = $this->usersService->delete($id);
            $result['message'] = $this->lang->get('messages.deleted', ['attribute' => $result['user']->first_name]);
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
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
            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_ERROR;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
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
