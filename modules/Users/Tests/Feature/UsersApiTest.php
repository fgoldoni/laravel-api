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
        Artisan::call('migrate');
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

        fwrite(STDERR, print_r(route('login'), true));
        fwrite(STDERR, print_r(url('login'), true));
        fwrite(STDERR, print_r(route('api.users'), true));
        fwrite(STDERR, print_r(url('api/users'), true));

        $response->assertStatus(200)
            ->assertJsonStructure([
            'data',
            'success',
            'status',
        ]);
    }
}
