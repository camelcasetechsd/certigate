if ($('.checkAllModuleRoutes').length) {
    $(".checkAllModuleRoutes").change(function () {
        $('.checkAll-' + $(this).attr("data-module") + ' input:checkbox').prop('checked', this.checked);
    });
}