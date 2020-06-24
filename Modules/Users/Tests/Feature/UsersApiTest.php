<?php

namespace Modules\Users\Tests\Feature;

use App\Exceptions\ErrorException;
use App\Exceptions\TestErrorException;
use App\User;
use Illuminate\Support\Facades\Notification;
use Modules\Users\Notifications\UserCreated;
use Modules\Users\Notifications\UserDeleted;
use Modules\Users\Notifications\UserRestored;
use Modules\Users\Services\Contracts\UsersServiceInterface;
use Prophecy\Argument;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    /**
     * @var array
     */
    private $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $token = $this->authAdmin()->accessToken;
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreListedCorrectly(): void
    {
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

        $usersService->getUsers()->willThrow(new TestErrorException('Test Exception', 500));

        $this->app->instance(UsersServiceInterface::class, $usersService->reveal());

        $response = $this->json('GET', 'api/users', [], $this->headers);

        $this->responseBody = $response->getContent();

        $response->assertStatus(500);

        $this->seeJsonEquals([
            'code'      => 500,
            'success'   => false,
            'exception' => $this->getExceptionShortName(TestErrorException::class),
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

        $usersService->paginate(Argument::any())->willThrow(new \Exception('Exception', 500));

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
//    public function testUsersAreEditedException(): void
//    {
//        $usersService = $this->prophesize(UsersServiceInterface::class);
//
//        $usersService->transform(Argument::any())->willThrow(new \Exception('Exception', 500));
//
//        $this->app->instance(UsersServiceInterface::class, $usersService->reveal());
//
//        $response = $this->json('GET', 'api/users/1/edit', [], $this->headers);
//
//        $this->responseBody = $response->getContent();
//
//        $response->assertStatus(500);
//
//        $this->assertSame($this->getExceptionShortName(ErrorException::class), $this->getResponseAsObject()->exception);
//    }

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

        $usersService->transform(Argument::any())->willThrow(new TestErrorException('Exception', 500));

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
        Notification::fake();

        Notification::assertNothingSent();

        $payload = [
            'first_name'        => 'John',
            'last_name'         => 'Doe',
            'email'             => 'john@doe.com',
            'password'          => '000000',
            'password_confirm'  => '000000',
            'roles'             => [1],
        ];

        $response = $this->json('POST', 'api/users', $payload, $this->headers)
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
                'message',
                'success',
                'status',
            ]);

        $content = json_decode($response->getContent());

        $this->assertSame('John', $content->user->first_name);

        $this->assertSame('Doe', $content->user->last_name);

        $this->assertSame('john@doe.com', $content->user->email);

        Notification::assertSentTo(
            [$this->admin],
            UserCreated::class
        );
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
        Notification::fake();

        Notification::assertNothingSent();

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

        Notification::assertSentTo(
            [$this->admin],
            UserDeleted::class
        );
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreForceDeletedCorrectly(): void
    {
        Notification::fake();

        Notification::assertNothingSent();

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

        Notification::assertSentTo(
            [$this->admin],
            UserDeleted::class
        );
    }

    /**
     * A basic unit test example.
     */
    public function testUsersAreRestoredCorrectly(): void
    {
        Notification::fake();

        Notification::assertNothingSent();

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

        Notification::assertSentTo(
            [$this->admin],
            UserRestored::class
        );
    }
}
