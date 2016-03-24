$(document).ready(function () {
    if (typeof (CKEDITOR) !== "undefined") {
        CKEDITOR.replace('page_form_body', {
            filebrowserBrowseUrl: '/cms/page/browse',
            filebrowserUploadUrl: '/cms/page/upload'

        });
        CKEDITOR.replace('page_form_bodyAr', {
            filebrowserBrowseUrl: '/cms/page/browse',
            filebrowserUploadUrl: '/cms/page/upload'

        });
    }
    // moving from upload tab to image info tab
    $(document).on("click", "#cke_170_uiElement", function () {
        $('#cke_Upload_172').removeClass('cke_dialog_tab_selected');
        $('#cke_info_155').addClass('cke_dioalog_tab_selected');
        $('#cke_154_uiElement').show();
        $('#cke_171_uiElement').hide();
        $('#cke_Upload_172').hide();
        $('#cke_122_uiElement').css({"border-color": "#ff0000",
            "border-width": "1px",
            "border-style": "solid"});
    });

});

