<?php

namespace Modules\Categories\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Categories\Entities\Category;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        foreach (config('categories.permissions', []) as $key => $value) {
            if (!DB::table('permissions')->where('name', $value)->exists()) {
                DB::table('permissions')->insertGetId([
                    'name'         => $value,
                    'guard_name'   => 'web',
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);
            }
        }

        $parent = Category::create([
            'name'     => 'Blog',
            'children' => [
                [
                    'name' => 'Laravel',
                ]
            ]
        ]);

        $parent->children()->create([
            'name' => 'PHP'
        ]);

        $parent->children()->create([
            'name' => 'VueJS'
        ]);
    }
}
