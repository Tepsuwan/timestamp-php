
/* global $container, waitingDialog */

//------------------------------------------------------------------------------
function JSClass() {
    this.uid = $("#uid").val();
    this.uidDetail = null;
    this.roleKey = $("#role_key").val();
    this.workshift = $("#work_shift").val();
    this.isEdit = $("#is_edit").val();
    this.fdate = $("#txt_fdate");
    this.tdate = $("#txt_tdate");
    this.staff = $("#staff");
    this.office = $("#office");
    this.page = $("#page");
    this.minutes = 15;
    this.reportDetail = false;
    this.viewList = false;
    this.timeOut = 10 * 60;
    this.timeInterval = 0;


    this.eventName = null;
    this.eventId = null;
    this.Sync = false;
    this.tab = 1;

    if (this.roleKey === '3') {
        this.team = 'FP';
    } else if (this.team === '4') {
        this.team = 'PE';
    } else if (this.team === '5') {
        this.team = '3D';
    } else if (this.team === '6') {
        this.team = 'CAD';
    } else if (this.team === '7') {
        this.team = 'SU';
    } else if (this.team === '8') {
        this.team = 'IT';
    } else {
        this.team = '';
    }


    var time = 0;
    var start = new Date().getTime();
    setTimeout(NTPTime(), 1000);
    function NTPTime() {
        time += 1000;
        $.ajax({
            type: "GET",
            cache: false,
            dataType: "json",
            url: "libs/connect/ntp_time.php",
            success: function (response) {
                $(".time").html(response.NTPtime.time);
                $(".dayText").html(response.NTPtime.dayText);
                $(".month").html(response.NTPtime.fullMonth);
            }
        });
        var diff = (new Date().getTime() - start) - time;
        setTimeout(NTPTime, (1000 - diff));
        return true;
    }

    $(".sum-report").click(function () {
        if (JSClass.roleKey) {
            window.location = "summary-report";
        } else {
            alert('Access Denied');
        }
    });
    $(".holidays-report").click(function () {
        if (JSClass.roleKey) {
            window.location = "holidays-report";
        } else {
            alert('Access Denied');
        }
    });
    $(".stamp-report").click(function () {

        if (JSClass.roleKey === "1" || JSClass.roleKey === "2") {
            window.location = "time-stamp-report";
        } else {
            alert('Access Denied');
        }
    });
    $(".floorplan-shift").click(function () {
        if (JSClass.roleKey === "1" || JSClass.roleKey === "2" || JSClass.roleKey === "3") {
            window.location = "calendar/FP";
        } else {
            alert('Access Denied');
        }
    });
    $(".photo-shift").click(function () {
        if (JSClass.roleKey === "1" || JSClass.roleKey === "2" || JSClass.roleKey === "4") {
            window.location = "calendar/PE";
        } else {
            alert('Access Denied');
        }
    });
    $(".su-shift").click(function () {
        if (JSClass.roleKey === "1" || JSClass.roleKey === "2" || JSClass.roleKey === "7") {
            window.location = "calendar/SU";
        } else {
            alert('Access Denied');
        }
    });
    $(".start-log").click(function () {
        if (JSClass.roleKey === "1" || JSClass.roleKey === "2" || JSClass.roleKey === "3" || JSClass.roleKey === "4") {
            window.location = "libs/PHPExcel/Report/logStartReport.php";
        } else {
            alert('Access Denied');
        }
    });
    $(".stop-log").click(function () {
        if (JSClass.roleKey === "1" || JSClass.roleKey === "2" || JSClass.roleKey === "3" || JSClass.roleKey === "4") {
            window.location = "libs/PHPExcel/Report/logStopReport.php";
        } else {
            alert('Access Denied');
        }
    });

    $(".work-time").click(function () {

        var url = "",
                prop = $(this).data("prop");
        if (JSClass.roleKey) {
            if (JSClass.roleKey === "1" || JSClass.roleKey === "2") {//Administrator
                url = "work-time/" + prop;
            } else if (JSClass.roleKey === "3" && prop === "FP") {//	Leader FP
                url = "work-time/" + prop;
            } else if (JSClass.roleKey === "4" && prop === "PE") {//	Leader PE
                url = "work-time/" + prop;
            } else if (JSClass.roleKey === "5" && prop === "3D") {//Leader 3D
                url = "work-time/" + prop;
            } else if (JSClass.roleKey === "6" && prop === "CA") {//Leader CA
                url = "work-time/" + prop;
            } else if (JSClass.roleKey === "7" && prop === "SU") {//Leader SU
                url = "work-time/" + prop;
            } else if (JSClass.roleKey === "8" && prop === "IT") {//Leader IT
                url = "work-time/" + prop;
            }
            if (url === "") {
                alert('Access Denied');
                return false;
            } else {
                window.open(url, '_blank');
            }

        } else {
            alert('Access Denied');
        }
    });

}

