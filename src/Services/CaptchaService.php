<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Services;

use Illuminate\Contracts\Encryption\Encrypter;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Random\RandomException;

class CaptchaService
{
    /**
     * Generates a CAPTCHA image based on the provided code.
     *
     * @param  string  $code  The string that the CAPTCHA image will represent.
     *
     * @return Image The generated CAPTCHA image.
     *
     * @throws RandomException
     */
    public static function generateCaptchaImage(string $code): Image
    {
        $width = 120;
        $height = 50;
        $padding = 2;
        $backColor = 0xFFFFFF;
        $foreColor = 0x2040A0;
        $fontFile = __DIR__ . '/../../resources/fonts/SpicyRice.ttf';

        $iconColor = 0x808080;
        $iconSize = 10;
        $iconFontFile = __DIR__ . '/../../resources/fonts/FontAwesome.ttf';

        $length = strlen($code);
        $fontSize = 30;

        $manager = new ImageManager(['driver' => 'gd']);
        $image = $manager->canvas($width, $height, '#' . str_pad(dechex($backColor), 6, '0', STR_PAD_LEFT));

        // Draw refresh icon in the top-left corner
        $image->text('f021', 0, 15, function ($font) use ($iconFontFile, $iconSize, $iconColor): void {
            $font->file($iconFontFile);
            $font->size($iconSize);
            $font->color('#' . str_pad(dechex($iconColor), 6, '0', STR_PAD_LEFT));
        });

        $box = imagettfbbox($fontSize, 0, $fontFile, $code);
        $textWidth = $box[4] - $box[0];
        $textHeight = $box[1] - $box[5];

        $scale = min(($width - $padding * 2) / $textWidth, ($height - $padding * 2) / $textHeight);

        $x = 10;
        $y = round($height * 27 / 40);

        for ($i = 0; $i < $length; $i++) {
            $fontSizeCurrent = (int) (random_int(26, 32) * $scale * 0.8);
            $angle = random_int(-10, 10);

            $image->text($code[$i], $x, (int) $y, function ($font) use ($fontFile, $fontSizeCurrent, $foreColor, $angle): void {
                $font->file($fontFile);
                $font->size($fontSizeCurrent);
                $font->color('#' . str_pad(dechex($foreColor), 6, '0', STR_PAD_LEFT));
                $font->angle($angle);
            });

            $box = imagettfbbox($fontSizeCurrent, $angle, $fontFile, $code[$i]);
            $x = 10 + (($width - 20) / $length) * ($i + 1);
        }

        return $image;
    }

    /**
     * Generates a random string of specified length using configurable letters and vowels.
     *
     * The generated string alternates between letters and vowels based on random conditions,
     * creating a mix that adheres to the provided probability rules.
     *
     * @return string Randomly generated string.
     *
     * @throws RandomException
     */
    public static function generateRandomString(): string
    {
        $length = config('captcha.length', 5);
        $letters = config('captcha.letters', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $vowels = config('captcha.vowels', '0123456789');
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 && random_int(0, 10) > 2 || !($i % 2) && random_int(0, 10) > 9) {
                $code .= $vowels[random_int(0, strlen($vowels) - 1)];
            } else {
                $code .= $letters[random_int(0, strlen($letters) - 1)];
            }
        }

        return $code;
    }

    public static function getHash(string $text): string
    {
        return app(Encrypter::class)->encrypt($text . '|' . time());
    }
}
