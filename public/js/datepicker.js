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
    dayNames: ['يوم الأحد', 'يوم الإثنين', 'يوم الثلاثاء', 'يوم الأربعاء', 'يوم الخميس', 'يوم الجمعة', 'يوم السبت'],
    dayNamesShort: ['يوم الأحد', 'يوم الإثنين', 'يوم الثلاثاء', 'يوم الأربعاء', 'يوم الخميس', 'يوم الجمعة', 'يوم السبت'],
    dayNamesMin: ['يوم الأحد', 'يوم الإثنين', 'يوم الثلاثاء', 'يوم الأربعاء', 'يوم الخميس', 'يوم الجمعة', 'يوم السبت'],
    digits: $.calendars.substituteDigits(['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩']),
    dateFormat: 'yyyy/mm/dd',
    firstDay: 6,
    isRTL: true
};

$('.hijriDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('islamic', 'ar'),
    onClose: function (dateText, datePickerInstance) {
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian').fromJD(julianDate);
            console.log('hijri is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in gregorian ' + gregorianDate);
            $(this).next('#gregorianDate').val(gregorianDate.toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).next('#gregorianDate').val("");
        }
    }
}));

$('.hijriDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('islamic'),
    onClose: function (dateText, datePickerInstance) {
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian').fromJD(julianDate);
            console.log('hijri is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in gregorian ' + gregorianDate);
            $(this).next('.gregorianDate').val(gregorianDate.toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).next('.gregorianDate').val("");

        }

    }
}));

$('.gregorianDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian'),
    onClose: function (dateText, datePickerInstance) {
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var islamicDate = $.calendars.instance('islamic').fromJD(julianDate);
            console.log('gregorian is ' + dateText.toLocaleString() + ' Leads to julian: ' + julianDate + ' which is in islamic ' + islamicDate);
            $(this).prev('.hijriDate').val(islamicDate.toLocaleString().replace(/-/g, '/'));
        } else {
            $(this).prev('.hijriDate').val("");

        }

    }
}));