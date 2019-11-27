<?php

namespace Modules\Roles\Services;

use App\Repositories\Criteria\EagerLoad;
use App\Repositories\Criteria\OrderBy;
use App\Repositories\Criteria\WithTrashed;
use App\Services\ServiceAbstract;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Roles\Entities\Role;
use Modules\Roles\Repositories\Contracts\RolesRepository;
use Modules\Roles\Services\Contracts\RolesServiceInterface;
use Modules\Roles\Transformers\RoleCollection;

/**
 * Class RolesService.
 */
class RolesService extends ServiceAbstract implements RolesServiceInterface
{
    /**
     * @return mixed
     */
    protected function repository()
    {
        return RolesRepository::class;
    }

    public function paginate($request): LengthAwarePaginator
    {
        [$perPage, $sort, $search] = $this->parseRequest($request);

        return $this->resolveRepository()->withCriteria([
            new WithTrashed(),
            new OrderBy($sort[0], $sort[1])
        ])->paginate($perPage);
    }

    public function getRole(int $id): Role
    {
        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['permissions' => function ($query) {
                $query->select('id', 'name');
            }, 'users' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
        ])->find($id, ['id', 'name']);
    }

    public function getRoles()
    {
        return $this->resolveRepository()->withCriteria([
            new EagerLoad(['permissions' => function ($query) {
                $query->select('id', 'name');
            }, 'users' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
        ])->all();
    }

    public function storeRole(Request $request): Role
    {
        return $this->resolveRepository()->create(
            $request->only('name')
        );
    }

    public function updateRole(Request $request, $id): Role
    {
        return $this->update(
            $id,
            $request->only('name')
        );
    }

    public function transform(Role $role)
    {
        return new RoleCollection($role);
    }
}
