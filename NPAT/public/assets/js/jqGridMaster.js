//helper for make form data to as json object
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$(document).ready(function () {
    //Attach Bootstrap tooltips to all toolbar buttons
    $('.tutorial-tooltip').tooltip();

    //Adding form validation usign the jQuery MG Validation Plugin.
    $('#' + FormName).jqMgVal('addFormFieldsValidations', {'helpMessageClass': 'col-sm-10'});

    //Binds onClick event to the "New" button.
    $('#btn-new').click(function () {
        //Disables all buttons within the toolbar.
        //The "disabledButtonGrofp" is a custom helper function, its definition
        //can be foound in the public/assets/tutorial/js/helpers.js script.
        $(".hide-on-edit").removeClass('hoe');
        $(".no-edit").removeAttr("readonly");
        $('#btn-toolbar').disabledButtonGroup();
        //Enables the third button group (save and close).
        //The "enabledButtonGroup" is a custom helper function, its definition
        //can be foound in the public/assets/tutorial/js/helpers.js script.
        $('#btn-group-3').enableButtonGroup();
        //Shows the form title.
        $('#form-new-title').removeClass('hidden');
        //Manually hide the tooltips (fix for firefox).
        $('.tooltip').tooltip('hide');
        //This is a bootstrap javascript effect to hide the grid.
        $('#grid-section').collapse('hide');
    });

    //Binds onClick event to the "Refresh" button.
    $('#btn-refresh').click(function () {
        //When toolbar is enabled, this method should be use to clean the toolbar and refresh the grid.
        $('#' + GridName)[0].clearToolbar();
        //Disables all buttons within the toolbar
        $('#btn-toolbar').disabledButtonGroup();
        //Enables the first button group (new, refresh and export)
        $('#btn-group-1').enableButtonGroup();
    });

    //Binds onClick event to the "xls" button.
    $('#export-xls').click(function () {
        //Triggers the grid XLS export functionality.
        $('#BookGridXlsButton').click();
    });

    //Binds onClick event to the "csv" button.
    $('#export-csv').click(function () {
        //Triggers the grid CSV export functionality.
        $('#BookGridCsvButton').click();
    });

    //Bind onClick event to the "Edit" button.
    $('#btn-edit').click(function () {
        //Gets the selected row id.
        $(".valid").find(".alert.alert-danger").remove();
        //$(".hide-on-edit").addClass('hoe');
        $('.no-edit').attr("readonly", true);

        rowid = $('#' + GridName).jqGrid('getGridParam', 'selrow');

        //Gets an object with the selected row data.
        rowdata = $('#' + GridName).getRowData(rowid);
        //Fills out the form with the selected row data (the id of the
        //object must match the id of the form elements).
        //This is a custom helper function, its definition
        //can be foound in the public/assets/tutorial/js/helpers.js script.        
        jQuery.ajax({
                type: "POST",
                data: 'practices=' + rowdata.practices_id,
                url: '/resource-practices-id',
                async: false
            })
            .success(function (data) {
                $("#reporting-manager-id").empty().append('<option value="">Select Reporting Manager</option>');
                $("#reporting-manager-id").append(function () {
                    return $.map(data, function (el, i) {
                        return '<option value=' + el.id + '>' + el.name + '</option>';
                    });
                });
            }).error(function (data) {
            alert("Data was not found!!");
        });
        populateFormFields(rowdata, '');
        //Disables all buttons within the toolbar.
        $('#btn-toolbar').disabledButtonGroup();
        //Enables the third button group (save and close).
        $('#btn-group-3').enableButtonGroup();
        //Shows the form title.
        $('#form-edit-title').removeClass('hidden');
        //Manually hide the tooltips (fix for firefox).
        $('.tooltip').tooltip('hide');
        //This is a bootstrap javascript effect to hide the grid.
        $('#grid-section').collapse('hide');
        var user_edit_url = $("#edit_base_url").val();
        $("#edit_url").attr("href", '/user-details/'+rowdata.emp_id);
    });

    //Bind onClick event to the "Delete" button.
    $('#btn-delete').click(function () {
        //Gets the selected row id
        rowid = $('#' + GridName).jqGrid('getGridParam', 'selrow');
        //Gets an object with the selected row data
        rowdata = $('#' + GridName).getRowData(rowid);
        //var result = confirm("Want to delete?");
        //if (result) {
        //var agree=confirm("Are you sure you want to delete this file?");
        var agree = $.confirm({
            title: 'Hello',
            message: 'Are you sure you want to delete this data ?',
            template: 'info',
            onOk: function () {
                $.ajax(
                    {
                        type: 'DELETE',
                        data: JSON.stringify({'id': rowdata['id']}),
                        dataType: 'json',
                        url: $('#' + FormName).attr('action') + '/' + rowdata['id'],
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#app-loader').addClass('hidden');
                            $('#main-panel-fieldset').removeAttr('disabled');
                            alert('Something went wrong, please try again later.');
                        },
                        beforeSend: function () {
                            $('#app-loader').removeClass('hidden');
                            $('#main-panel-fieldset').attr('disabled', 'disabled');
                        },
                        success: function (json) {
                            if (json.success) {
                                //Shows a message after an element.
                                //This is a custom helper function, its definition
                                //can be foound in the public/assets/tutorial/js/helpers.js script.
                                $('#btn-toolbar').showAlertAfterElement('alert-success alert-custom', json.message, 5000);
                            }
                            else {
                                $('#btn-toolbar').showAlertAfterElement('alert-danger alert-custom', json.message, 5000);
                            }

                            //Triggers the "Refresh" button funcionality.
                            $('#btn-refresh').click();
                            $('#app-loader').addClass('hidden');
                            $('#main-panel-fieldset').removeAttr('disabled');
                        }
                    });
            },
            templateCancel: 'Cancel'
        });
    });

    //Bind onClick event to the "Edit" button.
    $('#btn-deactive, #btn-active').click(function () {

        rowid = $('#' + GridName).jqGrid('getGridParam', 'selrow');
        //Gets an object with the selected row data.
        rowdata = $('#' + GridName).getRowData(rowid);
        updateResourceStatus(rowdata);
    });

    function updateResourceStatus(rowdata){
        jQuery.ajax({
            type: "POST",
            data: 'rowId=' + rowdata.id+'&action=' + rowdata.status,
            url: '/update-resource-status',
        })
            .success(function (data) {
                $.notify(data.msg, "success");
                $('#btn-refresh').click();

            }).error(function (data) {
            $.notify(d.msg, "error");
            $('#btn-refresh').click();
        });
    }

    //Bind onClick event to the "Save" button.
    $('#btn-save').click(function () {
        $(".valid").find(".alert.alert-danger").remove();

        var url = $('#' + FormName).attr('action');
        var type = 'POST';
        var formData = $('#' + FormName).serializeObject();


        console.log(formData);

        //Check is the form is valid usign the jQuery MG Validation Plugin.
        if (!$('#' + FormName).jqMgVal('isFormValid')) {

            return;
        }

        if ($('#id').isEmpty()) {
            type = 'POST';
        }
        else {
            type = 'PUT';
            url += '/' + formData.id;
        }

        //Send an Ajax request to the server.
        $.ajax(
            {
                type: type,
                //Creates an object from form fields.
                //The "formToObject" is a custom helper function, its definition
                //can be foound in the public/assets/tutorial/js/helpers.js script.
                data: formData,
                dataType: 'json',
                url: url,
                error: function (jqXhr) {
                    if (jqXhr.status === 401) //redirect if not authenticated user.
                        $(location).prop('pathname', 'auth/login');
                    if (jqXhr.status === 422) {
                        //process validation errors here.
                        var errors = jqXhr.responseJSON; //this will get the errors response data.
                        //show them somewhere in the markup
                        //e.g
                        errorsHtml = '<div class="alert alert-danger"><ul>';

                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
                        });
                        errorsHtml += '</ul></di>';

                        $('.valid').html(errorsHtml); //appending to a <div id="form-errors"></div> inside form
                    } else {
                        /// do some thing else
                    }
                },
                beforeSend: function () {
                    $('#app-loader').removeClass('hidden');
                    $('#main-panel-fieldset').attr('disabled', 'disabled');
                },
                success: function (json) {
                    //$(".valid").find(".alert.alert-danger").remove();
                    if (json.success) {
                        $('#btn-toolbar').showAlertAfterElement('alert-success alert-custom', json.message, 5000);
                    }
                    else {
                        $('#btn-toolbar').showAlertAfterElement('alert-danger alert-custom', json.message, 5000);
                    }

                    //Triggers the "Close" button funcionality.
                    $('#btn-close').click();
                    $('#app-loader').addClass('hidden');
                    $('#main-panel-fieldset').removeAttr('disabled');
                }
            });
    });

    //Bind onClick event to the "Close" button.
    $('#btn-close').click(function () {
        $(".valid").find(".alert.alert-danger").remove();
        //Disables all buttons within the toolbar.
        $('#btn-toolbar').disabledButtonGroup();
        //Enables the fisrt button group (new, refresh and export).
        $('#btn-group-1').enableButtonGroup();
        //Hides the form titles
        $('#form-new-title').addClass('hidden');
        $('#form-edit-title').addClass('hidden');
        //Manually hide the tooltips (fix for firefox).
        $('.tooltip').tooltip('hide');
        //Cleans the form usign the jQuery MG Validation Plugin.
        $('#' + FormName).jqMgVal('clearForm');
        //Triggers the "Refresh" button funcionality.
        $('#btn-refresh').click();
        //This is a bootstrap javascript effect to hide the grid.
        $('#form-section').collapse('hide');
    });

    //This is a bootstrap javascript event that allows you to do
    //something when the element is hidden.
    $('#grid-section').on('hidden.bs.collapse', function () {
        //Shows the form.
        //This is a bootstrap javascript effect
        $('#form-section').collapse('show');
        //Focus on the name form field
        $('#name').focus();
    });

    $('#form-section').on('hidden.bs.collapse', function () {
        //Shows the grid.
        $('#grid-section').collapse('show');
    });

    //Binds focusOut event to the "ASIN" field.
    $('#asin').focusout(function () {
        //Focus on the "Save" button.
        $('#btn-save').focus();
    });
});