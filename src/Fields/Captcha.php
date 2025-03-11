<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Fields;

use IlyaSapunkov\OrchidCaptcha\Services\CaptchaService;
use Orchid\Screen\Fields\Input;
use Random\RandomException;

class Captcha extends Input
{
    /**
     * @var string
     */
    protected $view = 'orchid-captcha::captcha';

    /**
     * CaptchaManager constructor.
     *
     * Initializes the Captcha component by generating a captcha text, hash, and image,
     * and setting required data like validation URL and CSRF token.
     *
     * @throws RandomException
     */
    public function __construct()
    {
        parent::__construct();
        $captchaText = CaptchaService::generateRandomText();

        $this->set('captchaHash', CaptchaService::getHash($captchaText));
        $this->set('captchaImage', CaptchaService::generateCaptchaImage($captchaText)->encode('data-url'));
        $this->set('generateUrl', $this->getGenerateUrl());
        $this->set('validationUrl', $this->getValidationUrl());
        $this->set('csrfToken', $this->getCsrfToken());
    }

    protected function getGenerateUrl(): string
    {
        return route('captcha.generate');
    }

    protected function getValidationUrl(): string
    {
        return route('captcha.validate');
    }

    protected function getCsrfToken(): string
    {
        return csrf_token();
    }
}
