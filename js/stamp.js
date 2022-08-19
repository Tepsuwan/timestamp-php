/* 
 * 
 */
var JSClass = new JSClass();

$(function () {

    $("#data-hot").height(window.innerHeight - 433);
    JSClass.loadBoxStartStop();
    JSClass.loadData();
    JSClass.getWorkShifts();
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
    //--------------------------------------------------------------------------

    $(".container").on('click', '#start', function () {
        JSClass.loadDataTimestampReport();
        JSClass.stamp('start');
        $("#start").addClass('disabled').html('<i class=\"fa fa-ban text-danger\"></i> START');
        $("#stop").removeClass('disabled').html('STOP');

        //window.open('http://www.baezeni.com/', '_blank')

    });
    $(".container").on('click', '#stop', function () {
        $("#myModal").modal('show');
    });
    $("#btn_stop").click(function () {
        if (JSClass.tab !== 1) {
            $(".tab-manu").removeClass("active");
            $("#normal").addClass("active");
            JSClass.loadData();
            updateSettings();
        }
        JSClass.loadDataTimestampReport();
        JSClass.stamp('stop');
        $("#myModal").modal('hide');


    });

    //-----datepicker----------------------------------------------------
    $("#txt_fdate,#txt_tdate").datepicker({
        format: 'dd/mm/yyyy'
    }).on('changeDate', function (e) {
        $("#today,#monthly").prop("checked", false);
        JSClass.loadData();
    });
    $("#today").click(function () {
        $("#monthly").prop("checked", false);
        JSClass.loadData();
    });
    $("#monthly").click(function () {
        $("#today").prop("checked", false);
        JSClass.loadData();
    });

    $('#reasonModal').on('hidden.bs.modal', function () {
        JSClass.loadData();
    });

    $('.tab-manu').on('click', function (event) {

        var tab = $(this).attr("id");
        if (tab === "normal") {
            JSClass.tab = 1;
            JSClass.loadData();
            updateSettings();
        } else if (tab === "holidays") {
            JSClass.tab = 3;
            JSClass.loadDataHolidays();
            updateHolidaySettings();
        } else if (tab === "logon") {
            JSClass.tab = 2;
            JSClass.loadDataLogOn();
            updateLogOnSettings();
        }

    });
    $(document).on('change', '#workshifts', function () {

        $("#work_shift").val($(this).val());

    });



});
function reasonModel(day, name, detail) {

    var html = '<h2 style="margin-bottom: 20px">' + name + '(' + day + ')</h2>';
    html += '<p><h4><i class="fa fa-sun-o"></i> รายการลา หรือ วันหยุดประจำปี ที่ใช้แล้ว</h4></p>';
    $.each(detail, function (index, value) {
        html += '<p class=""> (' + (index + 1) + ') ' + value.stamp_date + ' > ' + value.reason_name + (value.stamp_note != "" ? ' > ' + value.stamp_note : "") + '</p>';
    });
    html += '<br><p><h4 class="text-red"><i class="fa fa-warning"></i> คุณได้ใช้วันลา หรือ วันหยุดประจำปีครบกำหนดของบริษัทแล้ว.</h3></p>';
    html += '<p class="text-warning"><small>ระบบจะไม่บันทึกหาก วันลา หรือ วันหยุดครบกำหนดของบริษัทแล้ว</small></p>';

    $(".reason-body").html(html);
    $("#reasonModal").modal('show');

}

