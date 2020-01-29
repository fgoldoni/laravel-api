<?php

namespace Modules\Vouchers\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\CarbonInterval;
use FrittenKeeZ\Vouchers\Models\Voucher;
use FrittenKeeZ\Vouchers\Vouchers;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Modules\Users\Repositories\Contracts\UsersRepository;

class VouchersController extends Controller
{
    /**
     * @var \FrittenKeeZ\Vouchers\Vouchers
     */
    private $vouchers;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Modules\Users\Repositories\Contracts\UsersRepository
     */
    private $users;

    public function __construct(Vouchers $vouchers, AuthManager $auth, UsersRepository $users)
    {
        $this->vouchers = $vouchers;
        $this->auth = $auth;
        $this->users = $users;
    }

    public function getVouchers()
    {
        try {
            $vouchers = $this->auth->user()->vouchers()->get();

            return $this->responseJson(['data' => $vouchers]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $query = $this->vouchers;

            if ($request->has('user_id')) {
                $user = $this->users->find($request->get('user_id', null));
                if ($user) {
                    $query = $this->vouchers->withEntities($user);
                }
            }

            $voucher = $query->withMetadata([
                    'name'   => $request->get('name', 'Coupon'),
                    'type'   => 'coupon',
                    'target' => 'total',
                    'value'  => $request->get('value'),
                    'order'  => 1
                ])
                ->withExpireDateIn(CarbonInterval::create('P30D'))
                ->create();

            return $this->responseJson(['voucher' => $voucher]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
