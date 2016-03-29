/**
 * set course event students number with full available capacity
 * 
 * @param {string} studentsNoSelector
 * @param {string} capacitySelector
 * @returns {undefined}
 */
function setFullCapacity(studentsNoSelector, capacitySelector) {
    $(studentsNoSelector).val($(capacitySelector).val());
}
