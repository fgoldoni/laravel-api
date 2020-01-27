<?php

namespace Modules\Stripe\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use Modules\Stripe\Http\Requests\StripeRequest;
use Modules\Stripe\Repositories\Contracts\StripeRepository;

class StripeController extends Controller
{
    /**
     * @var \Modules\Stripe\Repositories\Contracts\StripeRepository
     */
    private $stripe;
    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;

    public function __construct(StripeRepository $stripe, Translator $lang)
    {
        $this->stripe = $stripe;
        $this->lang = $lang;
    }

    public function intent(Request $request)
    {
        try {
            $result['intent'] = $this->stripe->intent();
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(StripeRequest $request)
    {
        try {
            $customer = $this->stripe->customers($request->get('name'), $request->get('email'), $request->get('stripeToken'));
            $charges = $this->stripe->charges($customer);
            $result['transaction'] = $this->stripe->make($charges);
            $result['message'] = $this->lang->get('messages.created', ['attribute' => 'Cart']);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
