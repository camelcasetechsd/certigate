$(document).ready(function () {
// enabling datepicker lib
    $('.datepicker').datepicker();
    // prepare organization checkboxes
    prepareCheckBoxes();
    // intial state for the form
    showOrganizationFieldSet();

    formValidation();
    // on change in Organization Type
    $('.type').change(function () {
        showOrganizationFieldSet();
    });




    function formValidation() {

        $('#orgReg').validate({
            rules: {
                commercialName: {
                    required: true,
                    minlength: 8
                },
                long: {
                    minlength: 3,
                },
                lat: {
                    minlength: 3
                },
                ownerName: {
                    required: true,
                    minlength: 5,
                    maxlength: 15
                },
                ownerNationalId: {
                    required: true,
                    digits: true,
                    minlength: 5,
                    maxlength: 15
                },
                CRNo: {
                    required: true,
                    digits: true,
                    minlength: 5,
                    maxlength: 15
                },
                CRExpiration: {
                    required: true
                },
                CRAttachment: {
                    required: true

                },
                phone1: {
                    required: true,
                    minlength: 7,
                    maxlength: 20
                },
                phone2: {
                    minlength: 7,
                    maxlength: 20
                },
                phone3: {
                    minlength: 7,
                    maxlength: 20
                },
                fax: {
                    maxlength: 20
                },
                addressLine1: {
                    required: true,
                    minlength: 7,
                    maxlength: 50
                },
                addressLine2: {
                    minlength: 7,
                    maxlength: 50

                },
                city: {
                    required: true,
                    maxlength: 20
                },
                zipCode: {
                    required: true,
                    maxlength: 20
                },
                website: {
                    requited: true,
                    maxlength: 30,
                    url: true
                },
                email: {
                    required: true,
                    maxlength: 30,
                    email: true
                },
                focalContactPerson_id: {
                    required: true

                },
                atcLicenseNo: {
                    digits: true,
                    minlength: 7,
                    maxlength: 30,
                },
                atpLicenseNo: {
                    digits: true,
                    minlength: 7,
                    maxlength: 30,
                },
                labsNo: {
                    digits: true,
                    minlength: 1,
                    maxlength: 3,
                },
                classesNo: {
                    digits: true,
                    minlength: 1,
                    maxlength: 3,
                },
                pcsNo_lab: {
                    digits: true,
                    minlength: 1,
                    maxlength: 3,
                },
                pcsNo_class: {
                    digits: true,
                    minlength: 1,
                    maxlength: 3,
                }


            },
            massages: {
                commercialName: {
                    required: "Commercial name is mandatory for organizations",
                    minlength: "Commercial name must not be less than 8 characters long "
                },
                long: {
                    minlength: "Longtitude must not be less than 3 characters long "
                },
                lat: {
                    minlength: "Latitude must not be less than 3 characters long "
                },
                ownerName: {
                    required: "Owner name is mandatory for organizations",
                    minlength: "Owner name must not be less than 5 characters long",
                    maxlength: "Owner name must not be more than 15 characters long"
                },
                ownerNationalId: {
                    required: "Owner national ID is mandatory for organizations",
                    digits: "National Id must be set of integers",
                    minlength: "National Id  must not be less than 5 characters long",
                    maxlength: "National Id must not be more than 15 characters long"
                },
                CRNo: {
                    required: "CR No is mandatory for organizations",
                    digits: "CR No must be set of integers",
                    minlength: "CR No  must not be less than 5 characters long",
                    maxlength: "CR No must not be more than 15 characters long"
                },
                CRExpiration: {
                    required: "CR Expiration Date is mandatory for organizations",
                },
                CRAttachment: {
                    required: "CR Licesne Attachment is mandatory for organizations"
                },
                //messages
                phone1: {
                    required: "At least one phone number is required for organizations",
                    minlength: "Phone Number must be shorter than 7 digits",
                    maxlength: "Phone Number must not be longer than 20 digits"
                },
                phone2: {
                    minlength: "Phone Number must be shorter than 7 digits",
                    maxlength: "Phone Number must not be longer than 20 digits"
                },
                phone3: {
                    minlength: "Phone Number must not be shorter than 7 digits",
                    maxlength: "Phone Number must not be longer than 20 digits"
                },
                addressLine1: {
                    required: "At least one Address is mandatory for organizations ",
                    minlength: "Address must not be shorter than 7 digits",
                    maxlength: "Address must not be longer than 20 digits"
                },
                addressLine2: {
                    minlength: "Address must not be shorter than 7 digits",
                    maxlength: "Address must not be longer than 20 digits"
                },
                city: {
                    required: "City is mandatory for organizations",
                },
                fax: {
                    maxlength: "fax Number must not be longer than 20 digits"

                },
                zipCode: {
                    required: "zipCode is mandatory for organizations",
                    maxlength: "zipCode must not be longer than 20 digits"
                },
                website: {
                    requited: "Website is mandatory for organizations",
                    maxlength: "Website must not be longer than 30 digits"
                },
                email: {
                    required: "email is mandatory for organizations",
                    maxlength: "email must not be longer than 30 digits",
                    email: "please insert a valid emial"
                },
                focalContactPerson_id: {
                    required: "Focal Contact Person is mandatory for organizations",
                }
            }
        });




    }










    //submit organization registeration form
    $('#submit').click(function () {



//add validation method before submit
//        $('#orgReg').submit();
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
//                window.history.back();
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
//            location.reload();
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

    function urlParam(param) {
        location.search.substr(1)
                .split("&")
                .some(function (item) { // returns first occurence and stops
                    return item.split("=")[0] == param && (param = item.split("=")[1])
                })
        return param
    }








});
