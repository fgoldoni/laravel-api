<?php

namespace Modules\Tickets\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Criteria\ByUser;
use App\Repositories\Criteria\OrderBy;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Translation\Translator;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;
use Modules\Tickets\Transformers\TicketCollection;

class TicketsController extends Controller
{
    /**
     * @var \Modules\Tickets\Repositories\Contracts\TicketsRepository
     */
    private $tickets;
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

    public function __construct(TicketsRepository $tickets, ResponseFactory $response, Translator $lang, Logger $logger, AuthManager $auth)
    {
        $this->tickets = $tickets;
        $this->response = $response;
        $this->lang = $lang;
        $this->logger = $logger;
        $this->auth = $auth;
    }

    public function getTickets(Request $request)
    {
        try {
            [$perPage, $sort, $search] = $this->parseRequest($request);

            $result['tickets'] = $this->tickets->withCriteria([
                new OrderBy($sort[0], $sort[1]),
                new ByUser($this->auth->user()->id)
            ])->paginate($perPage);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function duplicate(Ticket $ticket)
    {
        try {
            $ticket = $this->tickets->withCriteria([
                new ByUser($this->auth->user()->id)
            ])->find($ticket->id);

            $result['ticket'] = $this->tickets->duplicate($ticket);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $ticket = $this->tickets->create(
                array_merge(
                    $request->only('name', 'offer_1', 'offer_2', 'offer_3', 'offer_4', 'price', 'quantity', 'event_id'),
                    [
                        'user_id' => $this->auth->user()->id
                    ]
                )
            );
            $this->tickets->sync($ticket->id, 'categories', $request->get('categories', []));

            $result['ticket'] = new TicketCollection($ticket->fresh());

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function create(int $id)
    {
        try {
            $result['ticket'] = $this->tickets->firstOrNew([
                'name'                => '',
                'offer_1'             => '',
                'offer_2'             => '',
                'offer_3'             => '',
                'offer_4'             => '',
                'price'               => 0,
                'quantity'            => 0,
                'event_id'            => $id,
            ]);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $ticket = $this->tickets->update(
                $id,
                $request->only('name', 'offer_1', 'offer_2', 'offer_3', 'offer_4', 'price', 'quantity', 'online')
            );
            $this->tickets->sync($ticket->id, 'categories', $request->get('categories', []));

            $result['message'] = $this->lang->get('messages.updated', ['attribute' => 'Ticket']);
            $result['ticket'] = new TicketCollection($ticket->fresh());


            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $result['ticket'] = $this->tickets->delete($id);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function restore(int $id)
    {
        try {
            $result['ticket'] = $this->tickets->restore($id);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $result['ticket'] = $this->tickets->forceDelete($id);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
