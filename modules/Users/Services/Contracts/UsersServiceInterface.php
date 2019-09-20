<?php

namespace Modules\Users\Services\Contracts;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Users\Http\Requests\ApiStoreUserRequest;
use Modules\Users\Http\Requests\ApiUpdateUserRequest;

interface UsersServiceInterface
{
    public function paginate(Request $request): LengthAwarePaginator;

    public function getUsers(): Collection;

    public function store(ApiStoreUserRequest $request): User;

    public function update(ApiUpdateUserRequest $request, $id): User;

    public function delete(int $id): User;

    public function forceDelete(int $id): User;

    public function restore(int $id): User;
}
