<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();

            return app()->isProduction() ? $rule->uncompromised() : $rule;
        });

        // Validate that Sanctum personal access tokens are active
        Sanctum::authenticateAccessTokensUsing(function ($accessToken, $isValid) {
            if (isset($accessToken->is_active) && ! $accessToken->is_active) {
                return false;
            }

            return $isValid;
        });
    }
}
