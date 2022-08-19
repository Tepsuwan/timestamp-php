/* 
 * 
 */

var JSClass = new JSClass();
$(function () {


    JSClass.loadDataReport();
    JSClass.loadStaff(JSClass.team);
    JSClass.loadTeam(JSClass.team);
    JSClass.loadOffice(JSClass.team);
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
        if (JSClass.reportDetail === true) {
            JSClass.loadDataReportDetail(JSClass.uidDetail);
        } else {
            JSClass.loadDataReport();
        }
        $("#monthly").prop("checked", false);
    });
    $("#monthly").click(function () {
        if (JSClass.reportDetail === true) {
            JSClass.loadDataReportDetail(JSClass.uidDetail);
        } else {
            JSClass.loadDataReport();
        }
    });
    $(".btn-back").click(function () {
        $(".btn-back").css('display', 'none');
        $("#lblAll").css('display', 'inline-block');
        JSClass.reportDetail = false;
        JSClass.loadDataReport();
        updateSettings();
    });
    $('.selectpicker').on('change', function () {
        if (JSClass.reportDetail === true) {
            JSClass.loadDataReportDetail(JSClass.uidDetail);
        } else {
            JSClass.loadDataReport();
        }
    });
    $("#all_user").click(function () {
        $(".btn-back").css('display', 'none');
        $('#staff').val("");
        $('#staff').selectpicker('refresh');
        JSClass.reportDetail = false;
        JSClass.loadDataReport();
        updateSettings();
    });
    $("#file-excel").click(function () {
        JSClass.summaryReportToExcel();
    });
});

function shiftDetial(uid) {
    //$(".btn-back").css('display', 'inline-block');
    $(".btn-detail").css('display', 'none');
    $('#staff').val(uid);
    $('#staff').selectpicker('refresh');
    JSClass.reportDetail = true;

    JSClass.loadDataReportDetail(uid);
    updateSettingsDetail();

}

Date.prototype.ddmmyyyy = function () {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
    var dd = this.getDate().toString();
    return  (dd[1] ? dd : "0" + dd[0]) + "/" + (mm[1] ? mm : "0" + mm[0]) + "/" + yyyy; // padding
};
//    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
//    var firstDay = new Date(y, m, 1);
//    var lastDay = new Date(y, m + 1, 0);
