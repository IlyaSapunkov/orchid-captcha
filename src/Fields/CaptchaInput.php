<?php

namespace IlyaSapunkov\OrchidCaptcha\Fields;

use Orchid\Screen\Fields\Input;
use IlyaSapunkov\OrchidCaptcha\Services\CaptchaService;
class CaptchaInput extends Input
{
    /**
     * @var string
     */
    protected string $type = 'text';

    /**
     * @param CaptchaService $captchaService
     */
    public function __construct(protected CaptchaService $captchaService)
    {
        parent::__construct();

        // Генерация капчи
        $captchaText = $this->captchaService->generateCaptcha();
        $captchaImage = $this->captchaService->generateCaptchaImage($captchaText);

        // Установка атрибутов
        $this->set('captchaImage', $captchaImage);
        $this->set('captchaText', $captchaText);
        $this->set('validationUrl', $this->getValidationUrl());
        $this->set('csrfToken', $this->getCsrfToken());
    }

    /**
     * @return mixed
     */
    public function render(): mixed
    {
        return view('orchid-captcha::captcha-input', $this->getAttributes());
    }

    /**
     * @return string
     */
    protected function getValidationUrl(): string
    {
        return route('captcha.validate');
    }

    /**
     * @return string
     */
    protected function getCsrfToken(): string
    {
        return csrf_token();
    }
}
