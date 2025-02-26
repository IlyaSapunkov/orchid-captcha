<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Services;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CaptchaService
{
    public function __construct(protected Encrypter $encrypter, protected string $key)
    {
    }

    /**
     * @return string
     */
    public function generateCaptcha(): string
    {
        $captchaText = $this->generateRandomString(5);
        $encryptedText = $this->encrypter->encrypt($captchaText . '|' . time());

        Session::put('captcha', $encryptedText);

        return $captchaText;
    }

    /**
     * @param $input
     *
     * @return bool
     */
    public function validateCaptcha($input): bool
    {
        $encryptedText = Session::get('captcha');
        if (!$encryptedText) {
            return false;
        }

        $decryptedText = $this->encrypter->decrypt($encryptedText);
        [$captchaText, $timestamp] = explode('|', $decryptedText);

        // Проверка времени жизни капчи (например, 5 минут)
        if (time() - $timestamp > 300) {
            return false;
        }

        return $input === $captchaText;
    }

    /**
     * @param $text
     *
     * @return string
     */
    public function generateCaptchaImage($text): string
    {
        $manager = new ImageManager(
            new Driver()
        );
        $image = $manager->create(150, 50);
        $image->text($text, 75, 25, function ($font): void {
            $font->file(__DIR__ . '/../../resources/fonts/Arial.ttf');
            $font->size(24);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        return $image->encode()->toDataUri();
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString(int $length = 5): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
