<?php

namespace Modules\Dashboard\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('dashboards')->insert([
            'name'            => 'All Tickets',
            'component'       => 'tile-ticket-component',
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        DB::table('dashboards')->insert([
            'name'            => 'All Orders',
            'component'       => 'tile-orders-component',
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        DB::table('dashboards')->insert([
            'name'            => 'All Revenue',
            'component'       => 'tile-revenue-component',
            'created_at'      => now(),
            'updated_at'      => now()
        ]);

        DB::table('dashboards')->insert([
            'name'            => 'All Stats',
            'component'       => 'tile-stats-component',
            'created_at'      => now(),
            'updated_at'      => now()
        ]);
    }
}
