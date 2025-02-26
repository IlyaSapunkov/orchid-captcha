<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Providers;

use Illuminate\Support\ServiceProvider;
use IlyaSapunkov\OrchidCaptcha\Fields\CaptchaInput;
use IlyaSapunkov\OrchidCaptcha\Services\CaptchaService;
use Illuminate\Contracts\Encryption\Encrypter;
use Orchid\Screen\Field;

class CaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/captcha.php', 'captcha');

        $this->app->singleton(CaptchaService::class, function ($app) {
            return new CaptchaService(
                $app->make(Encrypter::class),
                config('captcha.encryption_key')
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/captcha.php' => config_path('captcha.php'),
            __DIR__ . '/../../resources/js/captcha.js' => public_path('vendor/orchid-captcha/js/captcha.js'),
        ], 'captcha-assets');

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'orchid-captcha');

        Field::macro('captcha', function (string $name) {
            return (new CaptchaInput($this->app->make(CaptchaService::class)))
                ->name($name)
                ->title('Введите текст с картинки');
        });
    }
}
