//$('.date').datepicker({
//    'format': 'dd/mm/yyyy',
//    'autoclose': true,
//    'orientation': 'bottom',
//    changeMonth: true,
//    changeYear: true
//});
$('.date').calendarsPicker({calendar: $.calendars.instance('islamic', 'ar')});