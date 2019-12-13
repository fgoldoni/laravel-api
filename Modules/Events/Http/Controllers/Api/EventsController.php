<?php

namespace Modules\Events\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Events\Http\Requests\StoreEventRequest;
use Modules\Events\Http\Requests\UpdateEventRequest;
use Modules\Events\Services\Contracts\EventsServiceInterface;
use Modules\Events\Transformers\EventCollection;
use Modules\Events\Transformers\EventsCollection;

class EventsController extends Controller
{
    /**
     * @var \Modules\Events\Services\Contracts\EventsServiceInterface
     */
    private $eventsService;

    public function __construct(EventsServiceInterface $eventsService)
    {
        $this->eventsService = $eventsService;
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

    public function store(StoreEventRequest $request)
    {
        try {
            $result['event'] = new EventCollection(
                $this->eventsService->storeEvent(
                    $request->only('title', 'description', 'content', 'address', 'start', 'end', 'url', 'color', 'online', 'user_id'),
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
                $request->only('title', 'description', 'content', 'address', 'start', 'end', 'url', 'color', 'online', 'user_id'),
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
