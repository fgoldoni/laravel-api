<?php

namespace Modules\Roles\Services;

use App\Services\ServiceAbstract;
use Modules\Roles\Repositories\Contracts\RolesRepository;
use Modules\Roles\Services\Contracts\RolesServiceInterface;

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
}
