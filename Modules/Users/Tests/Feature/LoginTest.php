<?php

namespace Modules\Users\Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * Class LoginTest.
 */
class LoginTest extends TestCase
{
    public function testUserCanViewLoginForm()
    {
        $response = $this->get('/login');

        $response->assertSuccessful();

        $response->assertViewIs('auth.login');
    }

    public function testUserCannotViewLoginFormWhenAuthenticated()
    {
        Event::fake();

        Auth::logout();

        $response = $this->actingAs($this->user)->get('/login');

        $response->assertRedirect('/home');
    }

    public function testUserCanLoginWithCorrectCredentials()
    {
        Event::fake();

        Auth::logout();

        $response = $this->post('/login', [
            'email'    => $this->user->email,
            'password' => $this->userPassword,
        ]);

        $response->assertRedirect('/home');

        $this->assertAuthenticatedAs($this->user);
    }

    public function testUserCannotLoginWithIncorrectPassword()
    {
        $response = $this->from('/login')->post('/login', [
            'email'    => $this->user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
