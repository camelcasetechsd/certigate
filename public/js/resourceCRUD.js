/**
 * Add more resource above add more button
 * 
 * @param {string} addMoreSelector
 * @param {string} nameInputSelector
 * @param {string} fileInputSelector
 * @returns {Boolean} false in case any field does not exist
 */
function addMoreResource(addMoreSelector, nameInputSelector, fileInputSelector){
    if(! $(addMoreSelector).length || ! $(nameInputSelector).length || ! $(fileInputSelector).length){
        return false;
    }
    // determine new fields iteration value
    var fileInputsCount = $(".addedResources").length;
    var newElementIdExtension = "Added_" + fileInputsCount;
    var newElementNameExtension = "Added[" + fileInputsCount + "]";
    // prepare new name field
    var newNameInputId = $(nameInputSelector).attr("id") + newElementIdExtension;
    var newNameInputName = $(nameInputSelector).attr("name") + newElementNameExtension;
    var newNameInput = $(nameInputSelector).clone().val('').attr("id", newNameInputId).attr("name", newNameInputName);
    var newNameLabel = $(nameInputSelector).prev("label").clone();
    var newNameField = $("<dd></dd>").append(newNameLabel).append(newNameInput);
    // prepare new file field
    var newFileInputId = $(fileInputSelector).attr("id") + newElementIdExtension;
    var newFileInputName = $(fileInputSelector).attr("name") + newElementNameExtension;
    var newFileInput = $(fileInputSelector).clone().attr("class", "addedResources").attr("id", newFileInputId).attr("name", newFileInputName);
    var newFileLabel = $(fileInputSelector).prev("label").clone();
    var newFileField = $("<dd></dd>").append(newFileLabel).append(newFileInput);
    // prepare new remove button
    var newRemoveButtonId = "removeButton" + newElementIdExtension;
    var newRemoveButtonName = "removeButton" + newElementNameExtension;
    var newRemoveButtonSpacer = $(addMoreSelector).prev("dt").clone();
    var newRemoveButton = $(addMoreSelector).clone().attr("onclick", "removeResource('#" + newRemoveButtonId + "','#" + newNameInputId + "','#" + newFileInputId + "')").attr("id", newRemoveButtonId).attr("name", newRemoveButtonName).val("Remove");
    // prepare full new resource
    var newResource = newNameField.append(newFileField).append(newRemoveButtonSpacer).append(newRemoveButton);
    // add new resource before add button
    $(addMoreSelector).prev("dt").before(newResource);
}

/**
 * Remove resource
 * 
 * @param {string} removeButtonSelector
 * @param {string} nameInputSelector
 * @param {string} fileInputSelector
 * @returns {Boolean} false in case any field does not exist
 */
function removeResource(removeButtonSelector, nameInputSelector, fileInputSelector){
    if(! $(removeButtonSelector).length || ! $(nameInputSelector).length || ! $(fileInputSelector).length){
        return false;
    }
    $(nameInputSelector).parent("dd").remove();
    $(fileInputSelector).parent("dd").remove();
    $(removeButtonSelector).prev("dt").remove();
    $(removeButtonSelector).remove();
}


