<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Flag;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Log\Logger;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Users\Http\Requests\ApiAccessTokenRequest;
use Modules\Users\Http\Requests\ApiLinkRequest;
use Modules\Users\Http\Requests\ApiRegisterRequest;
use Modules\Users\Http\Requests\ApiTokenRequest;
use Modules\Users\Services\Contracts\UsersServiceInterface;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Modules\Users\Services\Contracts\UsersServiceInterface
     */
    private $usersService;
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Illuminate\Log\Logger
     */
    private $logger;
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    private $jwtAuth;

    public function __construct(UsersServiceInterface $usersService, ResponseFactory $response, Translator $lang, AuthManager $auth, Logger $logger, JWTAuth $jwtAuth)
    {
        $this->usersService = $usersService;
        $this->response = $response;
        $this->lang = $lang;
        $this->auth = $auth;
        $this->logger = $logger;
        $this->jwtAuth = $jwtAuth;
    }

    public function login(ApiTokenRequest $request): JsonResponse
    {
        $result = [];
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = $this->jwtAuth->attempt($credentials)) {
                $result['success'] = false;
                $result['status'] = Flag::STATUS_BAD_REQUEST;
                $result['message'] = 'Invalid credentials';
            } else {
                $result['accessToken'] = $token;
                $result['expiresIn'] = $this->jwtAuth->factory()->getTTL() * 60;

                return $this->responseJson($result, Flag::STATUS_CODE_SUCCESS)
                    ->cookie('accessToken', $result['accessToken'], $result['expiresIn']);
            }
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $result = [];

        try {
            $token = $this->jwtAuth->getToken();

            if (!$token) {
                $result['success'] = false;
                $result['message'] = 'Unauthorized';

                return $this->response->json($result, Flag::STATUS_CODE_UNAUTHORIZED, [], JSON_NUMERIC_CHECK);
            }
            $refreshedToken = $this->auth->guard()->refresh();
            $result['token'] = $refreshedToken;
            $result['expiresIn'] = $this->jwtAuth->factory()->getTTL() * 60;

            return $this->responseJson($result, Flag::STATUS_CODE_SUCCESS)
                ->cookie('accessToken', $refreshedToken, $result['expiresIn']);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function user(): JsonResponse
    {
        $result = [];

        try {
            $user = $this->usersService->find($this->auth->user()->id);
            $result['user'] = $this->usersService->authTransform($user);

            $result['success'] = true;
            $result['status'] = Flag::STATUS_CODE_SUCCESS;
        } catch (Exception $e) {
            $this->logger->error($e);
            $result['success'] = false;
            $result['exception'] = \get_class($e);
            $result['message'] = $e->getMessage();
            $result['status'] = Flag::STATUS_CODE_UNAUTHORIZED;
        }

        return $this->response->json($result, $result['status'], [], JSON_NUMERIC_CHECK);
    }

    public function link(ApiLinkRequest $request): JsonResponse
    {
        $result = [];

        try {
            $user = $this->usersService->getUserByEmail($request->get('email'));
            $this->usersService->sendLoginLink($user, $request->get('host'), $request->get('to'));

            $result['message'] = 'We have e-mailed your password reset link!';

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }

        return $this->response->json($result, 200, [], JSON_NUMERIC_CHECK);
    }

    public function magiclink(string $token): JsonResponse
    {
        $result = [];

        try {
            $result['message'] = 'We have e-mailed your password reset link!';

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function register(ApiRegisterRequest $request): JsonResponse
    {
        $user = $this->usersService->saveUser($request->only('email'))->makeVisible('api_token');
        $user->assignRole(Flag::ROLE_EVENT_MANAGER);
        $this->usersService->sendRegisterNotification($user, $request->get('host'), $request->get('to'));
        $result['user'] = $this->usersService->authTransform($user);
        $result['accessToken'] = $this->jwtAuth->fromUser($user);

        try {
            return $this->responseJson($result, Flag::STATUS_CODE_CREATED);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function token(ApiAccessTokenRequest $request): JsonResponse
    {
        try {
            $user = $this->usersService->findByToken($request->get('token'));
            $result['accessToken'] = $this->jwtAuth->fromUser($user);

            return $this->responseJson($result, Flag::STATUS_CODE_CREATED);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
