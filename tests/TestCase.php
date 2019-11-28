<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Modules\Roles\Entities\Role;
use PHPUnit\Framework\Assert as PHPUnit;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $responseBody;
    /**
     * @var
     */
    public $baseUrl;

    /**
     * @var
     */
    protected $admin;

    /**
     * @var
     */
    protected $executive;

    /**
     * @var
     */
    protected $user;

    /**
     * @var
     */
    protected $adminRole;

    /**
     * @var
     */
    protected $executiveRole;

    /**
     * @var
     */
    protected $userRole;
    /**
     * @var
     */
    protected $adminPassword = '000000';

    /**
     * @var
     */
    protected $executivePassword = '000000';

    /**
     * @var
     */
    protected $userPassword = '000000';

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUrl = config('app.url', 'http://localhost');

        // Set up the database
        Artisan::call('migrate:refresh');
        Artisan::call('module:seed', ['module' => 'Roles', '--force' => true]);
        Artisan::call('module:seed', ['module' => 'Users', '--force' => true]);

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();

        /*
         * Create class properties to be used in tests
         */
        $this->admin = User::find(1);
        $this->executive = User::find(2);
        $this->user = User::find(3);
        $this->adminRole = Role::find(1)->name;
        $this->executiveRole = Role::find(2)->name;
        $this->userRole = Role::find(3)->name;
    }

    protected function seeJsonEquals(array $data): self
    {
        $actual = json_encode(Arr::sortRecursive(
            $this->getResponseAsArray()
        ));

        PHPUnit::assertEquals(json_encode(Arr::sortRecursive($data)), $actual);

        return $this;
    }

    protected function getResponseAsArray()
    {
        return json_decode($this->responseBody, true);
    }

    protected function assertLoggedIn()
    {
        $this->assertTrue(Auth::check());
    }

    protected function assertLoggedOut()
    {
        $this->assertFalse(Auth::check());
    }
}
