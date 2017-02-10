function alertmethod(title,message){
        if(title ==''){
            title = "Hello!";
        }
        $.alert(message, {
            title: title,
            // closeTime: $('#time').val() * 1000,
            autoClose: true,
            position: ["top", [50,100]],
            // withTime: $('#withTime').is(':checked'),
            type: "danger",
            // Set to false to display multiple messages at a time
            isOnly: true,

        });
    }   
/* Select start and end date from calendar */
function selectDate() {
    var currentTime = new Date();
    var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth() - 5, 1); //previous month
    var maxDate = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);  //current month
    var maxEndDate = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);
    
    $('#endDate').datepicker({
        dateFormat: "dd-mm-yy",
        minDate: minDate,
        maxDate: maxDate
    });
    $("#startDate").datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: minDate,
        maxDate: maxDate,
        defaultDate: minDate,
        onSelect: function (selectedDate) {
            var date1 = $('#startDate').datepicker('getDate');
            var date = new Date(Date.parse(date1));
            var tmp = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            var newDate = tmp.toDateString();
            newDate = new Date(Date.parse(newDate));
         }
    });
};

/* Load resource name and metrices */
function loadMetrices() {
    $('#resourceName').change(function () {
        var val = $(this).val();
        $.get("feedback-form/" + val, function (data) {
            $("#loadMetrics").html(data);
            //$('form').validate();
        }).fail(function (data) {
                $("#loadMetrics").html(data);
            });
    });
}

/* Function check blank fields and throws validation error */

function feedbackFormValidation() {
    $('#feedbackInsert').on('click', 'button.addFeedback-btn', function () {
        
        var fromyear = $("#fromyear").val();
        var fromdate = $("#fromdate").val();
        if(fromyear == '' || fromdate == ''){
            alertmethod('','The year and Quarter value is mandatory, please select it');
            return false;
        }
        $('form').validate({
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-horizontal').find('select').removeClass('has-error');
            }
        });
    });
}

$.validator.addMethod("ratingValidation", function (value, element) {
    var ratingval = $(element).closest('.form-horizontal').find('select').val();
    if(ratingval == '' && value.length > 0){
        $(element).closest('.form-horizontal').find('select').addClass('has-error');
        return false;
    }
    return true;
}, "Select Rating!");

$.validator.addMethod("commentTextValidation", function (value, element) {   
    isMandatorySpecified = $(element).attr('mandatory') ? true : false;
    dontValidateForMandatory = $(element).closest('.form-horizontal').find('select').val() == 3;    
    isCommentEmpty = !(value.length > 0);
    isCommentLessThanMinChar = value.length > 0 && value.length < 10;
    isCommentMoreThanMaxChar = value.length > 100;
    validationFail = (isMandatorySpecified && isCommentEmpty && !dontValidateForMandatory) || (!isCommentEmpty && isCommentLessThanMinChar) || (!isCommentEmpty && isCommentMoreThanMaxChar)

    return !validationFail;
}, "The field is required, more than 10 Char & less than 100 Char");


