<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Policies\StipendPolicy;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Stipend' => 'App\Policies\StipendPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // Created custom link email for verification
         VerifyEmail::createUrlUsing(function ($notifiable) {
            
            $frontendUrl = env('STIPENNY_CLIENT_URL');

            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return $frontendUrl . 'verify/?verify_url=' . urlencode($verifyUrl);
        });

        // Created custom reset link for email forgot password 
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
             $frontendUrl = env('STIPENNY_CLIENT_URL');
            return $frontendUrl ."reset/?token={$token}&email=". $notifiable->getEmailForPasswordReset();
        });
    }
}
