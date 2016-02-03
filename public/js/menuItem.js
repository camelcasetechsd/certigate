$(function () {
    $('.menu_item_type').change(function(){
        var menu_item_type_value = $(this).val();
        
        if(menu_item_type_value == 1){
            $("#menu_item_form_directUrl").parent().hide();
            $("#menu_item_form_page").parent().show();
        } else if(menu_item_type_value == 2) {
            $("#menu_item_form_page").parent().hide();
            $("#menu_item_form_directUrl").parent().show();
        }
    })
});