<?php

namespace Modules\Carts\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Criteria\EagerLoad;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;

class CartsController extends Controller
{
    /**
     * @var \Modules\Carts\Repositories\Contracts\CartsRepository
     */
    private $carts;
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;
    /**
     * @var \Illuminate\Log\Logger
     */
    private $logger;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Modules\Tickets\Repositories\Contracts\TicketsRepository
     */
    private $tickets;

    public function __construct(CartsRepository $carts, TicketsRepository $tickets, ResponseFactory $response, Translator $lang, Logger $logger, AuthManager $auth)
    {
        $this->carts = $carts;
        $this->response = $response;
        $this->lang = $lang;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->tickets = $tickets;
    }

    public function getCarts()
    {
        try {
            $result['cart'] = $this->carts->details();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $ticket = $this->tickets->withCriteria([
                new EagerLoad(['event.categories'])
            ])->find($request->get('id'));

            $this->carts->addCart($ticket, $request->get('quantity', 1));

            $result['cart'] = $this->carts->details();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function coupon(Request $request)
    {
        try {
            $this->carts->addCoupon($request->get('coupon'));
            $result['cart'] = $this->carts->details();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $ticket = $this->tickets->withCriteria([
                new EagerLoad(['event.categories'])
            ])->find($id);

            $this->carts->updateCart($ticket, $request->get('quantity', 1));

            $result['cart'] = $this->carts->details();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->carts->deleteCart($id);

            $result['cart'] = $this->carts->details();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
