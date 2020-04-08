/***************************************************************************************************************************
                                                    VARIABLES
****************************************************************************************************************************/
var currDate = currDate;
/***************************************************************************************************************************
                                                    FUNCTIONS
****************************************************************************************************************************/
/**
 * Function that fill years and months on select input
 * Works for picka  date to report
 */
function StageDateSelector() {
    var today = new Date(),
        yyyy = today.getFullYear(),
        MM = today.getMonth() + 1,
        html = '';

    for (yyyy; yyyy > 2018; yyyy--) {
        html = html + `<option value="${yyyy}" > ${yyyy} </option>`;
    };

    $('#year').append(`${html}`);

    html = '';
    for (MM; MM > 0; MM--) {
        if (MM < 10) {
            html = html + `<option value="${MM}" > 0${MM} </option>`;
        } else {
            html = html + `<option value="${MM}" > ${MM} </option>`;
        }
    };

    $('#month').append(`${html}`);
};

/**
 * Change option listener
 * If the year selected is current year put to actual month as max option
 * Else put all months
 */
$('#year').change(function () {
    var today = new Date(),
        MM = today.getMonth() + 1,
        html = '';

    if (!($(this).val() == today.getFullYear().toString())) MM = 12;

    for (MM; MM > 0; MM--) {
        if (MM < 10) {
            html = html + `<option value="${MM}" > 0${MM} </option>`;
        } else {
            html = html + `<option value="${MM}" > ${MM} </option>`;
        }
    };

    $('#month').empty();
    $('#month').append(`${html}`);
});

/**
 * Select the right month and year
 */

function selectDate() {
    var parts = currDate.split('-');
    var mydate = new Date(parts[0], parts[1], parts[2]);
    $('#year').val(mydate.getFullYear());
    $('#month').val(mydate.getMonth());
}

/**
 * If other month is selected, then change the page
 */
function redirectRecords() {
    $('#month').change(function () {
        var month = $(this).val();
        var year = $('#year').val();
        document.onSubmit.submit();
    })
}
/***************************************************************************************************************************
                                                    LOAD FUNCTIONS
****************************************************************************************************************************/

$('document').ready(function () {
    StageDateSelector();
    selectDate();
    redirectRecords();
})