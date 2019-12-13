<?php

/**
 * Created by PhpStorm.
 * Role: goldoni
 * Date: 24.09.18
 * Time: 21:18.
 */

namespace Modules\Events\Repositories\Eloquent;

use App\Repositories\RepositoryAbstract;
use Modules\Events\Entities\Event;
use Modules\Events\Repositories\Contracts\EventsRepository;

/**
 * Class EloquentEventsRepository.
 */
class EloquentEventsRepository extends RepositoryAbstract implements EventsRepository
{
    public function model()
    {
        return Event::class;
    }
}
