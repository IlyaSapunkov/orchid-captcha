<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha;

use Illuminate\Support\ServiceProvider;
use IlyaSapunkov\OrchidCaptcha\Http\Controllers\LoginController;

class CaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/captcha.php', 'captcha');

        $this->app->bind(\Orchid\Platform\Http\Controllers\LoginController::class, LoginController::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/captcha.php' => config_path('captcha.php'),
        ], ['orchid-captcha', 'orchid-captcha-config']);
        $this->publishes([
            __DIR__ . '/../resources/js/captcha.js' => public_path('vendor/orchid-captcha/js/captcha.js'),
        ], ['orchid-captcha', 'orchid-captcha-assets']);
        $this->publishes([
            __DIR__ . '/../resources/views/captcha.blade.php' => resource_path('views/vendor/orchid-captcha/captcha.blade.php'),
            __DIR__ . '/../resources/views/platform/auth/login.blade.php' => resource_path('views/vendor/platform/auth/login.blade.php'),
        ], ['orchid-captcha', 'orchid-captcha-views']);
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path(),
        ], ['orchid-captcha', 'orchid-captcha-lang']);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'orchid-captcha');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'orchid-captcha');
        $this->loadRoutesFrom(__DIR__ . '/../routes/captcha.php');
    }
}
