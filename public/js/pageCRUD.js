/**
 * Manage press release fields display
 * 
 * @param {string} pressReleaseType
 * @returns {undefined}
 */
function managePressReleaseFields(pressReleaseType) {
    displayPressReleaseFields(pressReleaseType);
    // on type change, update visible fields
    $('#page_form_type').change(function () {
        displayPressReleaseFields(pressReleaseType);
    });
}

/**
 * Display press release fields by type
 * 
 * @param {string} pressReleaseType
 * @returns {undefined}
 */
function displayPressReleaseFields(pressReleaseType) {
    if ($('#page_form_type :selected').val() == pressReleaseType) {
        // show press release inputs at the beginning
        $("#page_form_category").parent().show();
        $("#page_form_category").prop('required',true);
        $("#page_form_author").parent().show();
        $("#page_form_author").prop('required',true);
        $("#page_form_summary").parent().show();
        $("#page_form_summary").prop('required',true);
        $("#page_form_summaryAr").parent().show();
        $("#page_form_summaryAr").prop('required',true);
        $("#page_form_picture").parent().show();
    } else {
        // hide press release inputs at the beginning
        $("#page_form_category").parent().hide();
        $("#page_form_category").prop('required',false);
        $("#page_form_author").parent().hide();
        $("#page_form_author").prop('required',false);
        $("#page_form_summary").parent().hide();
        $("#page_form_summary").prop('required',false);
        $("#page_form_summaryAr").parent().hide();
        $("#page_form_summaryAr").prop('required',false);
        $("#page_form_picture").parent().hide();
    }
}