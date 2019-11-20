<?php

namespace Modules\Users\Services;

use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Http\Requests\ApiUpdateUserRequest;
use Modules\Users\Repositories\Contracts\UsersRepository;
use Modules\Users\Services\Contracts\UsersServiceInterface;
use Modules\Users\Transformers\AuthCollection;
use Modules\Users\Transformers\UserCollection;

/**
 * Class UsersService.
 */
class UsersService extends ServiceAbstract implements UsersServiceInterface
{
    /**
     * @return mixed
     */
    protected function repository()
    {
        return UsersRepository::class;
    }

    private function parseRequest(Request $request)
    {
        return [
            $request->get('per_page', 10),
            explode('|', $request->get('sort', 'id|asc')),
            $request->get('filter')
        ];
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        [$perPage, $sort, $search] = $this->parseRequest($request);

        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['roles:id,name,guard_name']),
            new WithTrashed(),
            new OrderBy($sort[0], $sort[1])
        ])->paginate($perPage);
    }

    public function getUsers(): Collection
    {
        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['roles:id,name,guard_name']),
            new WithTrashed()
        ])->all();
    }

    public function store(ApiStoreUserRequest $request): User
    {
        return $this->resolveRepository()->create(
            array_merge(
                $request->only('first_name', 'last_name', 'email'),
                [
                    'email_verified_at' => now(),
                    'password'          => bcrypt($request->get('password'))
                ]
            )
        );
    }

    public function update(ApiUpdateUserRequest $request, $id): User
    {
        return $this->resolveRepository()->update(
            $id,
            $request->only('first_name', 'last_name', 'email')
        );
    }

    public function delete(int $id): User
    {
        return $this->resolveRepository()->delete($id);
    }

    public function forceDelete(int $id): User
    {
        return $this->resolveRepository()->forceDelete($id);
    }

    public function restore(int $id): User
    {
        return $this->resolveRepository()->restore($id);
    }

    public function find(int $id): User
    {
        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['roles:id,name', 'permissions:id,name', 'activities' => static function ($query) {
                return $query->latest();
            }])
        ])->find($id);
    }

    public function transform(User $user): UserCollection
    {
        return new UserCollection($user);
    }

    public function firstOrNew(array $attributes): User
    {
        return $this->resolveRepository()->firstOrNew($attributes);
    }

    public function authTransform(User $user): AuthCollection
    {
        return new AuthCollection($user);
    }
}
