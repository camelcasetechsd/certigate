if ($('#menu_item_form_parent').length) {
    var menuId = $('#menu_item_form_menu').val();
    // in case selected value is empty and menuId has value set
    // select empty value under proper optGroup "parent"
    if ($('#menu_item_form_parent :selected').val() == false && menuId) {
        // set value for select empty, which is the already expected case
        $('#menu_item_form_parent').val('');
        // loop on options
        // select option with right menu id
        $("#menu_item_form_parent option").each(function()
        {
            // menu id matches value for menu field
            if( $(this).val() === '' && $(this).attr("data-menu") ===  menuId){
                // set that option selected
                $(this).prop('selected', true);
            }
        });
    // selected parent is not empty value "root"          
    } else {
        // update menu field with selected option menu
        menuId = $('#menu_item_form_parent :selected').attr('data-menu');
        $('#menu_item_form_menu').val(menuId);
    }
    // on parent change, update menu field
    $('#menu_item_form_parent').change(function ()
    {
        menuId = $('#menu_item_form_parent :selected').attr('data-menu');
        $('#menu_item_form_menu').val(menuId);
    });
}