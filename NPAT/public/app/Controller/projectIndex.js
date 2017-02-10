$(function () {
    var currentTime = new Date();
    var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth() - 2, 1); //previous month
    var maxDate = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);  //current month
    var maxEndDate = new Date(currentTime.getFullYear(), currentTime.getMonth() + 1, 0);
    
    bondProjectStartDate();
    bindProjectEndDate();
    bindNavigatorEndDate();
    bondNavigatorStartDate();

});

/* Get Project End Date */

function bindProjectEndDate() {
    $('#project-end-date').datepicker({
        dateFormat: "dd-mm-yy"
    });
}

/* Get Project Start Date */

function bondProjectStartDate() {
    $("#project-created-date").datepicker({
        beforeShow: function (i) {
            if ($(i).attr('readonly')) {
                return false;
            }
        },
        dateFormat: 'dd-mm-yy',
        onSelect: function (selectedDate) {
            var date1 = $('#project-created-date').datepicker('getDate');
            var date = new Date(Date.parse(date1));
            var tmp = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            var newDate = tmp.toDateString();
            newDate = new Date(Date.parse(newDate));
            console.log(date,"hello");
            $('#project-end-date').datepicker("option", "minDate", date);
        }
    });
}


function bindNavigatorEndDate() {
    $('#end-date').datepicker({
        dateFormat: "dd-mm-yy"
    });
}

/* Get Project Start Date */

function bondNavigatorStartDate() {
    $("#start-date").datepicker({
        beforeShow: function (i) {
            if ($(i).attr('readonly')) {
                return false;
            }
        },
        dateFormat: 'dd-mm-yy',
        onSelect: function (selectedDate) {
            var date1 = $('#start-date').datepicker('getDate');
            var date = new Date(Date.parse(date1));
            var tmp = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            var newDate = tmp.toDateString();
            newDate = new Date(Date.parse(newDate));
            $('#end-date').datepicker("option", "minDate", date);
        }
    });
}