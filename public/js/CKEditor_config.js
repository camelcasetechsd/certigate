$(document).ready(function () {
    if (typeof (CKEDITOR) !== "undefined") {
        CKEDITOR.replace('page_form_body', {
            filebrowserBrowseUrl: '/cms/page/browse',
            filebrowserUploadUrl: '/cms/page/upload'

        });
    }
});

