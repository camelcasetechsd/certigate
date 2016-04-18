/**
 * Add to client calendar
 * 
 * @param {object} mouseEvent
 * @returns {undefined}
 */
function atcOnCalendarClick(mouseEvent) {
    $.get("/course-events/add-calendar", {url: this.href})
            .done(function (data) {
                var parsedData = $.parseJSON(data);
                if ("message" in parsedData) {
                    bootbox.alert(parsedData.message);
                }
            });
    mouseEvent.preventDefault();
}