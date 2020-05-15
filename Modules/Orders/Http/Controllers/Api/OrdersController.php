<?php

namespace Modules\Orders\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Criteria\ByCustomerOrProvider;
use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\Select;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Orders\Repositories\Contracts\OrdersRepository;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;

class OrdersController extends Controller
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
    /**
     * @var \Modules\Orders\Repositories\Contracts\OrdersRepository
     */
    private $orders;
    /**
     * @var \Modules\Transactions\Repositories\Contracts\TransactionsRepository
     */
    private $transactions;


    public function __construct(OrdersRepository $orders, TransactionsRepository $transactions, TicketsRepository $tickets, ResponseFactory $response, Translator $lang, Logger $logger, AuthManager $auth)
    {
        $this->response = $response;
        $this->lang = $lang;
        $this->logger = $logger;
        $this->auth = $auth;
        $this->tickets = $tickets;
        $this->orders = $orders;
        $this->transactions = $transactions;
    }

    public function getOrders()
    {
        $transactions = $this->transactions->withCriteria([
            new Select('id', 'customer_id', 'provider_id'),
            new ByCustomerOrProvider($this->auth->user()->id)
        ])->all();

        try {
            $result['orders'] = $this->orders->getOrders($transactions->pluck(['id'])->toArray());
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Order']);

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
            $this->carts->deteteCart($id);

            $result['cart'] = $this->carts->details();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
