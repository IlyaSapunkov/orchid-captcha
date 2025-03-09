@component($typeForm, get_defined_vars())
    <div data-controller="input"
         data-input-mask="{{$mask ?? ''}}"
    >
        <input {{ $attributes }}>
    </div>
    <input type="hidden"
           name="{{ $attributes['id'] }}_hash"
           value="{{ $captchaHash }}">
    <div>
        <img id="{{ $attributes['id'] }}_image" src="{{ $captchaImage }}" alt="Captcha">
        <button type="button" id="{{ $attributes['id'] }}_refresh">Refresh Captcha</button>
    </div>
    <script src="{{ asset('vendor/orchid-captcha/js/captcha.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initCaptcha('{{ $attributes['id'] }}', '{{ $generateUrl }}');
        });
    </script>
@endcomponent
