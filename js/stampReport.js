/*
 * 
 */
var JSClass = new JSClass();
$(function () {


    JSClass.loadDataTimestampReport();
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
    //-----datepicker----------------------------------------------------
    $("#txt_fdate,#txt_tdate").datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (e) {
        $("#today,#monthly").removeAttr('checked');       
        JSClass.loadDataTimestampReport();
          $(this).datepicker('hide');
    });
    $("#today").click(function () {
        $("#monthly").removeAttr('checked');
        JSClass.loadDataTimestampReport();
    });
    $("#monthly").click(function () {
        $("#today").removeAttr('checked');
        JSClass.loadDataTimestampReport();
    });
    $('.selectpicker').on('change', function () {
        
        JSClass.loadDataTimestampReport();
        
    });
    $("#file-excel").click(function () {
        JSClass.timestampReportToExcel();
    });



});
