/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function JSClass() {
    this.roleKey = $("#role_key").val();
    this.team = $("#team").val();
    this.eventName = null;
    this.eventId = null;
}
JSClass.prototype.setMenu = function () {
    if (this.roleKey === "3") {
        $(".li-disabled").addClass('disabled');
        $(".li-fp").removeClass('disabled');
    } else if (this.roleKey === "4") {
        $(".li-disabled").addClass('disabled');
        $(".li-pe").removeClass('disabled');
    } else {
        $(".li-disabled").removeClass('disabled');
    }
};
JSClass.prototype.loadStaff = function () {

    $.ajax({
        type: "GET",
        cache: false,
        dataType: "json",
        url: "../libs/php/staff.php",
        success: function (response) {
            $('#staff').empty();
            $('#staff').append($('<option>', {
                value: "",
                text: ""
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
JSClass.prototype.employeeTimeSetting = function (id, prop, val, action) {

    $.ajax({
        url: "php/update_employee_time.php",
        data: {id: id, val: val, prop: prop, action: action},
        type: 'POST',
        dataType: "json",
        success: function (res) {
        }
    });
};
JSClass.prototype.workingHoursSubmit = function () {
    var work_shift_start = $("#work_shift_start");
    var work_shift_stop = $("#work_shift_stop");
    if (work_shift_start.val() === "") {
        work_shift_start.focus();
        return;
    }
    var fd = new FormData($("#frm")[0]);
    $.ajax({
        url: "php/update_work_shift.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.success) {
                window.location = 'work_shift.php';
            } else {
                alert('Update fail');
            }
        }
    });

};
JSClass.prototype.workingHoursEdit = function (id) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('action', "edit_to_text");
    $.ajax({
        url: "php/update_work_shift.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            $("#work_shift_start").val(res.work_shift_start);
            $("#work_shift_stop").val(res.work_shift_stop);
            $("#id").val(res.work_shift_id);
            $("#action").val("edit");
        }
    });
};
JSClass.prototype.workingHoursDelete = function (id) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('action', "del");
    $.ajax({
        url: "php/update_work_shift.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.success) {
                window.location = 'work_shift.php';
            } else {
                alert('Delete fail');
            }
        }
    });
};
JSClass.prototype.reasonSubmit = function () {
    var reason_name = $("#reason_name");
    var reason_day = $("#reason_day");
    if (reason_name.val() === "") {
        reason_name.focus();
        return;
    }
    var fd = new FormData($("#frm")[0]);
    $.ajax({
        url: "php/update_reason.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.success) {
                window.location = 'reason.php';
            } else {
                alert('Update fail');
            }
        }
    });
};
JSClass.prototype.reasonEdit = function (id) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('action', "edit_to_text");
    $.ajax({
        url: "php/update_reason.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            $("#reason_name").val(res.reason_name);
            $("#reason_day").val(res.reason_day);
            $("#id").val(res.reason_id);
            $("#action").val("edit");
        }
    });
};
JSClass.prototype.reasonDelete = function (id) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('action', "del");
    $.ajax({
        url: "php/update_reason.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.success) {
                window.location = 'reason.php';
            } else {
                alert('Delete fail');
            }
        }
    });
};
JSClass.prototype.roleSubmit = function () {
    var role_name = $("#role_name");
    if (role_name.val() === "") {
        role_name.focus();
        return;
    }
    var fd = new FormData($("#frm")[0]);
    $.ajax({
        url: "php/update_role.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.success) {
                window.location = 'role.php';
            } else {
                alert('fail');
            }
        }
    });
};
JSClass.prototype.roleEdit = function (id) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('action', "edit_to_text");
    $.ajax({
        url: "php/update_role.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            $("#role_name").val(res.role_name);
            $("#role_discription").val(res.role_discription);
            $("#roleKey").val(res.role_key);
            $("#id").val(res.role_id);
            console.log(res.role_key);
        }
    });
};
JSClass.prototype.getRolekey = function () {

    $.ajax({
        url: "php/update_role.php",
        data: {action: 'key'},
        type: 'POST',
        cache: false,
        dataType: 'json',
        success: function (res) {
            $("#role_key").val(res);
        }
    });
};
JSClass.prototype.userSubmit = function () {

    var uid = $("#staff");
    var role_key = $("#role_key");
    if (uid.val() === "") {
        uid.focus();
        return;
    }
    if (role_key.val() === "") {
        role_key.focus();
        return;
    }
    var fd = new FormData($("#frm")[0]);
    $.ajax({
        url: "php/update_user.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.success) {
                window.location = 'user.php';
            } else {
                alert('fail');
            }
        }
    });
};
JSClass.prototype.userEdit = function (id) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('action', "edit_to_text");
    $.ajax({
        url: "php/update_user.php",
        data: fd,
        type: 'POST',
        cache: false,
        async: false,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {

            $("#id").val(res.admin_user_id);
            $('.selectpicker').val(res.uid);
            $('.selectpicker').selectpicker('refresh');
            $('#role_key option[value="' + res.role_key + '"]').prop('selected', true);
        }
    });
};
JSClass.prototype.calendarUser = function (team, shiftId) {

    //var color = ["bg-red", "bg-aqua","bg-orange", "bg-blue","bg-green", "bg-light-blue", "bg-yellow", "bg-teal",   "bg-lime",  "bg-muted", "bg-navy", "bg-maroon", "bg-fuchsia"];
    var color = ["bg-red", "bg-blue"];
    $.ajax({
        url: "php/calendar_user.php",
        type: 'GET',
        data: {team: team, shiftId: shiftId},
        cache: false,
        dataType: 'json',
        success: function (res) {
            $("#external-events").empty();
            //$("#external-events").append('<div id="trash" class="external-event1 bg-gray"><img  src="../images/trashcan.png" width=""></div>');
            $("#external-events").append('<div class="external-event bg-red" id="1234567890">Vietnam</div>');
            $.each(res, function (key, value) {

                $("#external-events").append('<div class="external-event bg-blue" id="' + value.id + '">' + value.name + '</div>');

            });
        }
    });
};
JSClass.prototype.getWorkshift = function (id) {
    $.ajax({
        url: "php/load_work_shift.php",
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
                $.each(res.data, function (i, item) {
                    $('#work_shift').append($('<option>', {
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
};
JSClass.prototype.setWorkshift = function (id) {
    $.ajax({
        url: "php/get_work_shift.php",
        type: 'GET',
        data: {id: id},
        cache: false,
        dataType: 'json',
        success: function (res) {
            if (res.text) {
                $(".console").text(res.text);
            } else {
                $(".console").text('All');
            }

        }
    });
};
JSClass.prototype.getCalender = function (id, team) {

    // var result = "";
    var source = [];
    $.ajax({
        url: "php/load_calendar.php",
        data: {id: id, team: team},
        type: 'GET',
        cache: false,
        async: false,
        dataType: 'json',
        success: function (res) {

            $.each(res.data, function (key, value) {
                console.log(value.event_start);
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
    console.log(source);
    return source;

};
JSClass.prototype.deleteEventShow = function (eventId, eventName, eventStart, eventEnd) {
    $("#delete").modal('show');
    if (eventEnd == 'Invalid date') {
        eventEnd = eventStart
    }
    console.log(eventId);
    $(".text-info-del").html("<p>Name : <code>" + eventName + '</code><p>'
            + '<p>Start : <code>' + eventStart + '</code></p>'
            + '<p>End : <code>' + eventEnd + '</code></p>'
            + ' <p class="text-warning"><small>If you don\'t Delete, your changes will be lost.</small></p>'
            );
    this.eventId = eventId;
    this.eventName = eventName;
};
JSClass.prototype.deleteEvent = function (eventId) {
    var data = {
        action: 'delete',
        event_id: eventId
    };
    $.ajax({
        url: 'php/calendar_process.php',
        data: data,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {
            $('#calendar').fullCalendar('removeEvents', eventId);
            $("#delete").modal('hide');
        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
};

JSClass.prototype.getExtraDayshift = function (uid) {

    $.ajax({
        url: 'php/extra_dayshift.php',
        data: {uid: uid, action: 'select'},
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {

            $element = $("#dt_tbody");
            $element.empty();
            if (response.length <= 0) {
                $element.append('<tr>'
                        + '<td colspan="4">No data in table</td>'
                        + '</tr>');
            }
            $.each(response, function (index, items) {

                $element.append('<tr>'
                        + '<td>'
                        + '<button type="button" data-toggle="tooltip" title="" data-id="' + items.id + '"  data-original-title="Edit" class="btn btn-warning btn-xs btn-edit"><i class="fa fa-edit"></i> Edit</button>'
                        + '<button type="button" data-toggle="tooltip" title="" data-id="' + items.id + '"  data-original-title="Delete" class="btn btn-danger btn-xs btn-del" style="margin-left:5px"><i class="fa  fa-close "></i> Del</button>'
                        + '</td>'
                        + '<td>' + items.days + '</td>'
                        + '<td>' + items.date + '</td>'
                        + '<td>' + items.work_shift + '</td>'
                        + '</tr>');

            });

        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
};
JSClass.prototype.getExtraDayshiftEdit = function (id) {

    $.ajax({
        url: 'php/extra_dayshift.php',
        data: {id: id, action: 'edit_to_text'},
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {

            console.log(response);
            $("#btn_submit").data('action', 'edit');
            $("#btn_submit").data('id', response[0].id);
            $("#txt_date").val(response[0].date);
            $("#days").val(response[0].days);
            $("#work_shift").val(response[0].work_shift_id);
            $('.selectpicker').selectpicker('refresh');

        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
};
JSClass.prototype.getExtraDayshiftDel = function (id) {

    $.ajax({
        url: 'php/extra_dayshift.php',
        data: {id: id, action: 'del'},
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function (response) {

            JSClass.getExtraDayshift($("#btn_submit").data("uid"));

        },
        error: function (e) {
            console.log(e.responseText);
        }
    });
};

//---------------------------------------------------------------------------------------------------------------------------------------------
var JSClass = new JSClass();
//---------------------------------------------------------------------------------------------------------------------------------------------
$(function () {


    $('#dataTable').dataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false,
        'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': ['nosort']
            }]
    });
    JSClass.setMenu();
    $(".checkbox-checked").click(function () {
        var checked = $(this).is(':checked');
        var prop = $(this).data('prop');
        var id = $(this).val();
        JSClass.employeeTimeSetting(id, prop, checked, 'set_check');
    });
    $(".work_shift").change(function () {
        var uid = $(this).data("uid");
        var val = $(this).val();
        var prop = 'work_shift_id';
        JSClass.employeeTimeSetting(uid, prop, val, 'work_shift');
    });
    $(".leader").change(function () {
        var uid = $(this).data("uid");
        var val = $(this).val();
        var prop = 'is_leader';
        JSClass.employeeTimeSetting(uid, prop, val, 'work_shift');
    });
    $(".add-proj").click(function () {
        $(".box-form").css('display', 'block');
    });
    $(".cancel").click(function () {
        $(".box-form").css('display', 'none');
    });
    $(".btn-insert").click(function () {
        var page = $(this).data("page");
        if (page === "work_shift") {
            JSClass.workingHoursSubmit();
        } else if (page === "reason") {
            JSClass.reasonSubmit();
        } else if (page === "role") {
            JSClass.roleSubmit();
        } else if (page === "user") {
            JSClass.userSubmit();
        }
    });
    $(".btn-edit").click(function () {
        $("#action").val('edit');
        var page = $(this).data("page");
        var id = $(this).attr("data-id");
        if (page === "work_shift") {
            JSClass.workingHoursEdit(id);
        } else if (page === "reason") {
            JSClass.reasonEdit(id);
        } else if (page === "role") {
            JSClass.roleEdit(id);
        } else if (page === "user") {
            JSClass.userEdit(id);
        }
    });
    $(".btn-del").click(function () {
        var page = $(this).data("page");
        var id = $(this).attr("data-id");
        $("#action").val('del');
        if (page === "work_shift") {
            JSClass.workingHoursDelete(id);
        } else if (page === "reason") {
            JSClass.reasonDelete(id);
        } else if (page === "role") {
            JSClass.roleSubmit();
        } else if (page === "user") {
            JSClass.userSubmit();
        }
    });
    $(".btn-modal-del").click(function () {

        JSClass.deleteEvent(JSClass.eventId);
    });
    $('.btn-bin').click(function () {
        var $action = $(this).data("action");
        if (confirm('Press OK to confirm ' + $action + ' ?') === true) {

            var values = $('input:checkbox:checked.checkbox-bin').map(function () {
                return this.value;
            }).get();
            var data = {id: JSON.stringify(values), action: $action};
            $.ajax({
                url: "php/deleted.php",
                data: data,
                type: 'POST',
                success: function (res) {
                    if (res.success) {
                        window.location = 'bin.php';
                    }
                }
            });
        }
    });
    $("#work_shift").change(function () {

        var workshiftId = $(this).val();
        JSClass.setWorkshift(workshiftId);
        var events = JSClass.getCalender(workshiftId, JSClass.team);
        $('#calendar').fullCalendar('removeEvents');
        $('#calendar').fullCalendar('addEventSource', events);
        $('#calendar').fullCalendar('rerenderEvents');
    });
    $(document).on("click", "#btn_extra", function () {
        $("#btn_submit").data("uid", $(this).data("uid"));
        $("#openExtraModal").modal("show");
    }).on("click", ".btn-edit", function () {
        $("#box_form").fadeIn();
        JSClass.getExtraDayshiftEdit($(this).data("id"));
    }).on("click", ".btn-del", function () {
        JSClass.getExtraDayshiftDel($(this).data("id"));
    }).on("click", ".btn-add", function () {
        $("#btn_submit").data("action", "add");
        $("#box_form").fadeIn();
    });


    $('#btn_submit').click(function () {

        var days = $("#days").val(),
                date = $("#txt_date").val();

        if (days == null && date == "") {
            return true;
        }
        if ($(this).data("action") === 'edit') {
            if (days.length > 1) {
                alert('Please select day only one for edit');
                return true;
            }
        }

        var fd = new FormData(document.getElementById("form_data"));
        fd.append('action', $(this).data("action"));
        fd.append('id', $(this).data("id"));
        fd.append('uid', $(this).data("uid"));
        $.ajax({
            url: "php/extra_dayshift.php",
            type: 'POST',
            data: fd,
            async: false,
            success: function (data) {

                var json = JSON.parse(data);
                if (json.success) {
                    $("#txt_date").val("");
                    $("#days").val("");
                    $('.selectpicker').selectpicker('refresh');
                    JSClass.getExtraDayshift($("#btn_submit").data("uid"));
                } else {
                    alert(json.msg_text);
                }

            },
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
    });
    //getExtraDayshift
    $('#openExtraModal').on('shown.bs.modal', function () {
        JSClass.getExtraDayshift($("#btn_submit").data("uid"));
    });

});



