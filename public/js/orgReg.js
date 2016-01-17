//$(document).ready(function () {
//
//    // hide both atc & atp sets
//    hideSet('atc');
//    hideSet('atp')
//    // enabling datepicker lib
//    $('.datepicker').datepicker();
//    // prepare organization checkboxes
//    prepareCheckBoxes();
//
//
//
//    $('#type-1').change(function () {
//        $atcBox = $('input:checkbox[id=type-1]').is(":checked");
//        $atpBox = $('input:checkbox[id=type-2]').is(":checked");
//
//        if ($atcBox) {
//            addRequired('atc');
//            // show atc fields
//            showSet('atc');
//            if ($atpBox) {
//                // both checked
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("3");
//            } else {                
//                //only atc 
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("1");
//            }
//
//        } else {
//            removeRequired('atc');
//            // hide atc fields
//            hideSet('atc');
//            if ($atpBox) {
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("2");
//            } else {
//                $('#org_form_hiddenType').val("");
//            }
//        }
//    });
//
//    $('#type-2').change(function () {
//        $atpBox = $('input:checkbox[id=type-2]').is(":checked");
//        $atcBox = $('input:checkbox[id=type-1]').is(":checked");
//        // atp Box is chicked 
//        if ($atpBox) {
//            addRequired('atp');
//            //show atp fields
//            showSet('atp');
//            if ($atcBox) {
//                // if both chicked 
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("3");
//            } else {
//                // if only atp chicked 
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("2");
//            }
//        } else {
//            removeRequired('atp');
//            // hide atp fields
//            hideSet('atp');
//            if ($atcBox) {
//                //if atc only
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("1");
//            } else {
//                // if both unchecked
//                $('#org_form_hiddenType').val("");
//            }
//        }
//    });
//
//    function hideSet($set) {
//        switch ($set) {
//            case 'atc':
//                //hiding atc fields
//                $('.atcSet').hide()
//                break;
//
//            case 'atp':
//                // hiding atp fields 
//                $('.atpSet').hide()
//                break;
//        }
//    }
//
//    function showSet($set) {
//        switch ($set) {
//            case 'atc':
//                //showing atc fields
//                $('.atcSet').show();
//                break;
//
//            case 'atp':
//                //showing atp fields 
//                $('.atpSet').show();
//                break;
//        }
//    }
//
//    function removeRequired($set) {
//        switch ($set) {
//            case 'atc':
//                //showing atc fields
//                $('.atcSet').removeAttr('required');
//                break;
//
//            case 'atp':
//                //showing atp fields 
//                $('.atpSet').removeAttr('required');
//                break;
//        }
//    }
//    function addRequired($set) {
//        switch ($set) {
//            case 'atc':
//                //showing atc fields
//                $('.atcSet').prop('required', true);
//                break;
//
//            case 'atp':
//                //showing atp fields 
//                $('.atpSet').prop('required', true);
//                break;
//        }
//    }
//
//    function prepareCheckBoxes() {
//        $type = getParameterByName('type');
//        switch ($type) {
//            case 'atc':
//                $('#type-1').prop('checked', true);
//                showSet('atc');
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("1");
//                break;
//
//            case 'atp':
//                $('#type-2').prop('checked', true);
//                showSet('atp');
//                $('#org_form_hiddenType').val("");
//                $('#org_form_hiddenType').val("2");
//                break;
//
//            default:
//                // consider atp as default 
//                $('#type-2').prop('checked', true);
//                showSet('atp');
//                break;
//        }
//    }
//
//    function getParameterByName(name) {
//        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
//        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
//                results = regex.exec(location.search);
//        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
//    }
//});
