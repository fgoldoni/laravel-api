<?php

namespace Modules\Users\Tests\Feature;

use App\Exceptions\TestErrorException;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Modules\Users\Services\Contracts\UsersServiceInterface;
use PHPUnit\Framework\Assert as PHPUnit;
use Prophecy\Argument;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    /**
     * @var array
     */
    private $headers;
    /**
     * @var false|string
     */
    private $responseBody;

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
    public function testUsersAreListedCorrectly(): void
    {
        //fwrite(STDERR, print_r(User::first()->first_name, TRUE));
        $response = $this->json('GET', 'api/users', [], $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'full_name',
                        'name',
                        'email',
                        'dob',
                        'gender',
                        'country',
                        'company',
                        'department',
                        'mobile',
                        'website',
                        'languages_known',
                        'contact_options',
                        'is_verified',
                        'status',
                        'roles',
                        'permissions',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'success',
                'status',
            ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreListedException(): void
    {
        $usersService = $this->prophesize(UsersServiceInterface::class);

        $usersService->getUsers()->willThrow(new TestErrorException('Test Exception'));

        $this->app->instance(UsersServiceInterface::class, $usersService->reveal());

        $response = $this->json('GET', 'api/users', [], $this->headers);

        $this->responseBody = $response->getContent();

        $response->assertStatus(500);

        $this->seeJsonEquals([
            'success'   => false,
            'exception' => TestErrorException::class,
            'message'   => 'Test Exception',
            'status'    => 500
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
            'status'
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreListedWithPaginateCorrectly(): void
    {
        $response = $this->json('GET', 'api/users/paginate', ['per_page' => 10], $this->headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'full_name',
                        'email',
                        'deleted_at',
                        'created_at',
                        'updated_at',
                        'roles' => [],
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
    public function testUsersAreListedWithPaginateException(): void
    {
        $usersService = $this->prophesize(UsersServiceInterface::class);

        $usersService->paginate(Argument::any())->willThrow(new \Exception('Exception'));

        $this->app->instance(UsersServiceInterface::class, $usersService->reveal());

        $response = $this->json('GET', 'api/users/paginate', ['per_page' => 10], $this->headers);

        $response->assertStatus(500);

        $response->assertJsonStructure([
            'success',
            'message',
            'status'
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreEditedCorrectly(): void
    {
        $response = $this->json('GET', 'api/users/1/edit', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'updated_at',
                    'created_at'
                ],
                'success',
                'status',
            ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreEditedException(): void
    {
        $usersService = $this->prophesize(UsersServiceInterface::class);

        $usersService->transform(Argument::any())->willThrow(new \Exception('Exception'));

        $this->app->instance(UsersServiceInterface::class, $usersService->reveal());

        $response = $this->json('GET', 'api/users/1/edit', [], $this->headers);

        $response->assertStatus(500);

        $response->assertJsonStructure([
            'success',
            'message',
            'status'
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreCreatedCorrectly(): void
    {
        $response = $this->json('GET', 'api/users/create', [], $this->headers)
            ->assertStatus(200);

        $content = json_decode($response->getContent(), false);

        $this->assertSame('', $content->user->first_name);

        $this->assertSame('', $content->user->last_name);

        $this->assertSame('', $content->user->email);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreCreatedException(): void
    {
        $usersService = $this->prophesize(UsersServiceInterface::class);

        $usersService->transform(Argument::any())->willThrow(new \Exception('Exception'));

        $this->app->instance(UsersServiceInterface::class, $usersService->reveal());

        $response = $this->json('GET', 'api/users/create', [], $this->headers);

        $response->assertStatus(500);

        $response->assertJsonStructure([
            'success',
            'message',
            'status'
        ]);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreStoredCorrectly(): void
    {
        $payload = [
            'first_name'        => 'John',
            'last_name'         => 'Doe',
            'email'             => 'john@doe.com',
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ];

        $response = $this->json('POST', 'api/users', $payload, $this->headers)
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

        $this->assertSame('John', $content->user->first_name);

        $this->assertSame('Doe', $content->user->last_name);

        $this->assertSame('john@doe.com', $content->user->email);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreUpdatedCorrectly(): void
    {
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

        $response = $this->json('PUT', 'api/users/' . $user->id, $payload, $this->headers)
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
        $user = factory(User::class)->create([
            'first_name'        => 'This is a First Name',
            'last_name'         => 'This is a Last Name',
            'email'             => 'john@doe.com',
        ]);

        $this->json('DELETE', 'api/users/' . $user->id, [], $this->headers)
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

    /**
     * A basic unit test example.
     */
    public function testUsersAreForceDeletedCorrectly(): void
    {
        $user = factory(User::class)->create([
            'first_name'        => 'This is a First Name',
            'last_name'         => 'This is a Last Name',
            'email'             => 'john@doe.com',
        ]);

        $this->json('DELETE', 'api/users/' . $user->id, [], $this->headers)
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertNotNull($user->deleted_at);

        $this->json('DELETE', 'api/users/' . $user->id . '/destroy', [], $this->headers)
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertNull($user);
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreRestoredCorrectly(): void
    {
        $user = factory(User::class)->create([
            'first_name'        => 'John',
            'last_name'         => 'Doe',
            'email'             => 'john@doe.com',
        ]);

        $this->json('DELETE', 'api/users/' . $user->id, [], $this->headers)
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertNotNull($user->deleted_at);

        $this->json('PUT', 'api/users/' . $user->id . '/restore', [], $this->headers)
            ->assertStatus(200);

        $user = $user->fresh();

        $this->assertNull($user->deleted_at);
    }

    private function seeJsonEquals(array $data): self
    {
        $actual = json_encode(Arr::sortRecursive(
            $this->getResponseAsArray()
        ));

        PHPUnit::assertEquals(json_encode(Arr::sortRecursive($data)), $actual);

        return $this;
    }

    private function getResponseAsArray()
    {
        return json_decode($this->responseBody, true);
    }
}
