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

    $("input[name='roles[]']").change(function () {
        var roleLabel = $(this).parent().text();
        var statementId = roleLabel.replace(/\s+/g, '').replace("-", "") + "Statement";
        statementId = statementId.charAt(0).toLowerCase() + statementId.slice(1);
        
        if (statementId == "reSellerStatement") {
            statementId = "resellerStatement";
        }
        console.log(statementId);
        console.log($(this).is(":checked"));
        if ($(this).is(":checked")) {
            $("input[name=\"" + statementId + "\"]").attr("required", "required");
        } else {
            $("input[name=\"" + statementId + "\"]").removeAttr("required");
        }
    });
});
