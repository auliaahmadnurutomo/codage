validIcon = '<i class="fa fa-check input-suggest-text text-success"></i>';
invalidIcon = '<i class="fa fa-exclamation input-suggest-text text-danger"></i>';
function initializeAutocomplete(configs, onSelect, appendToView,csrfToken) {
    globalConfig = configs
    globalViewAppend = appendToView
    configs.forEach(function (config) {
        // Handle focus event
        $(config.inputSelector).on('focus', function () {
            if (config.inputSelector.val === '') {
                $(appendToView + " #" + config.inputSelector.substring(1) + "_select").val('');
                appendInvalidIcon(config.inputSelector.substring(1),$(config.inputSelector).val())
            }
            // console.log("Focused on: " + config.inputSelector);
        });

        $(config.inputSelector).on('blur', function () {
            console.log("value selected on blur: " + $(config.inputSelector).val());
            if ($(appendToView + " #" + config.inputSelector.substring(1) + "_select").val() === '') {
                $('#no-results-' + config.inputSelector.substring(1)).show()
                appendInvalidIcon(config.inputSelector.substring(1),$(config.inputSelector).val())
            }
            if ($(config.inputSelector).val() === '') {
                $('#no-results-' + config.inputSelector.substring(1)).hide()
            }
        });
        $(config.inputSelector).autocomplete({
            source: function (request, response) {
                getDataSource(config, appendToView, request, response)
            },
            select: function (event, ui) {
                // console.log(ui.item.value);
                $(appendToView + " #" + config.inputSelector.substring(1)).val(ui.item.label);
                $(appendToView + " #" + config.inputSelector.substring(1) + "_select").val(ui.item.value);
                if (typeof onSelect === "function") {
                    // console.log("onSelect");
                    onSelect(ui.item, config);
                }
                appendValidIcon(config.inputSelector.substring(1))
                $(this).autocomplete("close"); // Close the suggestions list on select
                return false;
            },
            // minLength: 3,
            close: function (event, ui) {
                const selectedValue = $(config.inputSelector).val();
                appendValidIcon(config.inputSelector.substring(1))
                if ($(appendToView + " #" + config.inputSelector.substring(1) + "_select").val() == '') {
                    appendInvalidIcon(config.inputSelector.substring(1),selectedValue)
                }
            },
            appendTo: appendToView
        });

        $('#group_' + config.inputSelector.substring(1)+'_add_item').on('click', function(e) {
            e.preventDefault();
            let value = $(config.inputSelector).val();
            console.log(value);
            if (value) {
                showFullLoader()
                $.ajax({
                    url: config.addUrl,
                    type: "POST",
                    dataType: "json",
                    data: {
                        name: value,
                        _token: csrfToken
                    },
                    success: function(data) {
                        console.log(data);
                        $(config.inputSelector + "_select").val(data.value);
                        $(config.inputSelector).val(data.label);
                        appendValidIcon(config.inputSelector.substring(1))
                        hideFullLoader()
                    },
                    error: function (xhr, status, error) {
                        alert('Error server communication')
                        hideFullLoader()
                        appendInvalidIcon(idInputSelector,value)
                    }
            });
        }
        });
    });

    

    

}

function getDataSource(config, appendToView, request, response) {
    idInputSelector = config.inputSelector.substring(1)
    showFullLoader()
    $.ajax({
        url: config.url,
        type: "GET",
        dataType: "json",
        data: { term: request.term },
        success: function (data) {
            if (data.length > 0) {
                if (data.length == 1 && data[0].label === request.term) {
                    console.log(request.term + '=' + data[0].label)
                    $(appendToView + " #" + idInputSelector + "_select").val(data[0].value);
                    appendValidIcon(idInputSelector);
                }
                else {
                    $(appendToView + " #" + idInputSelector + "_select").val('');
                }
                console.log("Data received:", data);
                let formattedData = data.map(item => ({
                    label: item.label,
                    value: item.value
                }));
                response(formattedData);
            }
            else {
                console.log("No results found for:", request.term);
                $(appendToView + " #" + idInputSelector + "_select").val('');
                appendInvalidIcon(idInputSelector,request.term)
            }
            hideFullLoader()
        },
        error: function (xhr, status, error) {
            alert('Cannot load data from server: ');
            console.log("AJAX Error:", status, error);
            appendInvalidIcon(idInputSelector,request.term)
            hideFullLoader()
        }
    });
}

function appendInvalidIcon(idGroup,inputValue) {
    console.log('invalid icon on ' + idGroup);
    $('#group_' + idGroup + ' .input-suggest-append').html('');
    $('#group_' + idGroup + ' .input-suggest-append').append(invalidIcon);
    // $('#no-results-'+idGroup).html('Not Found.' + btn_add);
    $('#group_' + idGroup + '_add_item .inputValue').html(inputValue);
    $('#no-results-' + idGroup).show();
}
function appendValidIcon(idGroup) {

    console.log('valid icon')
    $('#group_' + idGroup + ' .input-suggest-append').html('');
    $('#group_' + idGroup + ' .input-suggest-append').append(validIcon);
    $('#no-results-' + idGroup).hide();

}


