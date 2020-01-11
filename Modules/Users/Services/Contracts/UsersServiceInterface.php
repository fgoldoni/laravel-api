<?php

namespace Modules\Users\Services\Contracts;

use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Http\Requests\ApiUpdateUserRequest;
use Modules\Users\Transformers\AuthCollection;
use Modules\Users\Transformers\UserCollection;

interface UsersServiceInterface
{
    public function transform(User $user): UserCollection;

    public function authTransform(User $user): AuthCollection;

    public function paginate($request): LengthAwarePaginator;

    public function storeUser(ApiStoreUserRequest $request): User;

    public function findUser(int $id): User;

    public function updateUser(ApiUpdateUserRequest $request, int $id): User;

    public function getUsers();

    public function getUserByEmail(string $email);

    public function sendLoginLink(User $user, string $host, string $to = null);

    public function saveUser(array $attributes = []);

    public function sendRegisterNotification(User $user, string $host, string $to = null);

    public function findByToken(string $token);
}
