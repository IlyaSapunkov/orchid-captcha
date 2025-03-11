@component($typeForm, get_defined_vars())
    <div data-controller="input"
         data-input-mask="{{$mask ?? ''}}"
    >
        <input {{ $attributes }} >
    </div>
    <input type="hidden"
           id="{{ $id }}_hash"
           name="{{ $name }}_hash"
           value="{{ $captchaHash }}">
    <div>
        <img id="{{ $id }}_image" src="{{ $captchaImage }}" alt="Captcha" onclick="refreshCaptcha('{{ $id }}', '{{ $generateUrl }}');">
    </div>
    <script src="{{ asset('vendor/orchid-captcha/js/captcha.js') }}"></script>
@endcomponent
