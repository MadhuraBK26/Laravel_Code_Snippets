                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        /* Function check blank fields and throws validation error */

function emptyFieldValidation() {
    $('#resource-data, #projectManagerReport, #profile-data').on('click', '.btn.btn-info', function () {
        console.log('called');
        $('form').validate({
            highlight: function (element) {
                $(element).closest('.form-group, .form-control').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group, .form-control').removeClass('has-error');
            }
        });
    });
}

$(document).ready(function () {
    $("body").delegate('.numberField', 'focusout', function(){
        if($(this).val() < 0){
            $(this).val('0');
        }
    });

    jQuery("#user-advanced-data").submit(function (e) {        
        
        var dep = $("#department").val();
        if(dep == ''){
            $("#current_exp_section").trigger("click");
            alert("Select Department");
            e.preventDefault();
            return false;
        }
        
    });

    /*Resource Rating Details*/
    jQuery("#resource-data").submit(function (e) {
        e.preventDefault();
        resourceRatingDetails();
    });

    function resourceRatingDetails() {
        var formdata = jQuery("#resource-data").serialize();

        jQuery.ajax({
            type: "POST",
            data: formdata,
            url: '/resource-details',
        }).success(function (data) {
            $('#resource-sheet').html(data).show();
        }).error(function (data) {
            var d = data.responseJSON;
            $('#resource-sheet').hide();
            $.notify(d.msg, "error");
            $('#startMonth,#endMonth').val('');
            $('#resource-project').select2({
                placeholder: "Select Project"
            });
        });
    }  
    
    var selectIds = $('#personal-details, #exp-details, #current-details, #performance-details');
    $(function ($) {
        selectIds.on('show.bs.collapse hidden.bs.collapse', function () {
            $(this).prev().find('.glyphicon').toggleClass('glyphicon-minus-sign glyphicon-plus-sign');
        })
    });

    /*Populating the project-Manager in NavigatorManagement*/
    jQuery("#manager-id,#people-id,#project-id").select2({
        width: '100%'
    });



    $("#startMonth,#endMonth,#resource-project").keyup(function () {
        var value = $(this).val();
        if (value) {
            resourceRatingDetails();
        }
    }).keyup();

    /*Passing practices Id To Get Reporting Manager*/
    jQuery("#practices-id").on('click',function(e)
    {
        e.preventDefault();
        reportingManagerInHierarchyPractice();
    });
    function reportingManagerInHierarchyPractice()
    {
        var practicesId= jQuery("#practices-id").val();
        jQuery.ajax({
                type: "POST",
                data: 'practices=' + practicesId,
                url: '/resource-practices-id',
            })
            .success(function (data) {

                $("#division-head").val(data[0].name);
                $("#practice_head_id").val(data[0].id);
                $("#reporting-manager-id").empty().append('<option value="">Select Reporting Manager</option>');
                $("#reporting-manager-id").append(function () {
                    return $.map(data, function (el, i) {
                        return '<option value=' + el.id + '>' + el.name + '</option>';
                    });
                });
            }).error(function (data) {
            alert("Data was not found!!");
        });
    }

    /*Passing delivery Id To Get Reporting Manager*/
    jQuery("#navigator-designation-id").on('click',function(e)
    {
        e.preventDefault();
        reportingManagerInHierarchyForPracticeLead();
    });
    function reportingManagerInHierarchyForPracticeLead()
    {
        var designationId= jQuery("#navigator-designation-id").val();
        jQuery.ajax({
            type: "POST",
            data: 'designationId=' + designationId,
            url: '/delivery-practices-id',
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
    }

    /*Passing delivery Id To Get Reporting Manager*/
    jQuery("#name").on('click',function(e)
    {
        e.preventDefault();
        listingSortValueForTheCategory();
    });
    function listingSortValueForTheCategory()
    {
        var categoryId= jQuery("#name").val();
        jQuery.ajax({
            type: "POST",
            data: 'categoryId=' + categoryId,
            url: '/category-sort-id',
        })
            .success(function (data) {
                $("#sort").val(data.feedback_metric.sort+1);
            }).error(function (data) {
            alert("Data was not found!!");
        });
    }

});