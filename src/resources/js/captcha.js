function initCaptcha(id, generateUrl) {
    const captchaInput = document.getElementById(id);
    const captchaHashInput = document.getElementById(`${id}_hash`);
    const captchaImage = document.getElementById(`${id}_image`);
    const refreshButton = document.getElementById(`${id}_refresh`);

    function refreshCaptcha() {
        fetch(generateUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            captchaImage.src = data.captchaImage;
            captchaHashInput.value = data.captchaHash;
            captchaInput.value = '';
            alert(data.captchaImage);
        })
        .catch(error => console.error('Error refreshing captcha:', error));
    }

    refreshButton.addEventListener('click', refreshCaptcha);

    captchaInput.addEventListener('blur', function () {
        const captchaValue = captchaInput.value;
        const captchaHash = captchaHashInput.value;

        if (!captchaValue || !captchaHash) {
            return;
        }

        fetch(captchaInput.dataset.validationUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': captchaInput.dataset.csrfToken,
            },
            body: JSON.stringify({
                captcha: captchaValue,
                captchaHash: captchaHash
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Капча валидна') {
                alert('Капча верна!');
            } else {
                alert('Капча неверна!');
                refreshCaptcha();
            }
        })
        .catch(error => {
            console.error('Ошибка при проверке капчи:', error);
            refreshCaptcha();
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // This part is kept for backwards compatibility
    const captchaInput = document.querySelector('input[name="captcha"]');
    if (captchaInput) {
        initCaptcha('captcha', captchaInput.dataset.generateUrl);
    }
});
