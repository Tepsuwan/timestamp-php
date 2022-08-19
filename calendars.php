<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
if (empty($_SESSION['userId'])) {
    echo "<script>";
    echo "window.location='index.php'";
    echo "</script>";
}
include_once './libs/connect/connect.php';
$userId = $_SESSION['userId'];
$fromDate = date('01/m/Y');
$toDate = date('t/m/Y');
$team = $_GET['t'];
if ($team == 'FP') {
    $work_shift_id = "55c871499df11"; //14:00
    $team_text = 'Floor plan';
} else {
    $work_shift_id = "562dd71e18365"; //14:00-23:00
    $team_text = 'Photo edit';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">   
        <title>BZ Timestamp::Day shift</title>
        <?php
        include './path_rewrite.php';
        ?>
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>  
        <link href="libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="libs/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>

        <link href="libs/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <link href="libs/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />
        <link href="bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
        <link href="css/custom.css" rel="stylesheet">       
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->  
    </head>
    <body>

        <?php include './libs/dist/php/menu_fontpage.php'; ?>       

        <div class="container"> 
            <div class="row">                
                <div class="col-md-12">                                
                    <div class="box box-primary">                                             
                        <div class="box-body">  
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box box-solid">
                                                <div class="box-body">
                                                    <div class="box-header with-border">
                                                        <h4 class="box-title"><b><?php echo $team_text; ?> Shift</b></h4>
                                                    </div>
                                                    <div class="form-group">
                                                        <select class="form-control" name="work_shift" id="work_shift"> </select>
                                                    </div>
                                                    <?php if ($team == "PE") { ?>
                                                        <div class="form-group">
                                                            <label>Next day</label>
                                                            <select class="form-control" name="work_shift_nextday" id="work_shift_nextday">                                                    
                                                            </select>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Draggable<kbd class="console"></kbd></h4>
                                        </div>
                                        <div class="box-body">                                                                       
                                            <div id='external-events'></div>
                                        </div>
                                    </div>                         
                                </div>
                                <div class="col-md-9">
                                    <div class="box box-solid">
                                        <div class="box-body no-padding">                                           
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>            
        </div>
        <footer class="footer">
            <div class="container">
                <p class="muted credit">
                    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="http://baezeni.com/">Baezeni</a>.</strong> All rights reserved.
                </p> 
            </div>
        </footer>
        <div class="box-loading-top">            
            <div class="loading" >
                <p>Loading.....</p>
            </div>           
        </div>
        <input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>">
        <input type="hidden" name="role_key" id="role_key" value="<?php echo $_SESSION['role_key']; ?>">
        <input type="hidden" name="work_shift" id="work_shift" value="<?php echo $_SESSION['work_shift_id']; ?>"> 
        <input type="hidden" name="team_calendar" id="team_calendar" value="<?php echo $team; ?>">
        <div id="info" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Information</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Please select <code>Work Shift</code>?</h4>                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>                        
                    </div>
                </div>
            </div>
        </div>
        <div id="infoNextday" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Information</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Please select <code>Next day</code>?</h4>                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>                        
                    </div>
                </div>
            </div>
        </div>
        <div id="delete" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Shift Information</h4>
                    </div>
                    <div class="modal-body text-info-del">
                        <p class="text-info-del"></p>                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                        <button type="button" class="btn btn-primary btn-modal-del">Delete</button>                      
                    </div>
                </div>
            </div>
        </div>

        <script src="libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="libs/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
        <script src="libs/plugins/moment.min.js" type="text/javascript"></script>               
        <script src="libs/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="js/JSClass.js"></script>
        <script src="js/login.js"></script>

        <script src="js/canledar.js"></script>

        <script type="text/javascript">
            $(function () {

                var team = $("#team_calendar").val();
                JSClass.calendarUser(team, '<?php echo $work_shift_id; ?>');
                JSClass.setWorkshift('<?php echo $work_shift_id; ?>');
                JSClass.getWorkshift('<?php echo $work_shift_id; ?>');

                var events = JSClass.getCalender('<?php echo $work_shift_id; ?>', team);
                /* initialize the external events
                 -----------------------------------------------------------------*/
                function ini_events(ele) {

                    ele.each(function () {

                        var eventObject = {
                            title: $.trim($(this).text()) // use the element's text as the event title
                        };

                        $(this).data('eventObject', eventObject);

                        $(this).draggable({
                            zIndex: 1070,
                            revert: true, // will cause the event to go back to its
                            revertDuration: 0  //  original position after the drag
                        });

                    });
                }
                $(document).on("mouseover", "#external-events", function () {
                    ini_events($('#external-events div.external-event'));
                });

                /* initialize the calendar
                 -----------------------------------------------------------------*/

                // var date = new Date();
//                var d = date.getDate(),
//                        m = date.getMonth(),
//                        y = date.getFullYear();
                $('#calendar').fullCalendar({
                    firstDay: 0,
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month' //'month,agendaWeek,agendaDay'
                    },
                    buttonText: {
                        today: 'today',
                        month: 'month',
                        week: 'week',
                        day: 'day'
                    },
                    eventRender: function (event, element) {

                        element.attr('href', 'javascript:void(0);');
                        element.click(function () {
                            console.log(event);
                            JSClass.deleteEventShow(event.id, event.title, moment(event.start).format("YYYY-MM-DD"), moment(event.end).format("YYYY-MM-DD"));
                        });
                    },
                    //Random default events                   
                    editable: true,
                    events: events,
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    drop: function (date, allDay) { // this function is called when something is dropped

                        var originalEventObject = $(this).data('eventObject');                       
                        var copiedEventObject = $.extend({}, originalEventObject);

                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.backgroundColor = $(this).css("background-color");
                        copiedEventObject.borderColor = $(this).css("border-color");


                        var m = $.fullCalendar.moment(date.format());
                        var start = m._i;
                        var end = start;
                        var shiftId = $("#work_shift").val();
                        var nextdayId = '';
                        if (team === 'PE') {
                            nextdayId = $("#work_shift_nextday").val();
                            if (nextdayId === "") {
                                $("#work_shift_nextday").focus();
                                $("#infoNextday").modal('show');
                                return;
                            }
                        }
                        //return;
                        if (shiftId === "All") {
                            $("#work_shift").focus();
                            $("#info").modal('show');
                            return;
                        }
                        var data = {
                            action: 'new',
                            event_id: '',
                            uid: $(this).attr('id'),
                            shiftId: shiftId,
                            nextdayId: nextdayId,
                            start: start,
                            end: start,
                            backgroundColor: $(this).css("background-color"),
                            borderColor: $(this).css("border-color"),
                            team: team
                        };
                        $.ajax({
                            url: 'setting/php/calendar_process.php',
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function (response) {
                                copiedEventObject.id = response.event_id;
                                copiedEventObject.uid = $(this).attr('id');
                            },
                            error: function (e) {
                                console.log(e.responseText);
                            }
                        });
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    },
                    eventResize: function (event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view) {
                        //var shiftId = $("#work_shift").val();
                        var start = event.start.format("YYYY-MM-DD");
                        var end = event.end;
                        if (end === null) {
                            end = start;
                        } else {
                            end = event.end.format("YYYY-MM-DD");
                        }
                        var data = {
                            action: 'resetdate',
                            event_id: event.id,
                            //shiftId: shiftId,
                            start: start,
                            end: end
                        };
                        $.ajax({
                            url: 'setting/php/calendar_process.php',
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function (response) {
                                if (response.success === false)
                                    revertFunc();
                            },
                            error: function (e) {
                                revertFunc();
                                alert('Error processing your request: ' + e.responseText);
                            }
                        });

                    },
                    eventDrop: function (event, delta, revertFunc) {

                        var shiftId = $("#work_shift").val();
                        var nextdayId = $("#work_shift_nextday").val();
                        var start = event.start.format("YYYY-MM-DD");
                        var end = event.end;
                        if (end === null) {
                            end = start;
                        } else {
                            end = event.end.format("YYYY-MM-DD");
                        }
                        var data = {
                            action: 'resetdate',
                            event_id: event.id,
                            uid: event.uid,
                            shiftId: shiftId,
                            nextdayId: nextdayId,
                            start: start,
                            end: end,
                            team: team
                        };
                        $.ajax({
                            url: 'setting/php/calendar_process.php',
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function (response) {
                                if (response.success === false)
                                    revertFunc();

                                var shiftId = $("#work_shift").val();
                                var events = JSClass.getCalender(shiftId, team);
                                $('#calendar').fullCalendar('removeEvents');
                                $('#calendar').fullCalendar('addEventSource', events);
                                $('#calendar').fullCalendar('rerenderEvents');

                            },
                            error: function (e) {
                                revertFunc();
                                console.log('Error processing your request: ' + e.responseText);
                            }
                        });
                    },
                    dayRender: function (date, cell) {

                        if (moment(date).format("dddd") === "Saturday") {
                            $(cell).removeClass('ui-widget-content');
                            $(cell).addClass('fc-sat');

                        } else if (moment(date).format("dddd") === "Sunday") {
                            $(cell).removeClass('ui-widget-content');
                            $(cell).addClass('fc-sun');

                        }
                    }

                });
            });


        </script>



    </body>
</html>