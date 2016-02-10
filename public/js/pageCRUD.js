$(document).ready(function () {

// Replace the <textarea id="page_form_body"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('page_form_body', {
        filebrowserBrowseUrl: '/browser/browse.php',
        filebrowserUploadUrl: '/uploader/upload.php'
    });
});

