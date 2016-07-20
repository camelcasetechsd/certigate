/**
 * Add more listener
 * Hide add more button if one 
 * 
 * @param {string} addMoreSelector
 * @param {string} typeSelector
 * @param {array} oneFileTypes
 * @returns {undefined}
 */
function addMoreListener(addMoreSelector, typeSelector, oneFileTypes) {
    oneFileTypes = $.parseJSON(oneFileTypes);
    $(typeSelector).change(function () {
        var typeValue = $(this).val();
        var slectedText = $(this).find('option[value=' + typeValue + ']').text();
        if ($.inArray(slectedText, oneFileTypes) > -1) {
            // remove all previously added sub-forms
            $('input[value="Remove"]').click();
            $(addMoreSelector).hide();
        } else {
            $(addMoreSelector).show();
        }
    });
}

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
function addMoreResource(addMoreSelector, nameInputSelector, nameInputArSelector, fileInputSelector, nameValue, nameClass, nameErrors, nameArValue, nameArClass, nameArErrors, fileClass, fileErrors) {

    if (!$(addMoreSelector).length || !$(nameInputSelector).length || !$(fileInputSelector).length || !$(nameInputArSelector).length) {
        return false;
    }

    // determine new fields iteration value
    var fileInputsCount = $(".addedResources").length;
    var newElementIdExtension = "Added_" + fileInputsCount;
    var newElementIdArExtension = "Added_id_" + fileInputsCount;
    var newElementNameExtension = "Added[" + fileInputsCount + "]";
    var newElementNameArExtension = "AddedAr[" + fileInputsCount + "]";

    // This is a way to "htmlDecode" your string...  
    nameErrors = $("<div />").html(nameErrors).text();
    nameArErrors = $("<div />").html(nameArErrors).text();
    fileErrors = $("<div />").html(fileErrors).text();

    // style preparation
    var divElementContainer = "<div class='col-md-12'></div>";
    var divFormGroup = "<div class='form-group'></div>";
    var divInputContainer = "<div class='col-sm-9'></div>";
    
    // prepare new  name field
    var newNameInputId = $(nameInputSelector).attr("id") + newElementIdExtension;
    var newNameInputName = $(nameInputSelector).attr("name") + newElementNameExtension;
    var oldNameInputClass = $(nameInputSelector).attr("class");
    if (typeof oldNameInputClass !== "undefined") {
        oldNameInputClass.replace('input-error', '')
    } else {
        oldNameInputClass = '';
    }
    var newNameInputClass = oldNameInputClass + " " + nameClass;
    var newNameInput = $(nameInputSelector).clone().attr('class', newNameInputClass).attr("id", newNameInputId).attr("name", newNameInputName).attr('value', nameValue);
    if (nameValue === "") {
        newNameInput.val("");
    }
    var newNameLabel = $(nameInputSelector).parents('.form-group').children("label").clone();
    var newNameField = $(divElementContainer).append($(divFormGroup).append(newNameLabel).append($(divInputContainer).append(newNameInput).append(nameErrors)));
    
    // prepare new arabic name field
    var newNameArInputId = $(nameInputArSelector).attr("id") + newElementIdArExtension;
    var newNameArInputName = $(nameInputArSelector).attr("name") + newElementNameArExtension;
    var oldNameArInputClass = $(nameInputArSelector).attr("class");
    if (typeof oldNameArInputClass !== "undefined") {
        oldNameArInputClass.replace('input-error', '')
    } else {
        oldNameArInputClass = '';
    }
    var newNameArInputClass = oldNameArInputClass + " " + nameArClass;
    var newNameArInput = $(nameInputArSelector).clone().attr('class', newNameArInputClass).attr("id", newNameArInputId).attr("name", newNameArInputName).attr('value', nameValue);
    if (nameArValue === "") {
        newNameArInput.val("");
    }

    var newNameArLabel = $(nameInputArSelector).parents('.form-group').children("label").clone();
    var newNameArField = $(divElementContainer).append($(divFormGroup).append(newNameArLabel).append($(divInputContainer).append(newNameArInput).append(nameArErrors)));


    // prepare new file field
    var newFileInputId = $(fileInputSelector).attr("id") + newElementIdExtension;
    var newFileInputName = $(fileInputSelector).attr("name") + newElementNameExtension;
    var oldFileInputClass = $(fileInputSelector).attr("class");
    if (typeof oldFileInputClass !== "undefined") {
        oldFileInputClass.replace('input-error', '')
    } else {
        oldFileInputClass = '';
    }
    var newFileInputClass = oldFileInputClass + " addedResources " + fileClass;
    var newFileInput = $(fileInputSelector).clone().attr("class", newFileInputClass).attr("id", newFileInputId).attr("name", newFileInputName);
    var newFileLabel = $(fileInputSelector).prev("label").clone();
    var newFileField = $("<div></div>").append(newFileLabel).append(newFileInput).append(fileErrors);
    var newFileField = $(divElementContainer).append($(divFormGroup).append(newFileLabel).append($(divInputContainer).append(newFileInput).append(fileErrors)));

    // prepare new remove button
    var newRemoveButtonId = "removeButton" + newElementIdExtension;
    var newRemoveButtonName = "removeButton" + newElementNameExtension;
    var newRemoveButtonSpacer = '<br/>';
    var newRemoveButton = $(addMoreSelector).clone().attr("onclick", "removeResource('#" + newRemoveButtonId + "','#" + newNameInputId + "','#" + newNameArInputId + "','#" + newFileInputId + "')").attr("id", newRemoveButtonId).attr("name", newRemoveButtonName).text("Remove");



    // prepare full new resource
    var newResource = $("<div><br/><strong>Added resource no. " + (fileInputsCount + 2) + "</strong></div>").append(newNameField).append(newNameArField).append(newFileField).append(newRemoveButtonSpacer).append(newRemoveButton);
    // add new resource before add button
    console.log($(addMoreSelector).prev("dt"));
    $(addMoreSelector).before(newResource);
}

/**
 * Remove resource
 * 
 * @param {string} removeButtonSelector
 * @param {string} nameInputSelector
 * @param {string} fileInputSelector
 * @returns {Boolean} false in case any field does not exist
 */
function removeResource(removeButtonSelector, nameInputSelector, fileInputSelector) {
    if (!$(removeButtonSelector).length || !$(nameInputSelector).length || !$(fileInputSelector).length) {
        return false;
    }
    $(nameInputSelector).parent("div").parent("div").remove();
}

/**
 * Delete resource physically
 * 
 * @param {object} deleteAnchorTag
 * @returns {undefined}
 */
function deleteResourcePhysically(deleteAnchorTag) {
    bootbox.confirm("Are you sure you want to delete the resource ?", function (result) {
        if (result) {
            var deleteLink = deleteAnchorTag.attr("data-href");
            window.location.href = deleteLink;
        }
    });
}


