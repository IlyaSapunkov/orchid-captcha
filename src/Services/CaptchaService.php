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
     * Generates a CAPTCHA image based on the provided text.
     *
     * @param  string  $text  The string that the CAPTCHA image will represent.
     *
     * @return string The generated CAPTCHA image.
     *
     * @throws RandomException
     */
    public static function generateCaptchaImage(string $text): string
    {
        $width = config('captcha.sizes.width', 120);
        $height = config('captcha.sizes.height', 50);

        $manager = new ImageManager(['driver' => 'gd']);
        $backColor = config('captcha.colors.background', 0xFFFFFF);
        $image = $manager->canvas($width, $height, '#' . str_pad(dechex($backColor), 6, '0', STR_PAD_LEFT));

        if (config('captcha.use_noise', true)) {
            self::renderNoise($width, $height, $image);
        }

        self::renderText($text, $width, $height, $image);

        self::renderIcon($image);

        return $image->encode('data-url')->encoded;
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
    public static function generateRandomText(): string
    {
        $length = config('captcha.length', 5);
        $letters = config('captcha.letters', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $vowels = config('captcha.vowels', '0123456789');
        $text = '';
        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 && random_int(0, 10) > 2 || !($i % 2) && random_int(0, 10) > 9) {
                $text .= $vowels[random_int(0, strlen($vowels) - 1)];
            } else {
                $text .= $letters[random_int(0, strlen($letters) - 1)];
            }
        }

        return $text;
    }

    /**
     * Generates a hash value for the provided text using the Laravel encrypter.
     *
     * @param  string  $text  The text to be hashed.
     */
    public static function getHash(string $text): string
    {
        return app(Encrypter::class)->encrypt($text . '|' . time());
    }

    /**
     * Draw refresh icon in the top-left corner.
     *
     * @param  Image  $image  The image object to render the icon on.
     */
    protected static function renderIcon(Image $image): void
    {
        $iconColor = config('captcha.colors.icon', 0x808080);
        $iconSize = config('captcha.sizes.font.icon', 10);
        $iconFontFile = __DIR__ . '/../../resources/fonts/FontAwesome.ttf';

        $image->text('f021', 0, 15, function ($font) use ($iconFontFile, $iconSize, $iconColor): void {
            $font->file($iconFontFile);
            $font->size($iconSize);
            $font->color('#' . str_pad(dechex($iconColor), 6, '0', STR_PAD_LEFT));
        });
    }

    /**
     * Adds random noise elements, such as lines, rectangles, and circles, to the image.
     *
     * @param  int  $width  The width of the image.
     * @param  int  $height  The height of the image.
     * @param  Image  $image  The image object to which noise will be added.
     *
     * @throws RandomException
     */
    protected static function renderNoise(int $width, int $height, Image $image): void
    {
        for ($i = 0; $i < random_int(5, 10); $i++) {
            $randX1 = random_int(0, $width - 1);
            $randY1 = random_int(0, $height - 1);
            $randX2 = random_int(0, $width - 1);
            $randY2 = random_int(0, $height - 1);
            $randColor = '#' . str_pad(dechex(random_int(0x000000, 0xCCCCCC)), 6, '0', STR_PAD_LEFT);
            $lineThickness = random_int(1, 3); // Random line thickness

            $shapeType = random_int(0, 2); // 0: line, 1: rectangle, 2: circle

            if ($shapeType === 0) {
                // Draw a line
                $image->line($randX1, $randY1, $randX2, $randY2, function ($draw) use ($randColor): void {
                    $draw->color($randColor);
                });
            } elseif ($shapeType === 1) {
                // Draw a rectangle
                $image->rectangle($randX1, $randY1, $randX2, $randY2, function ($draw) use ($randColor, $lineThickness): void {
                    $draw->border($lineThickness, $randColor);
                });
            } else {
                // Draw a circle
                $radius = (int) sqrt(pow($randX2 - $randX1, 2) + pow($randY2 - $randY1, 2)) / 2;
                $centerX = ($randX1 + $randX2) / 2;
                $centerY = ($randY1 + $randY2) / 2;
                $image->circle($radius * 2, $centerX, $centerY, function ($draw) use ($randColor, $lineThickness): void {
                    $draw->border($lineThickness, $randColor);
                });
            }
        }
    }

    /**
     * Renders text on an image with specified dimensions and styling.
     *
     * @param  string  $code  The text to be rendered.
     * @param  mixed  $width  The width of the rendering area.
     * @param  mixed  $height  The height of the rendering area.
     * @param  Image  $image  The image instance to render the text onto.
     *
     * @throws RandomException
     */
    protected static function renderText(string $code, mixed $width, mixed $height, Image $image): void
    {
        $length = strlen($code);
        $foreColor = config('captcha.colors.text', 0x2040A0);
        $fontSize = config('captcha.sizes.font.text', 30);
        $fontFile = __DIR__ . '/../../resources/fonts/SpicyRice.ttf';

        $box = imagettfbbox($fontSize, 0, $fontFile, $code);
        $textWidth = $box[4] - $box[0];
        $textHeight = $box[1] - $box[5];
        $padding = 2;
        $scale = min(($width - $padding * 2) / $textWidth, ($height - $padding * 2) / $textHeight);

        $x = 10;
        $y = round($height * 27 / 40);

        for ($i = 0; $i < $length; $i++) {
            $fontSizeCurrent = (int) (random_int((int) round($fontSize * 0.8), (int) round($fontSize * 1.2)) * $scale * 0.8);
            $angle = random_int(-config('captcha.angle', 20), config('captcha.angle', 20));

            $image->text($code[$i], $x, (int) $y, function ($font) use ($fontFile, $fontSizeCurrent, $foreColor, $angle): void {
                $font->file($fontFile);
                $font->size($fontSizeCurrent);
                $font->color('#' . str_pad(dechex($foreColor), 6, '0', STR_PAD_LEFT));
                $font->angle($angle);
            });

            $x = 10 + (($width - 20) / $length) * ($i + 1);
        }
    }
}
