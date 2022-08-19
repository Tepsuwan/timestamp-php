/*
 * 
 */
var JSClass = new JSClass();
$(function () {


    //JSClass.loadDataTimestampReport();
    JSClass.loadStaff(null);
    JSClass.loadTeam(null);
    JSClass.loadOffice(null);
    updateSettings();


    var t = setInterval(function () {
        ++JSClass.timeInterval;
        if (JSClass.timeInterval >= JSClass.timeOut) {
            Login.logout();//Login.logout();
            clearInterval(t);
        }
    }, 1000);
    $(document).click(function (e) {
        JSClass.timeInterval = 0;
    });    
    
    $('.selectpicker').on('change', function () {
        
        JSClass.loadDataHolidaysReport();
        
    });
//    $("#file-excel").click(function () {
//        JSClass.timestampReportToExcel();
//    });



});
