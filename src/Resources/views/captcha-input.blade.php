<div>
    <label for="{{ $name }}">{{ $title }}</label>
    <input type="{{ $type }}"
           name="{{ $name }}"
           id="{{ $name }}"
           class="form-control"
           required
           data-validation-url="{{ $validationUrl }}"
           data-csrf-token="{{ $csrfToken }}">
    <div>
        <img src="{{ $captchaImage }}" alt="Captcha">
    </div>
    <script src="{{ asset('vendor/orchid-captcha/js/captcha.js') }}"></script>
</div>
