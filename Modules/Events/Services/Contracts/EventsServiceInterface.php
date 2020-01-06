<?php

namespace Modules\Events\Services\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Events\Entities\Event;

interface EventsServiceInterface
{
    public function getEvents(): Collection;

    public function storeEvent(array $attributes = [], array $categories = [], array $tags = null): Event;

    public function updateEvent(int $id, array $attributes = [], array $categories = [], array $tags = null): Event;

    public function findBySlug(string $slug): Event;
}
