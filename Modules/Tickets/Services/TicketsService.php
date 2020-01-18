<?php

namespace Modules\Tickets\Services;

use App\Repositories\Criteria\ByUser;
use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\Where;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use App\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Repositories\Contracts\TicketsRepository;
use Modules\Tickets\Services\Contracts\TicketsServiceInterface;

/**
 * Class TicketsService.
 */
class TicketsService extends ServiceAbstract implements TicketsServiceInterface
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    private $auth;

    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    public function getTickets(): Collection
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

    public function storeTicket(array $attributes = [], array $categories = [], array $tags = null): Ticket
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

    public function findBySlug(string $slug): Ticket
    {
        return $this->resolveRepository()->withCriteria([
            new Where('slug', $slug)
        ])->first();
    }

    public function updateTicket(int $id, array $attributes = [], array $categories = [], array $tags = null): Ticket
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
        return TicketsRepository::class;
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
