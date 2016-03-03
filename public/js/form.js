/**
 * Submit form via AJAX request 
 * 
 * @param {string} formSelector
 * @param {string} responseSelector
 * @returns {undefined}
 */
function submitFormAjax(formSelector, responseSelector) {
    var form = $(formSelector);
    if (form.length) {

        form.submit(function (event) {
            $.ajax({
                type: "POST",
                url: form.attr("action"),
                data: form.serialize(), // serializes the form's elements.
                success: function (data)
                {
                    var parsedData = $.parseJSON(data)
                    $(responseSelector).html(parsedData.content); // show response from the php script.
                }
            });

            event.preventDefault(); // avoid to execute the actual submit of the form.
        });
    }
}

