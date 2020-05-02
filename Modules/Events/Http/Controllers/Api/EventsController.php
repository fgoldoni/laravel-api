<?php

namespace Modules\Events\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Criteria\Where;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Modules\Events\Entities\Event;
use Modules\Events\Http\Requests\StoreEventRequest;
use Modules\Events\Http\Requests\UpdateEventRequest;
use Modules\Events\Http\Requests\UpdateEventThemeRequest;
use Modules\Events\Services\Contracts\EventsServiceInterface;
use Modules\Events\Transformers\CreateEventCollection;
use Modules\Events\Transformers\EventCollection;
use Modules\Events\Transformers\EventsCollection;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;
use Modules\Tickets\Transformers\TicketsCollection;

class EventsController extends Controller
{
    /**
     * @var \Modules\Events\Services\Contracts\EventsServiceInterface
     */
    private $eventsService;
    /**
     * @var \Carbon\Carbon
     */
    private $carbon;
    /**
     * @var \Modules\Tickets\Repositories\Contracts\TicketsRepository
     */
    private $tickets;
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    public function __construct(EventsServiceInterface $eventsService, TicketsRepository $tickets, Carbon $carbon, AuthManager $auth)
    {
        $this->eventsService = $eventsService;
        $this->carbon = $carbon;
        $this->tickets = $tickets;
        $this->auth = $auth;
    }

    public function getEvents()
    {
        try {
            $data = EventsCollection::collection($this->eventsService->getEvents());

            return $this->responseJson(['data' => $data]);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function tickets(Request $request, Event $event)
    {
        try {
            $result['tickets'] = TicketsCollection::collection($this->tickets->withCriteria([
                new Where('event_id', $event->id),
                new Where('online', true)
            ])->all());

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function edit(int $id)
    {
        try {
            $result['event'] = new EventCollection($this->eventsService->find($id));

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function show(string $slug)
    {
        try {
            $result['event'] = new EventCollection($this->eventsService->findBySlug($slug));

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        try {
            $event = $this->eventsService->firstOrNew([
                'id'               => 0,
                'title'            => 'Ebony 2020',
                'description'      => 'Id est harum enim tempora quia ad est similique cumque eius ut quidem nesciunt accusamus expedita quae et soluta temporibus nesciunt commodi.',
                'content'          => 'content',
                'address'          => 'Niendorfer Straße 45, 22303 Hamburg',
                'city'             => 'Hamburg',
                'contact_phone'    => '017699663325',
                'contact_email'    => 'event@contact.de',
                'contact_name'     => 'John Doe',
                'online'           => false,
                'color'            => '#00695C',
                'start'            => $this->carbon->now(),
                'end'              => $this->carbon->now()->endOfDay(),
            ]);
            $result['event'] = new CreateEventCollection($event);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function store(StoreEventRequest $request)
    {
        try {
            $result['event'] = new EventCollection(
                $this->eventsService->storeEvent(
                    $request->only('title', 'description', 'content', 'address', 'city', 'contact_phone', 'contact_email', 'contact_name', 'start', 'end', 'url', 'color', 'online'),
                    $request->get('categories', []),
                    $request->get('tags', [])
                )
            );

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function update(UpdateEventRequest $request, int $id)
    {
        try {
            $event = $this->eventsService->updateEvent(
                $id,
                $request->only('title', 'description', 'content', 'address', 'city', 'contact_phone', 'contact_email', 'contact_name', 'start', 'end', 'url', 'color', 'online'),
                $request->get('categories', []),
                $request->get('tags', [])
            );

            $result['event'] = new EventCollection($event);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function theme(UpdateEventThemeRequest $request, int $id)
    {
        try {
            $event = $this->eventsService->update(
                $id,
                $request->only('theme')
            );

            $result['event'] = new EventCollection($event);

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function destroy(int $id)
    {
        try {
            $result['event'] = new EventCollection($this->eventsService->delete($id));

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function restore(int $id)
    {
        try {
            $result['event'] = new EventCollection($this->eventsService->restore($id));

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            $result['event'] = new EventCollection($this->eventsService->forceDelete($id));

            return $this->responseJson($result);
        } catch (Exception $e) {
            return $this->responseJsonError($e);
        }
    }
}
