<?php

namespace Modules\Users\Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('module:seed', ['module' => 'Roles', '--force' => true]);
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreListedCorrectly(): void
    {
        //fwrite(STDERR, print_r(User::first()->first_name, TRUE));

        $users = factory(User::class, 10)->create();

        $users->first()->assignRole('Admin');

        $token = $users->first()->makeVisible('api_token')->api_token;

        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', 'api/users', [], $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                ]
            ],
            'success',
            'status',
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreListedWithPaginateCorrectly(): void
    {
        $headers = $this->init(20);

        $response = $this->json('GET', 'api/users/paginate', ['per_page' => 10], $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]);
    }

    public function init($maxUsers): array
    {
        $users = factory(User::class, $maxUsers)->create();

        $users->first()->assignRole('Admin');

        $token = $users->first()->makeVisible('api_token')->api_token;

        return ['Authorization' => "Bearer $token"];
    }
}
