// rtl date pickers classes
$('.hijriDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('ummalqura', 'ar'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian').fromJD(julianDate);
            $(this).parent().next('div').find('.gregorianDate-ar').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().next('div').find('.gregorianDate-ar').val("");
        }
    }
}));

$('.gregorianDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian', 'ar'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var ummAlQuraDate = $.calendars.instance('ummalqura').fromJD(julianDate);
            $(this).parent().prev('div').find('.hijriDate-ar').val(ummAlQuraDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().prev('div').find('.hijriDate-ar').val("");

        }

    }
}));

// ltr date pickers classes
$('.hijriDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('ummalqura'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian')
                    .fromJD(julianDate);
            $(this).parent().next('div').find('.gregorianDate').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().next('div').find('.gregorianDate').val("");
        }
    }
}));

$('.gregorianDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var ummAlQuraDate = $.calendars.instance('ummalqura').fromJD(julianDate);
            $(this).parent().prev('div').find('.hijriDate').val(ummAlQuraDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().prev('div').find('.hijriDate').val("");

        }

    }
}));
