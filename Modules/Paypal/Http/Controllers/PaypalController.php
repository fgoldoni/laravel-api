<?php

namespace Modules\Paypal\Http\Controllers;

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Orders\Repositories\Contracts\OrdersRepository;
use Modules\Paypal\Repositories\Contracts\PaypalRepository;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;
use Modules\Users\Repositories\Contracts\UsersRepository;
use PayPal\Exception\PayPalConnectionException;

class PaypalController extends Controller
{
    /**
     * @var \Modules\Paypal\Repositories\Contracts\PaypalRepository
     */
    private $paypal;
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
     * @var \Carbon\Carbon
     */
    private $carbon;
    /**
     * @var \Modules\Transactions\Repositories\Contracts\TransactionsRepository
     */
    private $transactions;
    /**
     * @var \Modules\Users\Repositories\Contracts\UsersRepository
     */
    private $users;
    /**
     * @var \Modules\Orders\Repositories\Contracts\OrdersRepository
     */
    private $orders;

    /**
     * PaypalController constructor.
     */
    public function __construct(PaypalRepository $paypal, OrdersRepository $orders, TransactionsRepository $transactions, UsersRepository $users, ResponseFactory $response, AuthManager $auth, Translator $lang, Carbon $carbon)
    {
        $this->paypal = $paypal;
        $this->response = $response;
        $this->auth = $auth;
        $this->lang = $lang;
        $this->carbon = $carbon;
        $this->transactions = $transactions;
        $this->users = $users;
        $this->orders = $orders;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('paypal::index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function buy()
    {
        try {
            $apiContext = $this->paypal->getApiContext();
            $redirectUrls = $this->paypal->getRedirectUrls();

            $payment = $this->paypal->getPayment();
            $payment->setTransactions([$this->paypal->getTransaction()]);
            $payment->setIntent('sale');
            $payment->setRedirectUrls($redirectUrls);
            $payment->setPayer(($this->paypal->getPayer())->setPaymentMethod('paypal'));

            $payment->create($apiContext);
            header('Location: ' . $payment->getApprovalLink());
            exit();
        } catch (PayPalConnectionException $e) {
            return redirect()->route('user.carts')->withErrors([$e->getData()]);
        }
    }

    public function pay(Request $request)
    {
        try {
            $apiContext = $this->paypal->getApiContext();
            $payment = $this->paypal->getPaymentById($request->get('paymentId'), $apiContext);
            $execution = $this->paypal->getPaymentExecution($request->get('PayerID'), $payment->getTransactions());
            $payment->execute($execution, $apiContext);
            $transaction = $this->transactions->store($request->get('paymentId'), 'Paypal', $this->paypal->getTransaction()->getAmount()->total);

            $products = [];

            foreach (Cart::instance('default')->content() as $row) {
                $products[] = $row->model->id;
                $this->orders->create(
                    [
                        'name'           => $row->model->name,
                        'price'          => $row->price,
                        'qty'            => $row->qty,
                        'tax'            => $row->tax(2, '.', ''),
                        'customer_id'    => $this->auth->guard('web')->user()->id,
                        'provider_id'    => $row->model->user_id,
                        'transaction_id' => $transaction->id,
                        'template_id'    => $row->model->id,
                    ]
                );
            }

            $this->users->syncWithoutDetaching($this->auth->guard('web')->user()->id, 'downloads', $products);

            Cart::instance('default')->restore($this->auth->guard('web')->user()->id);
            Cart::instance('default')->destroy();

            return redirect()->route('user.download')->with(['success' => 'Payment perform successfully']);
        } catch (PayPalConnectionException $e) {
            return redirect()->route('user.carts')->withErrors([$e->getData()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('paypal::show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return view('paypal::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        return view('paypal::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
    }
}
