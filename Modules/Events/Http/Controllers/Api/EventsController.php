<?php

namespace Modules\Events\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Events\Http\Requests\StoreEventRequest;
use Modules\Events\Http\Requests\UpdateEventRequest;
use Modules\Events\Services\Contracts\EventsServiceInterface;
use Modules\Events\Transformers\CreateEventCollection;
use Modules\Events\Transformers\EventCollection;
use Modules\Events\Transformers\EventsCollection;

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

    public function __construct(EventsServiceInterface $eventsService, Carbon $carbon)
    {
        $this->eventsService = $eventsService;
        $this->carbon = $carbon;
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
            $event =  $this->eventsService->firstOrNew([
                'id'               => 0,
                'title'            => 'Ebony 2020',
                'description'      => 'Id est harum enim tempora quia ad est similique cumque eius ut quidem nesciunt accusamus expedita quae et soluta temporibus nesciunt commodi.',
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

            $result['data'] = new EventCollection($event);

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
