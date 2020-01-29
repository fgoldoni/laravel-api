<?php

namespace Modules\Transactions\Http\Controllers;

use Illuminate\Auth\AuthManager;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
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

    public function __construct(
        TransactionsRepository $transactions,
        ResponseFactory $response,
        AuthManager $auth,
        Translator $lang
    ) {
        $this->transactions = $transactions;
        $this->response = $response;
        $this->auth = $auth;
        $this->lang = $lang;
    }

    public function invoice(int $id)
    {
        dd($id);
    }
}
