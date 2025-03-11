function refreshCaptcha(id, generateUrl) {
    const captchaInput = document.getElementById(id);
    const captchaHashInput = document.getElementById(`${id}_hash`);
    const captchaImage = document.getElementById(`${id}_image`);
    fetch(generateUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(response => response.json())
        .then(data => {
            captchaImage.src = data.captchaImage.encoded;
            captchaHashInput.value = data.captchaHash;
            captchaInput.value = '';
        })
        .catch(error => console.error('Error refreshing captcha:', error));
}

