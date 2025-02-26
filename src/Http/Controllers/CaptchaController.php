<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IlyaSapunkov\OrchidCaptcha\Services\CaptchaService;

class CaptchaController
{
    /**
     * @param CaptchaService $captchaService
     */
    public function __construct(protected CaptchaService $captchaService)
    {
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function validateCaptcha(Request $request): JsonResponse
    {
        $request->validate([
            'captcha' => 'required|string',
        ]);

        $isValid = $this->captchaService->validateCaptcha($request->input('captcha'));

        return response()->json([
            'valid' => $isValid,
        ]);
    }
}
