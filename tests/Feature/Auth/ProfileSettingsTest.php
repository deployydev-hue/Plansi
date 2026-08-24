<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('profile.edit'));

        $response->assertOk();
    }

    public function test_guest_cannot_access_profile_screen(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_update_name_without_changing_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'user@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'New Name',
                'email' => 'user@example.com',
            ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('New Name', $user->name);
        $this->assertSame('user@example.com', $user->email);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create([
            'email' => 'taken@example.com',
        ]);

        $user = User::factory()->create([
            'email' => 'current@example.com',
            'password' => Hash::make('password-123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'taken@example.com',
                'current_password' => 'password-123',
            ]);

        $response->assertSessionHasErrors('email');

        $this->assertSame(
            'current@example.com',
            $user->fresh()->email
        );
    }

    public function test_current_password_is_required_when_email_changes(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'password' => Hash::make('password-123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'new@example.com',
                'current_password' => '',
            ]);

        $response->assertSessionHasErrors('current_password');

        $this->assertSame(
            'old@example.com',
            $user->fresh()->email
        );
    }

    public function test_current_password_must_be_correct_when_email_changes(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
            'password' => Hash::make('password-123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'new@example.com',
                'current_password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors('current_password');

        $this->assertSame(
            'old@example.com',
            $user->fresh()->email
        );
    }

    public function test_changing_email_requires_verification_again(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'old@example.com',
            'password' => Hash::make('password-123'),
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => 'new@example.com',
                'current_password' => 'password-123',
            ]);

        $user->refresh();

        $this->assertSame('new@example.com', $user->email);
        $this->assertNull($user->email_verified_at);

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_user_can_keep_their_existing_email(): void
    {
        $user = User::factory()->create([
            'email' => 'same@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'same@example.com',
            ]);

        $response->assertSessionHasNoErrors();

        $this->assertSame(
            'same@example.com',
            $user->fresh()->email
        );
    }
}