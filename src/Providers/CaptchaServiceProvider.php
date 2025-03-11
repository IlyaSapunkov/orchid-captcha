<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Providers;

use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/captcha.php', 'captcha');
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
            __DIR__ . '/../resources/views/captcha-input.blade.php' => resource_path('views/vendor/orchid-captcha/captcha-input.blade.php'),
        ], ['orchid-captcha', 'orchid-captcha-views']);
        $this->publishes([
            __DIR__ . '/../resources/lang' => lang_path(),
        ], ['orchid-captcha', 'orchid-captcha-lang']);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'orchid-captcha');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'orchid-captcha');
        $this->loadRoutesFrom(__DIR__ . '/../routes/captcha.php');
    }
}
