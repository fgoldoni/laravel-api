<?php

namespace Modules\Roles\Tests\Feature;

use App\Exceptions\TestErrorException;
use App\Flag;
use Modules\Roles\Services\Contracts\RolesServiceInterface;
use Prophecy\Argument;
use Tests\TestCase;

/**
 * Class RolesApiExceptionTest.
 */
class RolesApiExceptionTest extends TestCase
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
    public function testRolesAreListedException(): void
    {
        $rolesService = $this->prophesize(RolesServiceInterface::class);

        $rolesService->getRoles()->willThrow(new TestErrorException('Test Exception', Flag::STATUS_CODE_ERROR));

        $this->app->instance(RolesServiceInterface::class, $rolesService->reveal());

        $response = $this->json('GET', 'api/roles', [], $this->headers);

        $this->responseBody = $response->getContent();

        $response->assertStatus(Flag::STATUS_CODE_ERROR);

        $this->seeJsonEquals([
            'success'   => false,
            'exception' => TestErrorException::class,
            'message'   => 'Test Exception',
            'status'    => Flag::STATUS_CODE_ERROR
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
    public function testRolesAreEditedException(): void
    {
        $rolesService = $this->prophesize(RolesServiceInterface::class);

        $rolesService->getRole(Argument::any())->willThrow(new TestErrorException('Test Exception', Flag::STATUS_CODE_ERROR));

        $this->app->instance(RolesServiceInterface::class, $rolesService->reveal());

        $response = $this->json('GET', 'api/roles/1/edit', [], $this->headers);

        $response->assertStatus(Flag::STATUS_CODE_ERROR);

        $this->responseBody = $response->getContent();

        $this->seeJsonEquals([
            'success'   => false,
            'exception' => TestErrorException::class,
            'message'   => 'Test Exception',
            'status'    => Flag::STATUS_CODE_ERROR
        ]);
    }
}
