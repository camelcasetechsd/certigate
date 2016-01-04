$(document).ready(function () {
    $(".resetButton").click(function () {
        var closestForm = $(this).closest('form');
        closestForm.find(":input")
                .not(':button, :submit, :reset, :hidden')
                .removeAttr('checked').removeAttr('selected')
                .not(':checkbox, :radio, select')
                .val('').removeAttr('value');
        closestForm.find("select").prop('selectedIndex',0);
    });
});