Date.prototype.ddmmyyyy = function () {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
    var dd = this.getDate().toString();
    return (dd[1] ? dd : "0" + dd[0]) + "/" + (mm[1] ? mm : "0" + mm[0]) + "/" + yyyy; // padding
};

JSClass.prototype.stamp = function (action) {

    var getData = $container.data('handsontable').getData();
    var dateNow;
    if (action === 'start') {
        dateNow = $("#start").data('date');//date.ddmmyyyy();  
    } else {
        dateNow = $("#stop").data('date');//date.ddmmyyyy();  
    }
    var id = null;
    $.each(getData, function (index, value) {
        //console.log(dateNow, index, value[0], value[2]);
        if (dateNow === value[2]) {
            id = value[0];
            return false;
        }
    });
    this.workshift = $("#work_shift").val();

    var _this = this;
    var fd = new FormData();
    fd.append('action', action);
    fd.append('id', id);
    fd.append('workshift', this.workshift);
    $.ajax({
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        url: 'libs/php/updateStamp.php',
        success: function (res) {			
            if (res.success) {
                _this.loadData();
                _this.postToBztime(action, id, _this.uid);
                $(".error-stop").fadeOut(3000);
            } else {
                $(".error-stop").html('<p> Error : Access denied.  (' + res.message + ')</P>').fadeIn();
            }
        }
    });
};

JSClass.prototype.postToBztime = function (action, id, uid) {
    $.ajax({
        data: {action: action, id: id, uid: uid},
        type: 'GET',
        cache: false,
        dataType: "jsonp",
        url: 'https://time.baezeni.com/timestamp',		
        success: function (res) {			
            if (!res) {
                waitingDialog.show('Fail update in BZ-Time', {progressType: 'danger'});
            }
        }, error: function (jqXHR, exception) {
            waitingDialog.error(jqXHR, exception, function (text) {
                waitingDialog.show(text, {progressType: 'danger'});
            });
        }
    });
};

