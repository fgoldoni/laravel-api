<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Paypal\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Modules\Paypal\Entities\Paypal;
use Modules\Paypal\Repositories\Contracts\PaypalRepository;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

/**
 * Class EloquentPaypalRepository.
 */
class EloquentPaypalRepository extends RepositoryAbstract implements PaypalRepository
{
    public function model()
    {
        return Paypal::class;
    }

    public function getApiContext(): ApiContext
    {
        return new ApiContext(
            new OAuthTokenCredential(
                env('PAYPAL_ID'),
                env('PAYPAL_KEY')
            )
        );
    }

    public function getRedirectUrls(): RedirectUrls
    {
        return (new RedirectUrls())
            ->setReturnUrl(route('user.paypal.pay'))
            ->setCancelUrl(route('user.carts'));
    }

    public function getPayment(): Payment
    {
        return new Payment();
    }

    public function getPayer(): Payer
    {
        return new Payer();
    }

    public function getTransaction(): Transaction
    {
        $description = Auth::guard('web')->user()->email;

        $custom = json_encode([
            'id'        => Auth::guard('web')->user()->id,
            'full_name' => Auth::guard('web')->user()->full_name,
            'email'     => Auth::guard('web')->user()->email
        ]);

        $carts = Cart::content();
        $list = new ItemList();

        foreach ($carts as $cart) {
            $description = $description . ' | ' . $cart->name;
            $item = (new Item())
                ->setName($cart->name)
                ->setPrice($cart->price)
                ->setTax($cart->tax(2, '.', ''))
                ->setCurrency('EUR')
                ->setQuantity($cart->qty);
            $list->addItem($item);
        }

        $details = (new Details())
            ->setTax(Cart::tax(2, '.', ''))
            ->setSubtotal(Cart::subtotal(2, '.', ''));

        $amount = (new Amount())
            ->setTotal(Cart::total(2, '.', ''))
            ->setCurrency('EUR')
            ->setDetails($details);

        return (new Transaction())
            ->setItemList($list)
            ->setDescription($description)
            ->setAmount($amount)
            ->setCustom($custom);
    }

    public function getPaymentById($id, ApiContext $apiContext): Payment
    {
        return Payment::get($id, $apiContext);
    }

    public function getPaymentExecution($payerID, array $transactions): PaymentExecution
    {
        return (new PaymentExecution())
            ->setPayerId($payerID)
            ->setTransactions($transactions);
    }
}
