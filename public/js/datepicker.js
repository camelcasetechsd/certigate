//$('.date').datepicker({
//    'format': 'dd/mm/yyyy',
//    'autoclose': true,
//    'orientation': 'bottom',
//    changeMonth: true,
//    changeYear: true
//});

$.calendars.calendars.islamic.prototype.regional['ar'] = {
    name: 'Islamic',
    epochs: ['BAM', 'AM'],
    monthNames: ['محرّم', 'صفر', 'ربيع الأول', 'ربيع الآخر أو ربيع الثاني', 'جمادى الاول', 'جمادى الآخر أو جمادى الثاني',
        'رجب', 'شعبان', 'رمضان', 'شوّال', 'ذو القعدة', 'ذو الحجة'],
    monthNamesShort: ['محرّم', 'صفر', 'ربيع الأول', 'ربيع الآخر أو ربيع الثاني', 'جمادى الاول', 'جمادى الآخر أو جمادى الثاني',
        'رجب', 'شعبان', 'رمضان', 'شوّال', 'ذو القعدة', 'ذو الحجة'],
    dayNames: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
    dayNamesShort: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
    dayNamesMin: ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'],
    digits: $.calendars.substituteDigits(['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩']),
    dateFormat: 'yyyy/mm/dd',
    firstDay: 6,
    isRTL: true
};
// rtl date pickers classes
$('.hijriDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('islamic', 'ar'),
    onClose: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian').fromJD(julianDate);
            console.log('hijri is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in gregorian ' + gregorianDate);
            $(this).parent().next('div').find('.gregorianDate-ar').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().next('div').find('.gregorianDate-ar').val("");
        }
    }
}));

$('.gregorianDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian'),
    onClose: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var islamicDate = $.calendars.instance('islamic').fromJD(julianDate);
            console.log('gregorian is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in islamic ' + islamicDate);
            $(this).parent().prev('div').find('.hijriDate-ar').val(islamicDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().prev('div').find('.hijriDate-ar').val("");

        }

    }
}));

// ltr date pickers classes
$('.hijriDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('islamic'),
    onClose: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian')
                    .fromJD(julianDate);
            console.log('hijri is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in gregorian ' + gregorianDate);
            $(this).parent().next('div').find('.gregorianDate').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().next('div').find('.gregorianDate').val("");
        }
    }
}));

$('.gregorianDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian'),
    onClose: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var islamicDate = $.calendars.instance('islamic').fromJD(julianDate);
            console.log('gregorian is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in islamic ' + islamicDate);
            $(this).parent().prev('div').find('.hijriDate').val(islamicDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).parent().prev('div').find('.hijriDate').val("");

        }

    }
}));
