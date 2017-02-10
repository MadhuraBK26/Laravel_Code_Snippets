/* Function returns a quarter date range list */

function fetchQuarterDateRange() {
    var selectedQuarter = $(".quarter option:selected").val();
    if ($(".quarter option:selected").val() == $(".quarter option:selected").val()) {
        var year = $('.year option:selected').val();
        console.log(year);
        var quarter = $(".quarter option:selected").val();
        if (quarter != 5) {
            var fromdate = new Date(year, quarter * 3 - 3, 1);
            var todate = new Date(year, quarter * 3, 0);
            var start = convert(fromdate);
            var end = convert(todate);
            document.getElementById('start').value = start;
            document.getElementById('end').value = end;
        }
        else {
            var startquarter = new Date(year, 0, 1);
            var endquarter = new Date(year, 11, 31);
            var start = convert(startquarter);
            var end = convert(endquarter);
            document.getElementById('start').value = start;
            document.getElementById('end').value = end;
        }
    }
}
function bindFetchQuarterDateRangeToOnChange(selector) {
    $(selector).change(function () {
        fetchQuarterDateRange();
        getYearSelector();
    });
}
/* Get Start of the Month from Monthpicker */
function getMonthPickerStart(selector) {
    jQuery(selector).monthpicker({
        showOn: "both",
        dateFormat: 'M yy',
        yearRange: 'c-5:c+10'
    });
}

/* Get End of the Month from Monthpicker */
function getMonthPickerEnd(selector) {
    jQuery(selector).monthpicker({
        showOn: "both",
        dateFormat: 'M yy',
        yearRange: 'c-5:c+10',
    });
}

/* Get Year for Monthpicker */
function getYearSelector() {
    var d = new Date();
    $('#fromyear option, #toyear option').hide();
    for (var i = 0; i <= 3; i++) {
        var option = "<option value=" + parseInt(d.getFullYear() + -i) + ">" + parseInt(d.getFullYear() + -i) + "</option>"
        $('#fromyear,#toyear').append(option);
    }

}

function convert(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
    return [date.getFullYear(), mnth, day].join("-");
}