<?php

declare(strict_types=1);

namespace IlyaSapunkov\OrchidCaptcha\Http\Controllers;

use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;
use IlyaSapunkov\OrchidCaptcha\Rules\CaptchaRule;
use Orchid\Platform\Http\Controllers\LoginController as OrchidLoginController;

class LoginController extends OrchidLoginController
{
    public function login(Request $request, CookieJar $cookieJar)
    {
        if (config('captcha.show_in_login_form', false)) {
            $request->validate([
                'captcha' => ['required', new CaptchaRule()],
            ]);
        }

        return parent::login($request, $cookieJar);
    }
}
