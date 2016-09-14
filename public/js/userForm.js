$(document).ready(function () {
    $('.refresh_captcha').click(function () {
        $.ajax({
            url: '/users/refreshcaptcha',
            dataType: 'json',
            success: function (data) {
                $('#user_form_captcha-image').attr('src', data.src);
                $('#user_form_captcha-hidden').attr('value', data.id);
            }
        });
        return false;
    });
});
