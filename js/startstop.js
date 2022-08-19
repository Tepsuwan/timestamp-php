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
    $('.date-format').html('<code>' + formatMonth + ' ' + d.toLocaleTimeString() + '</code>');
}



var JSClass = new JSClass();
$(function () {

    JSClass.loadData();
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
    //--------------------------------------------------------------------------

    $("#start").click(function () {
        JSClass.startStop('start');
        $("#start").addClass('disabled btn-success').html('STARTED');
        $("#stop").removeClass('disabled btn-success');
    });
    $("#stop").click(function () {
        var id = $(this).data('id');
        JSClass.startStop('stop', id);
        $("#start,#stop").addClass('disabled btn-success');
    });
    //-----datepicker----------------------------------------------------
    $("#txt_fdate,#txt_tdate").datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (e) {
        JSClass.loadData();
    });
    $("#monthly").click(function () {
        JSClass.loadData();
    });
    $(".report").click(function () {       
        JSClass.viewReport();
    });

});

