<?php

namespace Modules\Posts\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Posts\Entities\Post;

class PostsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        factory(Post::class, 10)->create();
    }
}
