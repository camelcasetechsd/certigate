// rtl date pickers classes
$('.new-hijriDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('ummalqura', 'ar'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian').fromJD(julianDate);
            getNewOtherDateInput('next', this, '.new-gregorianDate-ar').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getNewOtherDateInput('next', this, '.new-gregorianDate-ar').val("");
        }
    }
}));

$('.new-gregorianDate-ar').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian', 'ar'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var ummAlQuraDate = $.calendars.instance('ummalqura').fromJD(julianDate);
            getNewOtherDateInput('prev', this, '.new-hijriDate-ar').val(ummAlQuraDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getNewOtherDateInput('prev', this, '.new-hijriDate-ar').val("");

        }

    }
}));

// ltr date pickers classes
$('.new-hijriDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('ummalqura'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var gregorianDate = $.calendars.instance('gregorian')
                    .fromJD(julianDate);
            getNewOtherDateInput('next', this, '.new-gregorianDate').val(gregorianDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getNewOtherDateInput('next', this, '.new-gregorianDate').val("");
        }
    }
}));

$('.new-gregorianDate').calendarsPicker($.extend({
    calendar: $.calendars.instance('gregorian'),
    onSelect: function (dateText, datePickerInstance) {
        $(this).val(dateText[0].formatDate('dd/mm/yyyy'));
        // getting julian date out of hijri date
        if (dateText.length > 0) {
            var julianDate = dateText[0].toJD();
            // creating gregorian date out of julain date
            var ummAlQuraDate = $.calendars.instance('ummalqura').fromJD(julianDate);
            getNewOtherDateInput('prev', this, '.new-hijriDate').val(ummAlQuraDate.formatDate('dd/mm/yyyy').toLocaleString().replace(/-/g, '/'));
        } else {
            getNewOtherDateInput('prev', this, '.new-hijriDate').val("");
        }

    }
}));

/**
 * Get other date input from current date
 * @param {string} relativePostion
 * @param {object} currentDate
 * @param {string} otherDateSelector
 * @returns {getNewOtherDateInput.otherDateInput}
 */
function getNewOtherDateInput(relativePostion, currentDate, otherDateSelector){
    var otherDateInput = $(currentDate).parent().parent()[relativePostion]("div").find(otherDateSelector);
    if(otherDateInput.length === 0){
        otherDateInput = $(currentDate)[relativePostion]()[relativePostion](otherDateSelector);
    }
    return otherDateInput;
}