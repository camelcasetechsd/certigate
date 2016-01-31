/**
 * Add more resource above add more button
 * 
 * @param {string} addMoreSelector
 * @param {string} nameInputSelector
 * @param {string} fileInputSelector
 * @param {string} nameValue
 * @param {string} nameClass
 * @param {string} nameErrors
 * @param {string} fileClass
 * @param {string} fileErrors
 * @returns {Boolean} false in case any field does not exist
 */
function addMoreResource(addMoreSelector, nameInputSelector, fileInputSelector, nameValue, nameClass, nameErrors, fileClass, fileErrors){
    if(! $(addMoreSelector).length || ! $(nameInputSelector).length || ! $(fileInputSelector).length){
        return false;
    }
    // determine new fields iteration value
    var fileInputsCount = $(".addedResources").length;
    var newElementIdExtension = "Added_" + fileInputsCount;
    var newElementNameExtension = "Added[" + fileInputsCount + "]";
    
    // This is a way to "htmlDecode" your string...  
    nameErrors = $("<div />").html(nameErrors).text();
    fileErrors = $("<div />").html(fileErrors).text();
    
    // prepare new name field
    var newNameInputId = $(nameInputSelector).attr("id") + newElementIdExtension;
    var newNameInputName = $(nameInputSelector).attr("name") + newElementNameExtension;
    var oldNameInputClass = $(nameInputSelector).attr("class");
    if(typeof oldNameInputClass !== "undefined"){
        oldNameInputClass.replace('input-error', '')
    }else{
        oldNameInputClass = '';
    }
    var newNameInputClass = oldNameInputClass + " " + nameClass;
    var newNameInput = $(nameInputSelector).clone().attr('class', newNameInputClass).attr("id", newNameInputId).attr("name", newNameInputName).attr('value', nameValue);
    if(nameValue === ""){
        newNameInput.val("");
    }
    var newNameLabel = $(nameInputSelector).prev("label").clone();
    var newNameField = $("<dd></dd>").append(newNameLabel).append(newNameInput).append(nameErrors);
    // prepare new file field
    var newFileInputId = $(fileInputSelector).attr("id") + newElementIdExtension;
    var newFileInputName = $(fileInputSelector).attr("name") + newElementNameExtension;
    var oldFileInputClass = $(fileInputSelector).attr("class");
    if(typeof oldFileInputClass !== "undefined"){
        oldFileInputClass.replace('input-error', '')
    }else{
        oldFileInputClass = '';
    }
    var newFileInputClass = oldFileInputClass + " addedResources " + fileClass;
    var newFileInput = $(fileInputSelector).clone().attr("class", newFileInputClass).attr("id", newFileInputId).attr("name", newFileInputName);
    var newFileLabel = $(fileInputSelector).prev("label").clone();
    var newFileField = $("<dd></dd>").append(newFileLabel).append(newFileInput).append(fileErrors);
    // prepare new remove button
    var newRemoveButtonId = "removeButton" + newElementIdExtension;
    var newRemoveButtonName = "removeButton" + newElementNameExtension;
    var newRemoveButtonSpacer = $(addMoreSelector).prev("dt").clone();
    var newRemoveButton = $(addMoreSelector).clone().attr("onclick", "removeResource('#" + newRemoveButtonId + "','#" + newNameInputId + "','#" + newFileInputId + "')").attr("id", newRemoveButtonId).attr("name", newRemoveButtonName).val("Remove");
    
    
    
    // prepare full new resource
    var newResource = $("<div><br/><strong>Added resource no. " + (fileInputsCount + 2) + "</strong></div>").append(newNameField).append(newFileField).append(newRemoveButtonSpacer).append(newRemoveButton);
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
    $(nameInputSelector).parent("dd").parent("div").remove();
}


