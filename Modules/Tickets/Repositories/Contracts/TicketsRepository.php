<?php

namespace Modules\Tickets\Repositories\Contracts;

use Modules\Tickets\Entities\Ticket;

interface TicketsRepository
{
    public function duplicate($ticket): Ticket;
}
