<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use IlyaSapunkov\OrchidCaptcha\Http\Controllers\CaptchaController;

Route::prefix('captcha')->group(function (): void {
    Route::post('validate', [CaptchaController::class, 'validateCaptcha'])
        ->name('captcha.validate');
});
