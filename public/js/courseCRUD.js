/**
 * Update outlines
 * 
 * @param {String} addMoreSelector
 * @param {bool} isAdminUser
 */
function updateOutlines(addMoreSelector, isAdminUser) {
    var currentCount, newLabel, newRemoveButton;
    // update displayed outlines fieldsets
    if ($('#course_form > fieldset.outlinesFieldset > fieldset').length) {
        $('#course_form > fieldset.outlinesFieldset > fieldset').each(function (index) {
            if (index !== 0) {
                currentCount = index;
                newLabel = getOutlineLabel(currentCount);
                $(this).before(newLabel);

                if (isAdminUser === true) {
                    newRemoveButton = getOutlineRemoveButton(currentCount, addMoreSelector);
                    $(this).after(newRemoveButton);
                }
            }
        });
    }
}

/**
 * Add more outline
 * 
 * @param {String} addMoreSelector
 */
function addMoreOutline(addMoreSelector) {
    var currentCount = $('form > fieldset.outlinesFieldset > fieldset').length;
    var newLabel = getOutlineLabel(currentCount);
    var newRemoveButton = getOutlineRemoveButton(currentCount, addMoreSelector);

    var template = $('form > fieldset.outlinesFieldset > span').data('template');
    template = template.replace(/__outlineNumber__/g, currentCount);

    var outlineFieldset = newLabel + template + newRemoveButton;
    $('form > fieldset.outlinesFieldset').append(outlineFieldset);
}

/**
 * Remove outline
 * 
 * @param {String} removeButtonSelector
 */
function removeOutline(removeButtonSelector) {
    $(removeButtonSelector).prev("fieldset").prev("label").remove();
    $(removeButtonSelector).prev("fieldset").remove();
    $(removeButtonSelector).remove();
}

/**
 * Get outline label
 * 
 * @param {String} currentCount
 * @returns {String} outline label
 */
function getOutlineLabel(currentCount) {
    return "<label>Added outline no. " + (currentCount + 1) + "</label>";
}

/**
 * Get outline remove button
 * 
 * @param {String} currentCount
 * @param {String} addMoreSelector
 * @returns {String} outline remove button html
 */
function getOutlineRemoveButton(currentCount, addMoreSelector) {
    // prepare new remove button
    var newRemoveButtonId = "removeOutline" + currentCount;
    return $(addMoreSelector).clone().attr("onclick", "removeOutline('#" + newRemoveButtonId + "')").attr("id", newRemoveButtonId).val("Remove").wrap("<div />").parent().html();
}