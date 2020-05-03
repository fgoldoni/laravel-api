<?php

namespace Modules\Transactions\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Carts\Jobs\OrderJob;
use Modules\Carts\Repositories\Contracts\CartsRepository;
use Modules\Carts\Repositories\Eloquent\EloquentCartsRepository;
use Modules\Transactions\Http\Requests\PaypalRequest;
use Modules\Transactions\Jobs\PaypalTransactionJob;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;

class TransactionsController extends Controller
{
    /**
     * @var \Modules\Transactions\Repositories\Contracts\TransactionsRepository
     */
    private $transactions;
    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;
    /**
     * @var \Modules\Carts\Repositories\Contracts\CartsRepository
     */
    private $cart;

    public function __construct(
        TransactionsRepository $transactions,
        ResponseFactory $response,
        AuthManager $auth,
        Translator $lang,
        CartsRepository $cart
    ) {
        $this->transactions = $transactions;
        $this->response = $response;
        $this->auth = $auth;
        $this->lang = $lang;
        $this->cart = $cart;
    }

    public function invoice(int $id)
    {
        try {
            $transaction = $this->transactions->find($id);
            $result['transaction'] = $this->transactions->find($transaction->parent_id);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function paypal(PaypalRequest $request)
    {
        try {
            $cart = $this->cart->details();

            $transaction = $this->transactions->makePaypalTransaction($request->all(), $cart, $this->auth->user()->id);

            OrderJob::dispatch($cart['items'], $transaction->id,  $this->auth->user()->id);

            app()->make(EloquentCartsRepository::class)->clear();

            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Paypal']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
