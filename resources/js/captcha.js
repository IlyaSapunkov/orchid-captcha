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
            captchaImage.src = data.captcha_image;
            captchaHashInput.value = data.captcha_hash;
            captchaInput.value = '';
        })
        .catch(error => console.error('Error refreshing captcha:', error));
}

