/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var myVar = setInterval(function () {
    myTimer()
}, 1000);
function myTimer() {
    var d = new Date();
    function pad(n) {
        return n < 10 ? "0" + n : n;
    }
    var formatMonth = pad(d.getDate()) + "/" + pad(d.getMonth() + 1) + "/" + d.getFullYear();
    $('.date-format').html('<kbd>' + formatMonth + ' ' + d.toLocaleTimeString() + '</kbd>');
}
var JSClass = new JSClass();
$(function () {


    JSClass.loadDataViewDetail();
    JSClass.loadStaff();
    JSClass.loadTeam();
    JSClass.loadOffice();
    JSClass.setCookie(JSClass.minutes);
    updateSettings();

    var t = setInterval(function () {
        myCookie();
    }, 1000);
    function myCookie() {
        var cookie = JSClass.getCookie();
        if (cookie === undefined || cookie === null) {
            //Login.logout();            
            Login.logout();
        }
    }
    $(document).click(function (e) {
        JSClass.setCookie(JSClass.minutes);
    });
    //-----datepicker----------------------------------------------------
    $("#txt_fdate,#txt_tdate").datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (e) {
        if (JSClass.reportDetail === true) {
            JSClass.loadDataReportDetail(JSClass.uidDetail);
        } else {
            if (JSClass.viewList === true) {
                JSClass.loadDataViewDetail();
            } else {
                JSClass.loadDataReport();
            }
        }
    });
    $("#monthly").click(function () {
        if (JSClass.reportDetail === true) {
            JSClass.loadDataReportDetail(JSClass.uidDetail);
        } else {
            if (JSClass.viewList === true) {
                JSClass.loadDataViewDetail();
            } else {
                JSClass.loadDataReport();
            }
        }
    });
    $(".btn-back").click(function () {
        $(".btn-back").css('display', 'none');
        $(".btn-detail").css('display', 'inline-block');
        $('.selectpicker').val("");
        $('.selectpicker').selectpicker('refresh');
        JSClass.reportDetail = false;
        JSClass.viewList = false;
        JSClass.loadDataReport();
        updateSettings();
    });
    $('.selectpicker').on('change', function () {
        if (JSClass.reportDetail === true) {
            JSClass.loadDataReportDetail(JSClass.uidDetail);
        } else {
            if (JSClass.viewList === true) {
                JSClass.loadDataViewDetail();
            } else {
                JSClass.loadDataReport();
            }

        }
    });
    $(".sum-report").click(function () {
        JSClass.summaryReport();
    });
    $(".detail-report").click(function () {
        JSClass.detailReport();
    });

    $(".btn-detail").click(function () {
        JSClass.viewList = true;
        $(".btn-back").css('display', 'inline-block');
        $(".btn-detail").css('display', 'none');
        updateSettingsViewlist();
        JSClass.loadDataViewDetail();

    });

});

function shiftDetial(uid) {
    $(".btn-back").css('display', 'inline-block');
    $(".btn-detail").css('display', 'none');
    $('.selectpicker').val(uid);
    $('.selectpicker').selectpicker('refresh');
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
