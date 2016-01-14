$(document).ready(function () {
// enabling datepicker lib
    $('.datepicker').datepicker();
    // prepare organization checkboxes
    prepareCheckBoxes();
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
    });








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







    function prepareCheckBoxes() {
        $orgType = urlParam('org');

        switch ($orgType) {
            case "atp":
                $('#type-1').attr('checked', 'checked');
                $('#hiddenType').val('2');
                break;
            case "atc":
                $('#type-0').attr('checked', 'checked');
                $('#hiddenType').val('1');
                break;
            default :
                window.history.back();
                break;
        }
    }



    // handling organization type change
    function showOrganizationFieldSet() {
        $atcBox = $('input:checkbox[id=type-0]').is(":checked");
        $atpBox = $('input:checkbox[id=type-1]').is(":checked");

        // BOTH SELECTED
        if ($atcBox && $atpBox) {
            $('#hiddenType').val('');
            $('#hiddenType').val('3');

            $('#atpSet').show();
            $('#atcSet').show();

        }

        //ATC ONLY
        if ($atcBox && !$atpBox) {
            $('#hiddenType').val('');
            $('#hiddenType').val('1');

            $('#atcSet').show();
            $('#atpSet').hide();

            clearAtpInputs();

        }

        //ATP ONLY
        if ($atpBox && !$atcBox) {
            $('#hiddenType').val('');
            $('#hiddenType').val('2');

            $('#atpSet').show();
            $('#atcSet').hide();
            clearAtcInputs();
        }

        //ALERT NOTHING SELECTED
        if (!$atcBox && !$atpBox) {
            location.reload();
        }




    }

    function clearAtpInputs() {

        $('#atpLicenseNo').val('');
        $('#atpLicenseAttachment').val('');
        $('#atpLicenseExpiration').val('');
        $('#labsNo').val('');
        $('#pcsNo_lab').val('');
        $('#internetSpeed_lab').val('');
        $('#operatingSystem').val('');
        $('#operatingSystemLang').val('');
        $('#officeLang').val('');
        $('#officeVersion').val('');
    }

    function clearAtcInputs() {
        $('#atcLicenseNo').val('');
        $('#atcLicenseAttachment').val('');
        $('#atcLicenseExpiration').val('');
        $('#classesNo').val('');
        $('#pcsNo_class').val('');

    }

    function urlParam(name) {
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        return results[1] || 0;
    }


});
