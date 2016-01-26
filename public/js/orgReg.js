$(document).ready(function () {

    $('#org_form_saveState').click(function (e) {

        if ($('#org_form_commercialName').val() !== "") {
            $state = $('#org_form').serialize();
            $.ajax({
                type: "POST",
                url: "/organizations/savestate",
                data: {
                    saveState: $state
                },
                dataType: "json"
            });
            window.location.replace("/organizations/atps");
        }
    });

    // in organization create & delete
    if (window.location.href.indexOf("new?organization=") > -1) {

        $orgType = getParameterByName('organization');
        switch ($orgType) {
            case '1' :
                $('#org_form_type').val("1");
                $('.atpSet').hide();
                $('.atpSet').removeAttr('required');
                $('#org_form_atcPrivacyStatement').after('&nbsp;<a href="" id="atcModalLink" class="checkboxAgreement" data-toggle="modal" data-target="#AtcModal">i agree to Atc\'s privacy statement</a>')

                break;
            case '2' :
                $('#org_form_type').val("2");
                $('.atcSet').hide();
                $('.atcSet').removeAttr('required');
                $('#org_form_atpPrivacyStatement').after('&nbsp;<a href="" id="atpModalLink" class="checkboxAgreement atpSet" data-toggle="modal" data-target="#AtpModal">i agree to Atp\'s privacy statement</a>')

                break;
            case '3' :
                $('#org_form_type').val("3");
                $('#org_form_atpPrivacyStatement').after('&nbsp;<a href="" id="atpModalLink" class="checkboxAgreement atpSet" data-toggle="modal" data-target="#AtpModal">i agree to Atp\'s privacy statement</a>')
                $('#org_form_atcPrivacyStatement').after('&nbsp;<a href="" id="atcModalLink" class="checkboxAgreement atcSet" data-toggle="modal" data-target="#AtcModal">i agree to Atc\'s privacy statement</a>')

                break;
            default:

                //for hacking
                alert('please select organization type');
                window.location.replace("/organizations/type");
                break;

        }

    }
    // in type page
    if (window.location.href.indexOf("type?type=") > -1) {

        $orgType = getParameterByName('type');
        switch ($orgType) {
            case '1' :
                $('#type-1').prop("checked", "checked");
                $('#type_form_type').val("1");
                break;
            case '2' :
                $('#type-2').prop("checked", "checked");
                $('#type_form_type').val("2");
                break;
            default:
                //for hacking
//            window.location.replace("/sign/out");
                break;
        }
    }

    $('.orgType').change(function () {
        $atcBox = $('input:checkbox[id=type-1]').is(":checked");
        $atpBox = $('input:checkbox[id=type-2]').is(":checked");
        if ($atcBox && !$atpBox) {
            $('#type_form_type').val("1");
        } else if (!$atcBox && $atpBox) {
            $('#type_form_type').val("2");
        }
        else if ($atcBox && $atpBox) {
            $('#type_form_type').val("3");
        }
        else if (!$atcBox && !$atpBox) {
            alert("you need to choose type");
            $('#type_form_type').val("");
        }
    });




    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }


});
