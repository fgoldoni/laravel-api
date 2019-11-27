<?php

namespace Modules\Roles\Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Artisan;
use Modules\Roles\Entities\Role;
use Tests\TestCase;

class RolesApiTest extends TestCase
{
    /**
     * @var array
     */
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('module:seed', ['module' => 'Roles', '--force' => true]);
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $admin = User::role('Admin')->first();
        $token = $admin->makeVisible('api_token')->api_token;
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    /**
     * A basic unit test example.
     */
    public function testRolesAreListedCorrectly(): void
    {
        //fwrite(STDERR, print_r(User::first()->first_name, TRUE));
        $response = $this->json('GET', 'api/roles', [], $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'users'       => [],
                        'permissions' => [],
                    ]
                ],
                'success',
                'status',
            ]);
    }

    /**
     * A basic unit test example.
     */
    public function testRolesAreStoredCorrectly(): void
    {
        $payload = [
            'name'        => 'Provider',
            'permissions' => [1],
        ];

        $response = $this->json('POST', 'api/roles', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'role' => [
                    'id',
                    'name',
                    'guard_name',
                    'updated_at',
                    'created_at'
                ],
                'message',
                'success',
                'status',
            ]);

        $this->responseBody = $response->getContent();

        $this->assertSame('Provider', $this->getResponseAsArray()['role']['name']);
    }

    /**
     * A basic unit test example.
     */
    public function testRolesAreUpdatedCorrectly(): void
    {
        $role = factory(Role::class)->create([
            'name'        => 'Provider'
        ]);

        $payload = [
            'name'        => 'Seller'
        ];

        $response = $this->json('PUT', 'api/roles/' . $role->id, $payload, $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'role' => [
                    'id',
                    'name',
                    'guard_name',
                    'updated_at',
                    'created_at'
                ],
                'message',
                'success',
                'status',
            ]);

        $content = json_decode($response->getContent(), false);

        $this->assertSame('Seller', $content->role->name);
    }

    /**
     * A basic unit test example.
     */
    public function testRolesAreDeletedCorrectly(): void
    {
        $role = factory(Role::class)->create();

        $this->json('DELETE', 'api/roles/' . $role->id, [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'role' => [
                    'id',
                    'name',
                    'guard_name',
                    'updated_at',
                    'created_at'
                ],
                'message',
                'success',
                'status',
            ]);

        $role = $role->fresh();

        $this->assertNotNull($role->deleted_at);
    }
}
