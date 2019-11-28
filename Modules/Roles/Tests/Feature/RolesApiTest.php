<?php

namespace Modules\Roles\Tests\Feature;

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
        $token = $this->admin->makeVisible('api_token')->api_token;
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    /**
     * A basic unit test example.
     */
    public function testRolesAreListedCorrectly(): void
    {
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
    public function testRolesAreListedWithPaginateCorrectly(): void
    {
        $response = $this->json('GET', 'api/roles/paginate', ['per_page' => 10], $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'guard_name',
                        'deleted_at',
                        'created_at',
                        'updated_at',
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

    /**
     * A basic unit test example.
     */
    public function testRolesAreRestoredCorrectly(): void
    {
        $role = factory(Role::class)->create([
            'name'        => 'Fake',
        ]);

        $this->json('DELETE', 'api/roles/' . $role->id, [], $this->headers)
            ->assertStatus(200);

        $role = $role->fresh();

        $this->assertNotNull($role->deleted_at);

        $this->json('PUT', 'api/roles/' . $role->id . '/restore', [], $this->headers)
            ->assertStatus(200);

        $role = $role->fresh();

        $this->assertNull($role->deleted_at);
    }

    /**
     * A basic unit test example.
     */
    public function testRolesAreForceDeletedCorrectly(): void
    {
        $role = factory(Role::class)->create([
            'name'        => 'Fake',
        ]);

        $this->json('DELETE', 'api/roles/' . $role->id, [], $this->headers)
            ->assertStatus(200);

        $role = $role->fresh();

        $this->assertNotNull($role->deleted_at);

        $this->json('DELETE', 'api/roles/' . $role->id . '/destroy', [], $this->headers)
            ->assertStatus(200);

        $role = $role->fresh();

        $this->assertNull($role);
    }
}
