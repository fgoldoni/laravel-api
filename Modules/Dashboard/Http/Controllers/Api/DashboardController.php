<?php

namespace Modules\Dashboard\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Modules\Dashboard\Repositories\Contracts\DashboardRepository;
use Modules\Users\Repositories\Contracts\UsersRepository;

class DashboardController extends Controller
{
    /**
     * @var \Modules\Dashboard\Repositories\Contracts\DashboardRepository
     */
    private $dashboard;
    /**
     * @var \Modules\Users\Repositories\Contracts\UsersRepository
     */
    private $users;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    public function __construct(UsersRepository $users, DashboardRepository $dashboard, AuthManager $auth)
    {
        $this->dashboard = $dashboard;
        $this->users = $users;
        $this->auth = $auth;
    }

    public function getDashboard()
    {
        try {
            $user = $this->users->find($this->auth->user()->id);

            $result['dashboards'] = $user->dashboards()->get();

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->users->sync($this->auth->user()->id, 'dashboards',
                [
                    1 => ['x' => 0, 'y' => 0, 'w' => 2, 'h' => 2, 'i' => 1],
                    2 => ['x' => 2, 'y' => 0, 'w' => 2, 'h' => 2, 'i' => 2],
                ]);
            $result['dashboards'] = $this->auth->user()->dashboards()->get();

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
