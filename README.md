# Orchid Captcha

Orchid Captcha is a package that provides captcha functionality for the Orchid Platform in Laravel applications.

## Installation

You can install the package via composer:

```bash
composer require ilyasapunkov/orchid-captcha
```

## Publishing Assets

After installation, you need to publish the package assets. Run the following command:

```php
php artisan vendor:publish --tag=orchid-captcha
```
This will publish the following files:
- Configuration file: config/captcha.php
- JavaScript file: public/vendor/orchid-captcha/js/captcha.js
- Blade file: resources/views/vendor/orchid-captcha/captcha-input.blade.php
- Language files: lang

Or publish separately:
```php
php artisan vendor:publish --tag=orchid-captcha-config
php artisan vendor:publish --tag=orchid-captcha-assets
php artisan vendor:publish --tag=orchid-captcha-views
php artisan vendor:publish --tag=orchid-captcha-lang
```
After publishing, you can modify these files to customize the behavior and appearance of the captcha in your application.

## Usage

To use the captcha in your Orchid screens, you can add the captcha field to your layout:

```php
use IlyaSapunkov\OrchidCaptcha\Screen\Fields\Captcha;

// In your screen's layout method
public function layout(): array
{
    return [
        // ... other fields
        Captcha::make('captcha')
            ->title('Verify you are human'),
    ];
}
```

Don't forget to validate the captcha in your screen's method:
```php
use IlyaSapunkov\OrchidCaptcha\Rules\CaptchaRule;

public function yourMethod(Request $request)
{
    $request->validate([
        'captcha' => ['required', new CaptchaRule()],
    ]);

    // ... rest of your method
}
```
