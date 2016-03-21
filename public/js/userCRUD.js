$(document).ready(function () {
    $("#user_form_securityQuestion").after("<ul><li>Security Question should be <strong>Memorable</strong>, You should be able to remember the answer.</li><li>Security Question should be <strong>Consistent</strong>, Answer should not change with time.</li><li>Security Question should be <strong>Safe</strong>, Answer should be hard to guess or research.</li></ul>");
});

/**
 * Generate checkbox sentence with proper modal to display statement
 * Statement agreement action affect checkbox value
 * 
 * @param {string} checkboxId
 * @param {string} title statement title
 * @param {string} sentence checkbox sentence
 * @param {string} content whole statement content
 * @param {string} role statement related user role
 * @returns {undefined}
 */
function generateStatementCheckboxContent(checkboxId, title, sentence, content, role) {
    var divId = checkboxId + "Div";
    var checkboxSelector = "#" + checkboxId;
    var divSelector = "#" + divId;
    $(checkboxSelector).attr('data-role', role).after("<div id='" + divId + "'></div>");
    $(checkboxSelector).appendTo(divSelector);
    $(divSelector).append("<p>" + sentence + "</p>");
    $(divSelector + ' a').click(function () {
        bootbox.dialog({
            message: content,
            title: title,
            buttons: {
                success: {
                    label: "Agree",
                    className: "btn-success",
                    callback: function () {
                        $(checkboxSelector).attr('checked', true);
                    }
                },
                cancel: {
                    label: "Disagree",
                    className: "btn-primary",
                    callback: function () {
                        $(checkboxSelector).attr('checked', false);
                    }
                }
            }
        });
    });
    if (role !== '') {
        var roleLabel = $("label:contains('" + role + "')");
        roleLabel.after($(divSelector));
    }
}

/**
 * Display statement checkbox according to selected roles
 * 
 * @param {string} roleSelector
 * @returns {undefined}
 */
function displayStatementCheckbox(roleSelector) {
    var value, checkboxSelector;
    $(roleSelector + ':checked').each(function (i, selected) {
        value = $(selected).parent('label').text();
        checkboxSelector = $("[data-role='" + value + "']");
        if (checkboxSelector.length) {
            checkboxSelector.parent("div").show();
        }
    });
    $(roleSelector).change(function () {
        if ($(this).is(":checked")) {
            value = $(this).parent('label').text();
            checkboxSelector = $("[data-role='" + value + "']");
            if (checkboxSelector.length) {
                checkboxSelector.parent("div").show();
            }
        } else {
            value = $(this).parent('label').text();
            checkboxSelector = $("[data-role='" + value + "']");
            if (checkboxSelector.length) {
                checkboxSelector.parent("div").hide();
            }
        }
    });
}


