<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    Password::defaults(function () {
        return Password::min(8)
            ->max(255);
    });

    VerifyEmail::toMailUsing(
        function ($notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify your email address | PLANSI')
                ->view('emails.auth.verify-email', [
                    'user' => $notifiable,
                    'url' => $url,
                ]);
        }
    );

    ResetPassword::toMailUsing(
        function ($notifiable, string $url) {
            return (new MailMessage)
                ->subject('Reset your PLANSI password')
                ->view('emails.auth.reset-password', [
                    'user' => $notifiable,
                    'url' => $url,
                ]);
        }
    );
}
}