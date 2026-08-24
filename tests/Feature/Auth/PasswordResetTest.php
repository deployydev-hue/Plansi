<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk();
    }

    public function test_existing_user_can_request_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertSessionHas(
            'status',
            'If an account exists for this email, a password reset link will be sent.'
        );

        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );
    }

    public function test_unknown_email_receives_same_generic_response(): void
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'unknown@example.com',
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertSessionHas(
            'status',
            'If an account exists for this email, a password reset link will be sent.'
        );

        Notification::assertNothingSent();
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->get(
            route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ])
        );

        $response->assertOk();
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasNoErrors();

        $response->assertRedirect(route('login'));

        $this->assertTrue(
            Hash::check(
                'new-password-123',
                $user->fresh()->password
            )
        );
    }

    public function test_invalid_token_returns_generic_error(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'This password reset link is invalid or has expired.',
        ]);

        $this->assertTrue(
            Hash::check(
                'old-password-123',
                $user->fresh()->password
            )
        );
    }

    public function test_unknown_email_with_invalid_token_returns_same_generic_error(): void
    {
        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => 'unknown@example.com',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'This password reset link is invalid or has expired.',
        ]);
    }

    public function test_expired_token_returns_generic_error(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $token = Password::createToken($user);

        $this->travel(61)->minutes();

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'This password reset link is invalid or has expired.',
        ]);

        $this->assertTrue(
            Hash::check(
                'old-password-123',
                $user->fresh()->password
            )
        );
    }

    public function test_password_confirmation_is_required(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('password');
    }
}