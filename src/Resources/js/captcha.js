document.addEventListener('DOMContentLoaded', function () {
    const captchaInput = document.querySelector('input[name="captcha"]');
    const captchaForm = document.querySelector('form');

    if (captchaInput && captchaForm) {
        captchaInput.addEventListener('blur', function () {
            const captchaValue = captchaInput.value;

            if (!captchaValue) {
                return;
            }

            fetch(captchaInput.dataset.validationUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': captchaInput.dataset.csrfToken,
                },
                body: JSON.stringify({ captcha: captchaValue }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        alert('Капча верна!');
                    } else {
                        alert('Капча неверна!');
                    }
                })
                .catch(error => {
                    console.error('Ошибка при проверке капчи:', error);
                });
        });
    }
});
