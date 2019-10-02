<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Users\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use App\User;
use Modules\Users\Repositories\Contracts\UsersRepository;

/**
 * Class EloquentPostsRepository.
 */
class EloquentUsersRepository extends RepositoryAbstract implements UsersRepository
{
    public function model()
    {
        return User::class;
    }
}
