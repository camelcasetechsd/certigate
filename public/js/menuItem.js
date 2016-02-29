$(document).ready(function () {
    // hide inputs at the beginning
    $("#menu_item_form_directUrl").parent().hide();
    $("#menu_item_form_page").parent().hide();

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
    if (type == 1) {
        $("#menu_item_form_directUrl").parent().hide();
        $("#menu_item_form_page").parent().show();
    } else if (type == 2) {
        $("#menu_item_form_page").parent().hide();
        $("#menu_item_form_directUrl").parent().show();
    }
}