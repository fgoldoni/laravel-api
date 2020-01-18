<?php

namespace Modules\Tickets\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Tickets\Entities\Ticket;

class TicketsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        factory(Ticket::class, 10)->create();
    }
}
