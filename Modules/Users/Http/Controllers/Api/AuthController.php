<?php

namespace Modules\Users\Http\Controllers\Api;

use App\Flag;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Users\Services\Contracts\UsersServiceInterface;

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

    public function __construct(UsersServiceInterface $usersService, ResponseFactory $response, Translator $lang, AuthManager $auth, Logger $logger)
    {
        $this->usersService = $usersService;
        $this->response = $response;
        $this->lang = $lang;
        $this->auth = $auth;
        $this->logger = $logger;
    }

    public function login(Request $request): JsonResponse
    {
        $result = [];

        try {
            if ($this->auth->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                $user = $this->usersService->find($this->auth->user()->id)->makeVisible('api_token');
                $result['user'] = $this->usersService->authTransform($user);
            } else {
                throw new Exception('Wrong Email or Password');
            }

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
}
