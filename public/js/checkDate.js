function getThisDate() {
    var datepicker = $('#datepicker').datepicker();
    var date = String(datepicker.value());
    var date = date.split("/");
    if (date.length < 3) date = date[0].split("-");
    if (checkDate(date)) return true;
    else return false;
}

function checkDate(date) {
    var curDate = new Date();
    var curYear = curDate.getFullYear();
    var curMonth = curDate.getMonth() + 1;
    var curDay = curDate.getDate();
    var year = parseInt(date[2]);
    var month = parseInt(date[1]);
    if (year == curYear && month == curMonth) return checkDays(date, curDay);
    else if (year == curYear && month < curMonth && month > 0) return checkDays(date, 0);
    else if (year <= curYear - 1 && (month <= 12 && month > 0)) return checkDays(date, 0);
    else return false;
}

function checkDays(date, curDay) {
    var year = parseInt(date[2]);
    var month = parseInt(date[1]);
    var day = parseInt(date[0]);
    var newDate = new Date(year, month, curDay);
    if (day > 0 && day <= newDate.getDate()) return true;
    else return false;
}