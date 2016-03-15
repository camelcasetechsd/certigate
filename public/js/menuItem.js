$(document).ready(function () {
    // hide inputs at the beginning
    displayMenuItemFormFieldsByType();

    var menuItemType = $("input[type='radio']:checked");
    if (menuItemType.length > 0) {
        var menuItemTypeValue = menuItemType.val();
        displayMenuItemFormFieldsByType(menuItemTypeValue);
    }
    $('.menu_item_type').change(function () {
        var menuItemTypeValue = $(this).val();
        displayMenuItemFormFieldsByType(menuItemTypeValue);
    });
});

/**
 * Display menu item form fields according to type
 * 
 * @param {int} type
 * @returns {undefined}
 */
function displayMenuItemFormFieldsByType(type) {
    if (type == 2) {
        $("#menu_item_form_page").parent().hide();
        $("#menu_item_form_page").prop('required', false);
        $("#menu_item_form_directUrl").parent().show();
        $("#menu_item_form_directUrl").prop('required', true);
    } else {
        $("#menu_item_form_directUrl").parent().hide();
        $("#menu_item_form_directUrl").prop('required', false);
        $("#menu_item_form_page").parent().show();
        $("#menu_item_form_page").prop('required', true);
    }
}