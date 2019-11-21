<?php

namespace Modules\Users\Services\Contracts;

use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Transformers\AuthCollection;
use Modules\Users\Transformers\UserCollection;

interface UsersServiceInterface
{
    public function transform(User $user): UserCollection;

    public function authTransform(User $user): AuthCollection;

    public function paginate($request): LengthAwarePaginator;

    public function storeUser(ApiStoreUserRequest $request): User;

    public function updateUser(ApiStoreUserRequest $request, int $id): User;
}
