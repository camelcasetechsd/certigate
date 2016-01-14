$(document).ready(function () {
//    Note: need to check if user selected any type of organizations before submit
// enabling datepicker lib
    $('.datepicker').datepicker();
    // intial state for the form
    showOrganizationFieldSet();
    // on change in Organization Type
    $('.type').change(function () {
        showOrganizationFieldSet();
    });
    //submit organization registeration form
    $('#submit').click(function () {
//add validation method before submit
        $('#orgReg').submit();
    })







    function checkTypeValidations() {
        switch ($('#hiddenType').val()) {
            // atc
            case '1':
                $('#atcLicenseNo').attr('required', 'true');
                $('#atcLicenseExpiration').attr('required', 'true');
                $('#atcLicenseAttachment').attr('required', 'true');
                $('#atpLicenseNo').attr('required', 'false');
                $('#atpLicenseExpiration').attr('required', 'false');
                $('#atpLicenseAttachment').attr('required', 'false');

                break;
            // atp    
            case '2':
                $('#atpLicenseNo').attr('required', 'true');
                $('#atpLicenseExpiration').attr('required', 'true');
                $('#atpLicenseAttachment').attr('required', 'true');
                $('#atcLicenseNo').attr('required', 'false');
                $('#atcLicenseExpiration').attr('required', 'false');
                $('#atcLicenseAttachment').attr('required', 'false');

                break;
            // both    
            case '3':
                $('#atcLicenseNo').attr('required', 'true');
                $('#atcLicenseExpiration').attr('required', 'true');
                $('#atcLicenseAttachment').attr('required', 'true');
                $('#atpLicenseNo').attr('required', 'true');
                $('#atpLicenseExpiration').attr('required', 'true');
                $('#atpLicenseAttachment').attr('required', 'true');
                break;
        }

    }















        // handling organization type change
    function showOrganizationFieldSet() {

        $atcBox = $('input:checkbox[id=type-0]').is(":checked");
        $atpBox = $('input:checkbox[id=type-1]').is(":checked");
        if ($atcBox) {
            $('#atcSet').show();
            $('#hiddenType').val("");
            $('#hiddenType').val(1);
            checkTypeValidations();
        } else {
            $('#atcSet').hide();
            $('#atcLicenseNo').val("");
            $('#atcLicenseExpiration').val("");
            $('#atcLicenseAttachment').val("");
        }

        if ($atpBox) {
        // still need to add required
            $('#atpSet').show();
            $('#hiddenType').val("");
            $('#hiddenType').val(2);
            checkTypeValidations();
        } else {
            $('#atpSet').hide();
            $('#atpLicenseNo').val("");
            $('#atpLicenseExpiration').val("");
            $('#atpLicenseAttachment').val("");
        }


        if ($atpBox && $atcBox) {
            $('#hiddenType').val("");
            $('#hiddenType').val(3);
            checkTypeValidations();
        }



    }


});
