<?php

namespace Modules\Paypal\Repositories\Contracts;

use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

interface PaypalRepository
{
    public function getApiContext(): ApiContext;

    public function getRedirectUrls(): RedirectUrls;

    public function getPayment(): Payment;

    public function getPayer(): Payer;

    public function getTransaction(): Transaction;

    public function getPaymentById($id, ApiContext $apiContext): Payment;

    public function getPaymentExecution($payerID, array $transactions): PaymentExecution;
}
