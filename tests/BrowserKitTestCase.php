<?php


namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

/**
 * Class BrowserKitTestCase
 *
 * @package \Tests
 */
class BrowserKitTestCase extends TestCase
{
    use RefreshDatabase;
    /**
     * @var array
     */
    protected $headers;

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

}
