<?php

namespace Modules\Users\Services;

use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\Where;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Http\Requests\ApiUpdateUserRequest;
use Modules\Users\Notifications\LoginLinkNotification;
use Modules\Users\Notifications\RegisterNotification;
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

    public function transform(User $user): UserCollection
    {
        return new UserCollection($user);
    }

    public function authTransform(User $user): AuthCollection
    {
        return new AuthCollection($user);
    }

    public function paginate($request): LengthAwarePaginator
    {
        [$perPage, $sort, $search] = $this->parseRequest($request);

        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['roles:id,name,guard_name']),
            new WithTrashed(),
            new OrderBy($sort[0], $sort[1])
        ])->paginate($perPage);
    }

    public function storeUser(ApiStoreUserRequest $request): User
    {
        return $this->store(
            array_merge(
                $request->only('first_name', 'last_name', 'email'),
                [
                    'email_verified_at' => now(),
                    'password'          => bcrypt($request->get('password')),
                    'api_token'         => Str::random(60)
                ]
            )
        );
    }

    public function updateUser(ApiUpdateUserRequest $request, int $id): User
    {
        return $this->update(
            $id,
            $request->only('first_name', 'last_name', 'email')
        );
    }

    public function findUser(int $id): User
    {
        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['roles:id,name', 'permissions:id,name', 'activities' => static function ($query) {
                return $query->latest();
            }])
        ])->find($id);
    }

    public function getUsers()
    {
        return $this->all();
    }

    public function getUserByEmail(string $email)
    {
        return $this->resolveRepository()->withCriteria([
            new Where('email', $email)
        ])->first();
    }

    public function sendLoginLink(User $user, string $host, string $to = null)
    {
        Notification::send($user, new LoginLinkNotification($user, $host, $to));
    }

    public function saveUser(array $attributes = [])
    {
        return $this->store(
            array_merge(
                $attributes,
                [
                    'first_name'        => '',
                    'last_name'         => '',
                    'email_verified_at' => now(),
                    'password'          => bcrypt(Str::random(20)),
                    'api_token'         => Str::random(60)
                ]
            )
        );
    }

    public function sendRegisterNotification(User $user, string $host, string $to = null)
    {
        Notification::send($user, new RegisterNotification($user, $host, $to));
    }

    public function findByToken(string $token)
    {
        return $this->resolveRepository()->withCriteria([
            new Where('api_token', $token)
        ])->first();
    }
}
