<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Encryption\Encrypter;
use Illuminate\Translation\PotentiallyTranslatedString;

class CaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $captchaHash = request($attribute . '_hash');

        if (!$captchaHash) {
            $fail(__('captcha.required', ['attribute' => $attribute . '_hash']));

            return;
        }

        try {
            $decryptedText = app(Encrypter::class)->decrypt($captchaHash);
            [$captchaText, $timestamp] = explode('|', $decryptedText);
        } catch (Exception $e) {
            $fail(__('captcha.invalid', ['attribute' => $attribute]));

            return;
        }

        if (time() - $timestamp > config('captcha.expiration_time')) {
            $fail(__('captcha.expired', ['attribute' => $attribute]));

            return;
        }

        if (strtolower($value) !== strtolower($captchaText)) {
            $fail(__('captcha.failed'));
        }
    }
}
