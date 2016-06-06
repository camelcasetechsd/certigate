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
            getOtherDateInput('next', this, '.gregorianDate-ar').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getOtherDateInput('next', this, '.gregorianDate-ar').val("");
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
            getOtherDateInput('prev', this, '.hijriDate-ar').val(ummAlQuraDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getOtherDateInput('prev', this, '.hijriDate-ar').val("");

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
            getOtherDateInput('next', this, '.gregorianDate').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getOtherDateInput('next', this, '.gregorianDate').val("");
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
            getOtherDateInput('prev', this, '.hijriDate').val(ummAlQuraDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getOtherDateInput('prev', this, '.hijriDate').val("");
        }

    }
}));

/**
 * Get other date input from current date
 * @param {string} relativePostion
 * @param {object} currentDate
 * @param {string} otherDateSelector
 * @returns {getOtherDateInput.otherDateInput}
 */
function getOtherDateInput(relativePostion, currentDate, otherDateSelector){
    var otherDateInput = $(currentDate).parent()[relativePostion]("div").find(otherDateSelector);
    if(otherDateInput.length === 0){
        otherDateInput = $(currentDate)[relativePostion]()[relativePostion](otherDateSelector);
    }
    return otherDateInput;
}