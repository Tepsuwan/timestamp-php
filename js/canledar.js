

//---------------------------------------------------------------------------------------------------------------------------------------------
var JSClass = new JSClass();

//---------------------------------------------------------------------------------------------------------------------------------------------
$(function () {

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
    $(".btn-modal-del").click(function () {
        JSClass.deleteEvent(JSClass.eventId);
    });
    $("#work_shift").change(function () {

        var workshiftId = $(this).val();
        var team = $("#team_calendar").val();
        JSClass.setWorkshift(workshiftId);
        var events = JSClass.getCalender(workshiftId, team);
        $('#calendar').fullCalendar('removeEvents');
        $('#calendar').fullCalendar('addEventSource', events);
        $('#calendar').fullCalendar('rerenderEvents');
    });

});



