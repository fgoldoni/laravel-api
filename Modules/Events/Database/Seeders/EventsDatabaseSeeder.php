<?php

namespace Modules\Events\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Events\Entities\Event;

class EventsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        foreach (config('events.permissions', []) as $key => $value) {
            if (!DB::table('permissions')->where('name', $value)->exists()) {
                DB::table('permissions')->insertGetId([
                    'name'         => $value,
                    'guard_name'   => 'web',
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);
            }
        }

        factory(Event::class, 10)->create();
    }
}
