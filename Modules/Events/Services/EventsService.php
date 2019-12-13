<?php

namespace Modules\Events\Services;

use App\Repositories\Criteria\ByUser;
use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use App\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Events\Entities\Event;
use Modules\Events\Repositories\Contracts\EventsRepository;
use Modules\Events\Services\Contracts\EventsServiceInterface;

/**
 * Class EventsService.
 */
class EventsService extends ServiceAbstract implements EventsServiceInterface
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function getEvents(): Collection
    {
        return $this->resolveRepository()->withCriteria([
            new WithTrashed(),
            new ByUser($this->auth->user()->id),
            new EagerLoad(['categories' => function ($query) {
                $query->select('categories.id', 'categories.name');
            }, 'user' => function ($query) {
                $query->select('users.id', 'users.first_name', 'users.last_name');
            }])
        ])->all();
    }

    public function storeEvent(array $attributes = [], array $categories = [], array $tags = null): Event
    {
        $event = $this->resolveRepository()->create(
            array_merge(
                $attributes,
                [
                    'user_id'        => $this->auth->user()->id,
                    'eventable_type' => User::class,
                    'eventable_id'   => $this->auth->user()->id,
                ]
            )
        );

        $this->resolveRepository()->sync($event->id, 'categories', $categories);
        $event->saveTags($tags);

        return $event->fresh();
    }

    public function updateEvent(int $id, array $attributes = [], array $categories = [], array $tags = null): Event
    {
        $event = $this->resolveRepository()->update(
            $id,
            $attributes
        );

        $this->resolveRepository()->sync($event->id, 'categories', $categories);
        $event->saveTags($tags);

        return $event->fresh();
    }

    /**
     * @return mixed
     */
    protected function repository()
    {
        return EventsRepository::class;
    }

    public function paginate($request): LengthAwarePaginator
    {
        [$perPage, $sort, $search] = $this->parseRequest($request);

        return $this->resolveRepository()->withCriteria([
            new WithTrashed(),
            new OrderBy($sort[0], $sort[1])
        ])->paginate($perPage);
    }
}
