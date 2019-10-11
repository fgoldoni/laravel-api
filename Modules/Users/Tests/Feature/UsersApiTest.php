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

    /**
     * A basic unit test example.
     */
    public function testUsersAreCreatedCorrectly(): void
    {
        $headers = $this->init(1);

        $payload = [
            'first_name'        => 'This is a First Name',
            'last_name'         => 'This is a Last Name',
            'email'             => 'john@doe.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ];

        $response = $this->json('POST', 'api/users', $payload, $headers)
            ->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'updated_at',
                    'created_at'
                ],
                'message',
                'success',
                'status',
            ]);

        $content = json_decode($response->getContent());

        $this->assertSame('This is a First Name', $content->user->first_name);

        $this->assertSame('This is a Last Name', $content->user->last_name);

        $this->assertSame('john@doe.com', $content->user->email);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreUpdatedCorrectly(): void
    {
        $headers = $this->init(1);

        $user = factory(User::class)->create([
            'first_name'        => 'This is a First Name',
            'last_name'         => 'This is a Last Name',
            'email'             => 'john@doe.com',
        ]);

        $payload = [
            'first_name' => 'Updated This is a First Name',
            'last_name'  => 'Updated This is a Last Name',
            'email'      => 'updated.john@doe.com',
        ];

        $response = $this->json('PUT', 'api/users/' . $user->id, $payload, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'first_name',
                    'last_name',
                    'email',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'message',
                'success',
                'status',
            ]);

        $content = json_decode($response->getContent(), false);

        $this->assertSame('Updated This is a First Name', $content->user->first_name);

        $this->assertSame('Updated This is a Last Name', $content->user->last_name);

        $this->assertSame('updated.john@doe.com', $content->user->email);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreDeletedCorrectly(): void
    {
        $headers = $this->init(1);

        $user = factory(User::class)->create([
            'first_name'        => 'This is a First Name',
            'last_name'         => 'This is a Last Name',
            'email'             => 'john@doe.com',
        ]);

        $this->json('DELETE', 'api/users/' . $user->id, [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'first_name',
                    'last_name',
                    'email',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'message',
                'success',
                'status',
            ]);

        $user = $user->fresh();

        $this->assertNotNull($user->deleted_at);
    }
}