JSClass.prototype.loadData = function () {

    var m = $("#monthly").is(':checked');
    var d = $("#today").is(':checked');
    var data = {uid: this.uid, fdate: this.fdate.val(), tdate: this.tdate.val(), month: m, today: d};
    $.ajax({
        data: data,
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/loadStamp.php",
        success: function (response) {
            $("#data-hot").data('handsontable').loadData(response.data);
            $('.loading').fadeOut(1000);
        }
    });
    return true;
};
JSClass.prototype.loadDataLogOn = function () {

    //var m = $("#monthly").is(':checked');
    //var d = $("#today").is(':checked');
    //var data = {fdate: this.fdate.val(), tdate: this.tdate.val(), month: m, today: d};
    $.ajax({
        //data: data,
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/loadLogOn.php",
        success: function (response) {

            $("#data-hot").data('handsontable').loadData(response.data);
            $('.loading').fadeOut(1000);

        }
    });
    return true;
};
JSClass.prototype.loadDataHolidays = function () {

    var data = {uid: this.uid};
    $.ajax({
        data: data,
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/load_holidays.php",
        success: function (response) {

            $("#data-hot").data('handsontable').loadData(response.data);
            $('.loading').fadeOut(1000);

        }
    });
    return true;
};
//JSClass.prototype.loadDataForget = function () {
//
//    var data = {uid: this.uid};
//    $.ajax({
//        data: data,
//        type: "GET",
//        cache: false,
//        dataType: "json",
//        url: "libs/php/loadForget.php",
//        success: function (response) {
//            if (response.success) {
//                $('.forget').css('display', 'block');
//                $('.forget').html(
//                        '<p>You forgot to stop Work! '
//                        + '<small class="label label-danger"><i class="fa fa-clock-o"></i> '
//                        + response.stamp_date
//                        + '</small>'
//                        + '</p>'
//                        + '<i class="fa fa-times close"></i>'
//                        );
//            }
//        }
//    });
//};
JSClass.prototype.loadDataReport = function () {
    $('.loading').css('display', 'block');
    var checked = $("#monthly").is(':checked');
    var team = this.team || $('#team').val();
    console.log(team);
    var data = {
        uid: this.uid,
        fdate: this.fdate.val(),
        tdate: this.tdate.val(),
        checked: checked,
        team: team,
        staff: this.staff.val(),
        office: this.office.val()
    };
    $.ajax({
        data: data,
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/loadReport.php",
        success: function (response) {
            $('.loading').fadeOut(1000);
            $("#hot").data('handsontable').loadData(response.data);
        }
    });
};
JSClass.prototype.loadDataReportDetail = function (uid) {
    $('.loading').css('display', 'block');
    var checked = $("#monthly").is(':checked');
    this.uidDetail = uid;
    var team = this.team;
    var staff = this.staff.val();
    var office = this.office.val();
    var data = {
        uid: uid,
        fdate: this.fdate.val(),
        tdate: this.tdate.val(),
        checked: checked,
        team: team,
        staff: staff,
        office: office
    };
    $.ajax({
        data: data,
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/loadSummaryDetail.php",
        success: function (response) {
            $("#hot").data('handsontable').loadData(response.data);
            $('.loading').fadeOut(1000);
        }
    });
};
JSClass.prototype.loadDataHolidaysReport = function () {

    var staff = $("#staff").val() || null;
    if (staff) {
        var staffText = $("#staff option[value='" + staff + "']").text() || null;
    }

    var data = {uid: staff};
    $.ajax({
        data: data,
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/load_holidays_report.php",
        success: function (response) {

            $("#data-hot").data('handsontable').loadData(response.data);

        }
    });

};
JSClass.prototype.loadDataTimestampReport = function () {

    if (this.Sync) {
        $('.sync').css('display', 'block');
    } else {
        //$('.loading').css('display', 'block');
    }
    var checked = $("#monthly").is(':checked');
    var todayChecked = $("#today").is(':checked');
    var team = $("#team").val() || null;
    var staff = $("#staff").val() || null;
    var office = $("#office").val() || null;
    if (staff) {
        var staffText = $("#staff option[value='" + staff + "']").text() || null;
    }
    var f = this.fdate.val().split('/');
    var t = this.tdate.val().split('/');
    var data = {
        fdate: f[2] + '-' + f[1] + '-' + f[0],
        tdate: t[2] + '-' + t[1] + '-' + t[0],
        m: checked,
        today: todayChecked,
        team: team,
        staff: staff,
        office: office,
        staffText: staffText,
        uid: this.uid
    };
    if (staff) {
        nodeJS.socket.emit('timeStampStaffRpt', {message: data});
    } else {
        nodeJS.socket.emit('timeStampRpt', {message: data});
    }
};
JSClass.prototype.timestampReportToExcel = function () {
    //$('.loading').fadeIn();
    var checked = $("#monthly").is(':checked');
    var todayChecked = $("#today").is(':checked');
    var team = $("#team").val();
    var staff = $("#staff").val();
    var office = $("#office").val();
    var data = {
        fdate: this.fdate.val(),
        tdate: this.tdate.val(),
        m: checked,
        today: todayChecked,
        team: team,
        staff: staff,
        office: office
    };
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {
                    $(".bar").css('width', '50%');
                    console.log(evt.lengthComputable);
                }
            }, false);
            xhr.addEventListener("progress", function (evt) {
                console.log(evt.lengthComputable);
                if (evt.lengthComputable) {
                    var percent = Math.round((event.loaded / event.total) * 100);
                    console.log(percent);
                    $(".bar").css('width', percent + '%');
                }
            }, false);
            return xhr;
        },
        type: "POST",
        url: "libs/PHPExcel/Report/TimestampReport.php",
        data: data,
        dataType: 'json',
        success: function (response) {
            $(".bar").css('width', '100%');
            window.location = 'libs/PHPExcel/Report/ExcelFile/' + response.filename;
            $('#loadingExcel').fadeOut(3000);
        },
        beforeSend: function () {
            $(".bar").css('width', '0%');
            $('#loadingExcel').show();
        },
    });
};
JSClass.prototype.summaryReportToExcel = function () {

    //$('.loading').fadeIn();
    var checked = $("#monthly").is(':checked');
    var team = $("#team").val();
    var staff = $("#staff").val();
    var office = $("#office").val();
    var data = {
        fdate: this.fdate.val(),
        tdate: this.tdate.val(),
        checked: checked,
        team: team,
        staff: staff,
        office: office
    };
    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (event) {
                if (event.lengthComputable) {
                    $(".bar").css('width', '80%');
                    console.log(event.lengthComputable);
                }
            }, false);
            xhr.addEventListener("progress", function (event) {
                console.log(event.lengthComputable);
                if (event.lengthComputable) {
                    var percent = Math.round((event.loaded / event.total) * 100);
                    $(".bar").css('width', percent + '%');
                }
            }, false);
            return xhr;
        },
        type: "POST",
        url: "libs/PHPExcel/Report/SummaryReport.php",
        data: data,
        cache: false,
        dataType: 'json',
        success: function (response) {
            $(".bar").css('width', '100%');
            window.location = 'libs/PHPExcel/Report/ExcelFile/' + response.filename;
            $('#loadingExcel').fadeOut(3000);
        },
        beforeSend: function () {
            $(".bar").css('width', '0%');
            $('#loadingExcel').show();
        }
    });
};

