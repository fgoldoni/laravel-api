<?php

namespace Modules\Tickets\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Tickets\Entities\Ticket;

interface TicketsServiceInterface
{
    public function getTickets(): Collection;

    public function storeTicket(array $attributes = [], array $categories = [], array $tags = null): Ticket;

    public function updateTicket(int $id, array $attributes = [], array $categories = [], array $tags = null): Ticket;

    public function findBySlug(string $slug): Ticket;
}
