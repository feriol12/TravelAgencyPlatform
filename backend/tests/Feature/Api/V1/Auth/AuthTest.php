<?php

namespace Tests\Feature\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private const STATEFUL_ORIGIN = 'http://127.0.0.1:5173';

    public function test_sanctum_stateful_domains_include_the_configured_frontend_origin(): void
    {
        $this->assertContains('127.0.0.1:5173', config('sanctum.stateful'));
    }

    public function test_csrf_cookie_endpoint_issues_an_xsrf_token_cookie(): void
    {
        $response = $this->get('/sanctum/csrf-cookie');

        $response->assertNoContent();
        $response->assertCookie('XSRF-TOKEN');
    }

    public function test_login_with_valid_credentials_authenticates_the_user(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $response = $this->withHeaders(['Referer' => self::STATEFUL_ORIGIN])
            ->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'correct-password',
            ]);

        $response->assertOk();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_fails_safely(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $response = $this->withHeaders(['Referer' => self::STATEFUL_ORIGIN])
            ->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
        $response->assertJsonMissingPath('data');
        $this->assertGuest();
    }

    public function test_login_requires_email_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    public function test_login_without_a_stateful_session_context_fails_safely(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        // No Referer/Origin header: Sanctum's EnsureFrontendRequestsAreStateful
        // will not recognise this as a frontend request, so no session middleware
        // runs for it. This must not crash and must not authenticate the user.
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'correct-password',
        ]);

        $response->assertStatus(419);
        $response->assertJsonMissingPath('data');
        $this->assertGuest('web');
    }

    public function test_me_returns_the_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/v1/auth/me');

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function test_me_is_unauthorized_when_not_authenticated(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_logout_destroys_the_authenticated_session(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeaders(['Referer' => self::STATEFUL_ORIGIN])
            ->postJson('/api/v1/auth/logout');

        $response->assertOk();

        // The "auth:sanctum" middleware switches the app's default guard to
        // "sanctum" for the remainder of the request (Authenticate::authenticate()).
        // Check the "web" guard explicitly, which is the one the controller logs out.
        $this->assertGuest('web');
    }

    public function test_me_is_unauthorized_after_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withHeaders(['Referer' => self::STATEFUL_ORIGIN])
            ->postJson('/api/v1/auth/logout')
            ->assertOk();

        // Sanctum's RequestGuard caches its resolved user for the lifetime of the
        // guard instance. Forget resolved guards so the next call re-resolves
        // authentication from the (now invalidated) session, as a real separate
        // HTTP request would.
        Auth::forgetGuards();

        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401);
    }

    public function test_logout_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/auth/logout');

        $response->assertStatus(401);
    }
}
