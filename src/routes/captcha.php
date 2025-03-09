<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use IlyaSapunkov\OrchidCaptcha\Http\Controllers\CaptchaController;

Route::prefix('captcha')->group(function (): void {
    Route::get('image', [CaptchaController::class, 'image'])->name('captcha.image');
    Route::get('generate', [CaptchaController::class, 'generate'])->name('captcha.generate');
    Route::post('validate', [CaptchaController::class, 'validate'])->name('captcha.validate');
});
