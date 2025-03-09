<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use IlyaSapunkov\OrchidCaptcha\Services\CaptchaService;
use Random\RandomException;

class CaptchaController
{
    public function __construct(protected CaptchaService $captchaService)
    {
    }

    /**
     * For testing purposes only.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|object
     *
     * @throws RandomException
     */
    public function image()
    {
        [$captchaText, $encryptedText] = $this->captchaService->generateCaptcha(10);
        $image = $this->captchaService->generateCaptchaImage($captchaText);

        return response($image->encode(), 200, ['Content-Type' => 'image/png']);
    }

    /**
     * Generates a CAPTCHA image and its corresponding hash for verification.
     *
     * @param  int  $length  The length of the CAPTCHA text to generate. Defaults to 5.
     *
     * @throws RandomException
     */
    public function generate(int $length = 5): JsonResponse
    {
        [$captchaText, $encryptedText] = $this->captchaService->generateCaptcha($length);
        $image = $this->captchaService->generateCaptchaImage($captchaText);

        return response()->json([
            'captchaImage' => $image->encode('data-url'),
            'captchaHash' => $encryptedText,
        ]);
    }

    /**
     * Validates the captcha input from the request.
     *
     * @param  Request  $request  The HTTP request containing the captcha data.
     *
     * @return JsonResponse A JSON response indicating the validation result.
     *
     * @throws ValidationException If the captcha validation fails.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'captcha' => 'required|string',
            'captchaHash' => 'required|string',
        ]);

        $validationResult = $this->captchaService->validateCaptcha(
            $request->input('captcha'),
            $request->input('captchaHash')
        );

        if (!$validationResult['status']) {
            throw ValidationException::withMessages(['captcha' => $validationResult['error']]);
        }

        return response()->json();
    }
}
