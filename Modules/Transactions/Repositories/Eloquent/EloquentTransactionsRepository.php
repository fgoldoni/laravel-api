<?php

/**
 * Created by PhpStorm.
 * User: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Transactions\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Modules\Transactions\Entities\Transaction;
use Modules\Transactions\Repositories\Contracts\TransactionsRepository;

/**
 * Class EloquentTransactionsRepository.
 */
class EloquentTransactionsRepository extends RepositoryAbstract implements TransactionsRepository
{
    public function model()
    {
        return Transaction::class;
    }
}
