<div class="mb-3">

    <label class="form-label">
        {{__('captcha.label')}}
    </label>

    {!!  \IlyaSapunkov\OrchidCaptcha\Fields\Captcha::make('captcha')
        ->required()
        ->placeholder(__('captcha.placeholder'))
    !!}
</div>