$(document).ready(function () {

    /*Get Report Summary Details*/

    jQuery("#reportsummary-data").submit(function (e) {
        e.preventDefault();
        reportsummary();
    });

    function reportsummary() {
        var formdata = jQuery("#reportsummary-data").serialize();

        jQuery.ajax({
            type: "POST",
            data: formdata,
            url: '/report-summary',
        }).success(function (data) {
            $('#report-summary-sheet').html(data).show();
        }).error(function (data) {
            var d = data.responseJSON;
            $('#report-summary-sheet').hide();
            $.notify(d.msg, "error");
            $('.year, #resource').val('');
            $('#resource').select2({
                placeholder: "Select People"
            });
        });
    }

    /*Get Practice Lead Report Details*/
    jQuery("#pl-report-data").submit(function (e) {
        var people = $("#people").val();
        var role = $("#role_id").val();
        if(role == 5){
            var practice = $("#practiceName").val();
        }else{
            var practice = $("#project").val();
        }
        
        var year = $("#fromyear").val();
        var quarterdate = $("#fromdate").val();
        if(people =='' && practice =='' && year =='' && quarterdate ==''){
            alertmethod("","Please select any one option");
            return false;            
        }
        if((year =='' || quarterdate =='') && (year !='' || quarterdate !='')){
            alertmethod("","Year and quarter both are mandatory, Please select it");
            return false;
        }

        e.preventDefault();
        practiceLeadReportDetails();
    });

    function practiceLeadReportDetails() {
        var reportData = jQuery("#pl-report-data").serialize();

        jQuery.ajax({
            type: "POST",
            data: reportData,
            url: '/report-dashboard',
        }).success(function (data) {
            $('#pl-report-sheet').html(data).show();
        }).error(function (data) {
            var d = data.responseJSON;
            $('#pl-report-sheet').hide();
            $.notify(d.msg, "error");
            $('.year, .quarter,.project,.people,#practiceName').val('');
            $('.project').select2({
                placeholder: "Select Project"
            });
            $('.people').select2({
                placeholder: "Select People",
            });
            $('#practiceName').select2({
                placeholder: "Select Practice",
            });
        });

    }

    /*Get Project Manager Report Details*/
    jQuery("#projectManagerReport").submit(function (e) {
        e.preventDefault();
        projectManagerReportDetails();
    });

    function projectManagerReportDetails() {
        var reportData = jQuery("#projectManagerReport").serialize();

        jQuery.ajax({
            type: "POST",
            data: reportData,
            url: '/pm-report-dashboard',
        }).success(function (data) {
            $('#pm-report-sheet').html(data).show();
        }).error(function (data) {
            var d = data.responseJSON;
            $('#pm-report-sheet').hide();
            $.notify(d.msg, "error");
            $('#startMonthDate,#endMonthDate').val('');
            $('.placeholder').select2({
                placeholder: "Select People"
            });
        });
    }

    /*Back Button Functionalities*/
    jQuery("#people,#project,#resource,#resource-project,#resourceName,#practiceName").select2();

    $("#project,#people,#fromdate,#practiceName").keyup(function () {
        var value = $(this).val();
        if (value) {
           practiceLeadReportDetails();
        }
    }).keyup();

    $("#startMonthDate,#endMonthDate,#resource").keyup(function () {
        var value = $(this).val();
        if (value) {
            projectManagerReportDetails();
        }
    }).keyup();

    /*Goto Previous Page-Back Button Functionality*/
    jQuery("#back").click(function (e) {
        e.preventDefault();
        window.history.go(-1);
    });

    /*Passing Resources Id To Get Reporting Manager*/
    jQuery("#practiceName").on('change',function(e)
    {

        $("#resourceName, #people").select2({
            placeholder: "Select People"
        }).empty().trigger("change");
      
        var practicesId= jQuery("#practiceName").val();
        jQuery.ajax({
            type: "POST",
            data: 'practicesId=' + practicesId,
            url: '/resources-practices',
        }).success(function (data) {
                
                $("#resourceName, #people").empty().append('<option value="">Select People</option>');
                $("#resourceName, #people").append(function () {
                    return $.map(data, function (el, i) {
                        return '<option value=' + el.user_id + '>' + el.name + '</option>';
                    });
                });
            }).error(function (data) {
            });
    });  
    
    /*Passing Project Id To Get Resources*/
    jQuery("#project").on('change',function(e)
    {

        $("#people").select2({
            placeholder: "Select People"
        }).empty().trigger("change");
      
        var projectId= jQuery("#project").val();

        var customattr = $('option:selected', this).attr('data-custom');
        $("#selected-data").val(customattr);
        
        if(customattr == 'practices'){
            jQuery.ajax({
                type: "POST",
                data: 'practicesId=' + projectId,
                url: '/resources-practices',
            }).success(function (data) {

                $("#people").empty().append('<option value="">Select People</option>');
                $("#people").append(function () {
                    return $.map(data, function (el) {
                        return '<option value=' + el.user_id + '>' + el.name + '</option>';
                    });
                });
            }).error(function (data) {
            });
        }else{
            jQuery.ajax({
                type: "POST",
                data: 'projectId=' + projectId,
                url: '/resources-project',
            }).success(function (data) {

                $("#people").empty().append('<option value="">Select People</option>');
                $("#people").append(function () {
                    return $.map(data, function (el) {
                        return '<option value=' + el.id + '>' + el.name + '</option>';
                    });
                });
            }).error(function (data) {
            });
        }

    });

    $('#fromyear').change(function(){
        $('#fromdate').prop('selectedIndex',0);
    });
});
