$(document).ready(function () {

    /*Updating User Password Details*/
    jQuery("#profile-data").submit(function (e) {
        e.preventDefault();
        updateUserPasswordDetails();
    });

    function updateUserPasswordDetails() {
        var formdata = jQuery("#profile-data").serialize();
console.log(formdata);
        jQuery.ajax({
            type: "POST",
            data: formdata,
            url: '/profile-details'

        }).success(function (data) {
            $.notify(data.msg, "success");
            $('.password').val('');
            $('#reset_question').attr('checked', false);
            $('#insertinputs').hide();

        }).error(function (data) {
            var d = data.responseJSON;
            $.notify(d.msg, "error");

        });
    }

    /*Update User Profile Details*/
    jQuery("#user-profile").submit(function (e) {
        e.preventDefault();
        updateUserProfileDetails();
    });

    function updateUserProfileDetails() {
        var formdata = jQuery("#user-profile").serialize();

        jQuery.ajax({
            type: "POST",
            data: formdata,
            url: '/user-update'

        }).success(function (data) {
            $.notify(data.msg, "success");
            $('.form-box').attr('disabled', 'disabled');
            $(".hideBtn").hide();

        });
    }

    /*Edit Button Functionalities*/
    jQuery('.editBtn').on('click', function (event) {
        event.preventDefault();

        if ($('.form-box').attr('disabled')) {
            $('.form-box').removeAttr('disabled');
            $(".hideBtn").show();
        } else {
            $('.form-box').attr('disabled', 'disabled');
            $('.hideBtn').hide();
        }
    });

    jQuery("#reset_question").on('change', function (event) {
        event.preventDefault();
        dynInput(this);
    });

    function dynInput(cbox) {
        if (cbox.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = "insertfield";
            input.name = "recovery_question";
            input.placeholder = "Who is role model?";
            var div = document.createElement("div");
            div.id = cbox.name;
            div.innerHTML = cbox.name;
            div.appendChild(input);
            document.getElementById("insertinputs").appendChild(div);
        } else {
            document.getElementById(cbox.name).remove();
        }
    }
});