JSClass.prototype.loadStaff = function (team) {

    $.ajax({
        type: "GET",
        cache: false,
        data: {team: team},
        dataType: "json",
        url: "libs/php/staff.php",
        success: function (response) {
            $('#staff').empty();
            $('#staff').append($('<option>', {
                value: "",
                text: "All selected"
            }));
            $.each(response, function (i, item) {
                $('#staff').append($('<option>', {
                    value: item.value,
                    text: item.text
                }));
            });
            $('.selectpicker').selectpicker('refresh');

        }
    });
};
JSClass.prototype.loadTeam = function (team) {
    $.ajax({
        type: "GET",
        cache: false,
        data: {team: team},
        dataType: "json",
        url: "libs/php/team.php",
        success: function (response) {
            $('#team').empty();
            $('#team').append($('<option>', {
                value: '',
                text: 'All selected'
            }));
            $.each(response, function (i, item) {
                $('#team').append($('<option>', {
                    value: item.value,
                    text: item.text
                }));
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
};
JSClass.prototype.loadOffice = function (team) {
    $.ajax({
        type: "GET",
        cache: false,
        data: {team: team},
        dataType: "json",
        url: "libs/php/office.php",
        success: function (response) {
            console.log(response);
            $('#office').empty();
            $('#office').append($('<option>', {
                value: '',
                text: 'All selected'
            }));
            $.each(response, function (i, item) {
                $('#office').append($('<option>', {
                    value: item.value,
                    text: item.text
                }));
            });
            $('.selectpicker').selectpicker('refresh');
        }
    });
};
JSClass.prototype.loadBoxStartStop = function () {
    var shiftId = $("#work_shift").val();
    $.ajax({
        type: "GET",
        cache: false,
        data: {uid: this.uid, shiftId: shiftId},
        url: "libs/php/boxStartStop.php",
        success: function (response) {
            $("#box_st").html(response);
        }
    });
    return true;
};
JSClass.prototype.calendarUser = function (team, shiftId) {

    $.ajax({
        url: "setting/php/calendar_user.php",
        type: 'GET',
        data: {team: team, shiftId: shiftId},
        cache: false,
        dataType: 'json',
        success: function (res) {
            $("#external-events").empty();
            if (team === 'FP')
                $("#external-events").append('<div class="external-event bg-red" id="1234567890">Vietnam</div>');

            $.each(res, function (key, value) {
                console.log(value);
                $("#external-events").append('<div class="external-event bg-blue" id="' + value.id + '">' + value.name + '</div>');
            });
        }
    });
    return true;
};
JSClass.prototype.getWorkshift = function (id) {
    $.ajax({
        url: "setting/php/load_work_shift.php",
        type: 'GET',
        cache: false,
        dataType: 'json',
        success: function (res) {
            $("#work_shift").empty();
            var i = 0;
            if (res.success) {
                $('#work_shift').append($('<option>', {
                    value: 'All',
                    text: 'All'
                }));
                $('#work_shift_nextday').append($('<option>', {
                    value: '',
                    text: 'Please select'
                }));
                $.each(res.data, function (i, item) {
                    $('#work_shift').append($('<option>', {
                        value: item.value,
                        text: item.text
                    }));
                    $('#work_shift_nextday').append($('<option>', {
                        value: item.value,
                        text: item.text
                    }));
                });
                $('#work_shift').val(id);

            } else {
                alert("Error! can not load work shift");
            }
        }
    });
    return true;
};
JSClass.prototype.setWorkshift = function (id) {
    $.ajax({
        url: "setting/php/get_work_shift.php",
        type: 'GET',
        data: {id: id},
        cache: false,
        dataType: 'json',
        success: function (res) {
            if (res.text) {
                $(".console").text(res.text);
            } else {
                $(".console").text('All Shift');
            }
        }
    });
};
JSClass.prototype.getCalender = function (id, team) {
    var source = [];
    $.ajax({
        url: "setting/php/load_calendar.php",
        data: {id: id, team: team},
        type: 'GET',
        cache: false,
        async: false,
        dataType: 'json',
        success: function (res) {

            $.each(res.data, function (key, value) {
                var date = new Date(value.event_start);
                var d = date.getDate(),
                        m = date.getMonth(),
                        y = date.getFullYear(),
                        h = date.getHours(),
                        mi = date.getMinutes();
                var dateEnd = new Date(value.event_end);
                var de = dateEnd.getDate(),
                        me = dateEnd.getMonth(),
                        ye = dateEnd.getFullYear(),
                        he = dateEnd.getHours(),
                        mie = date.getMinutes();
                obj = {
                    "id": value.calendar_id,
                    "uid": value.uid,
                    "title": value.title,
                    "start": new Date(y, m, d, h, mi),
                    "end": new Date(ye, me, de, he, mie),
                    backgroundColor: value.calendar_bg_color, //yellow
                    borderColor: value.calendar_border_color //yellow
                };
                source.push(obj);
            });
        }
    });
    return source;

};
JSClass.prototype.deleteEventShow = function (eventId, eventName, eventStart, eventEnd) {
    $("#delete").modal('show');
    if (eventEnd === 'Invalid date') {
        eventEnd = eventStart;
    }
    $(".text-info-del").html("<p>Name : <code>" + eventName + '</code><p>'
            + '<p>Start : <code>' + eventStart + '</code></p>'
            + '<p>End : <code>' + eventEnd + '</code></p>'
            );
    this.eventId = eventId;
    this.eventName = eventName;
};
JSClass.prototype.deleteEvent = function (eventId) {

    var team = $("#team_calendar").val();
    var data = {
        action: 'delete',
        event_id: eventId
    };
    $.ajax({
        url: 'setting/php/calendar_process.php',
        data: data,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {
            var shiftId = $("#work_shift").val();
            var events = JSClass.getCalender(shiftId, team);
            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', events);
            $('#calendar').fullCalendar('rerenderEvents');
            $("#delete").modal('hide');
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
    return true;
};

JSClass.prototype.getWorkShifts = function () {

    var self = this;
    $.ajax({
        data: {uid: this.uid},
        type: "GET",
        cache: false,
        dataType: "json",
        url: "libs/php/load_workshifts.php",
        success: function (response) {
            var objectData = response;
            Object.keys(objectData).map(function (key, index) {
                $('#workshifts').append($('<option>', {
                    value: objectData[key].work_shift_id,
                    text: objectData[key].work_shifts
                }));
            });
            $('#workshifts').val(self.workshift);


        }
    });
    return true;
};




