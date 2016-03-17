$(document).ready(function () {
    if($("form[name=signin_header_form]").length){
        var form = $("form[name=signin_header_form]");
        var action = form.attr("action");
        var queryString = window.location.href.slice(window.location.href.indexOf('?'));
        form.attr("action", action + queryString);
    }
});