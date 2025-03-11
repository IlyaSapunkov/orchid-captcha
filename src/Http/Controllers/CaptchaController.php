<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use IlyaSapunkov\OrchidCaptcha\Rules\CaptchaRule;
use IlyaSapunkov\OrchidCaptcha\Services\CaptchaService;
use Random\RandomException;

class CaptchaController
{
    /**
     * Generates a CAPTCHA image and its corresponding hash for verification.
     *
     * @return JsonResponse
     *
     * @throws RandomException
     */
    public function generate(): JsonResponse
    {
        $captchaText = CaptchaService::generateRandomString();

        return response()->json([
            'captchaImage' => CaptchaService::generateCaptchaImage($captchaText)->encode('data-url'),
            'captchaHash' => CaptchaService::getHash($captchaText),
        ]);
    }

    /**
     * Validates the captcha input from the request.
     *
     * @param  Request  $request  The HTTP request containing the captcha data.
     *
     * @return JsonResponse A JSON response indicating the validation result.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'captcha' => ['required|string', new CaptchaRule()],
            'captcha_hash' => 'required|string',
        ]);

        return response()->json();
    }
}
