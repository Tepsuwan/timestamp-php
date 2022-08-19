<?php
session_start();
if (empty($_SESSION['role_key'])) {
    echo "<script>window.location='index.php'</script>";
}
include_once('../libs/connect/connect.php');
$team = $_GET['t'];
if ($team == 'FP') {
    $work_shift_id = "55c871499df11"; //14:00
} else {
    $work_shift_id = "55bf1082af74b"; //07:00-16:00
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Admin | Calendar</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="../libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <!-- Ionicons -->    
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar 2.2.5-->
        <link href="../libs/plugins/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <link href="../libs/plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />
        <!-- Theme style -->
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />      
        <link href="../libs/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />


        <link href="css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->



    </head>
    <body class="skin-blue sidebar-mini">
        <div class="wrapper">
            <!-- =============================================== -->
            <?php include '../libs/dist/php/header.php'; ?>

            <?php include '../libs/dist/php/menu.php'; ?>

            <!-- =============================================== -->
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Calendar
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Calendar</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <div class="box-header with-border">
                                                <h4 class="box-title"><b>Work shift</b></h4>
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" name="work_shift" id="work_shift">                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Draggable<kbd class="console"></kbd></h4>
                                </div>
                                <div class="box-body">
                                    <!-- the events -->                                    
                                    <div id='external-events'>                                        
                                    </div>
                                </div><!-- /.box-body -->
                            </div><!-- /. box -->                            
                        </div><!-- /.col -->
                        <div class="col-md-9">
                            <div class="box box-primary">
                                <div class="box-body no-padding">
                                    <!-- THE CALENDAR -->
                                    <div id="calendar"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /. box -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->


            <!-- =============================================== -->
            <?php include '../libs/dist/php/footer.php'; ?>
            <!-- =============================================== -->


        </div><!-- ./wrapper -->
        <div id="info" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Information</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Please choose <code>Work Shift</code>?</h4>                       
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
                        <h4 class="modal-title">Confirmation to delete</h4>
                    </div>
                    <div class="modal-body text-info-del">
                        <p class="text-info-del"></p>
                        <p class="text-warning"><small>If you don't Delete, your changes will be lost.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-modal-del">OK</button>                      
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="team" id="team" value="<?php echo $team; ?>">
        <!-- jQuery 2.1.4 -->
        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>  

        <script src="../libs/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../libs/dist/js/app.min.js" type="text/javascript"></script> 
        <script src="../libs/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>


        <!-- jQuery UI 1.11.4 -->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
        <script src="../libs/plugins/moment.min.js" type="text/javascript"></script>               
        <script src="../libs/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="script/login.js" type="text/javascript"></script>
        <script src="script/main.js" type="text/javascript"></script>

        <!-- Page specific script -->
        <script type="text/javascript">
            $(function () {

                var team = $("#team").val();
                JSClass.calendarUser(team, '<?php echo $work_shift_id; ?>');
                JSClass.setWorkshift('<?php echo $work_shift_id; ?>');
                JSClass.getWorkshift('<?php echo $work_shift_id; ?>');
                var events = JSClass.getCalender('<?php echo $work_shift_id; ?>', team);
                /* initialize the external events
                 -----------------------------------------------------------------*/
                function ini_events(ele) {

                    ele.each(function () {
                        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                        // it doesn't need to have a start or end
                        var eventObject = {
                            title: $.trim($(this).text()) // use the element's text as the event title
                        };
                        // store the Event Object in the DOM element so we can get to it later
                        $(this).data('eventObject', eventObject);
                        // make the event draggable using jQuery UI  
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
                //Date for the calendar events (dummy data)
                var date = new Date();
                var d = date.getDate(),
                        m = date.getMonth(),
                        y = date.getFullYear();
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
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
                            JSClass.deleteEventShow(event.id, event.title, moment(event.start).format("YYYY-MM-DD"), moment(event.end).format("YYYY-MM-DD"));

                        });
                    },
                    //Random default events                   
                    editable: true,
                    events: events,
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    drop: function (date, allDay) { // this function is called when something is dropped
                        // retrieve the dropped element's stored Event Object
                        var originalEventObject = $(this).data('eventObject');
                        // we need to copy it, so that multiple events don't have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);
                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;
                        copiedEventObject.backgroundColor = $(this).css("background-color");
                        copiedEventObject.borderColor = $(this).css("border-color");

                        var m = $.fullCalendar.moment(date.format());
                        var start = m._i;
                        var end = start;
                        var shiftId = $("#work_shift").val();
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
                            start: start,
                            end: start,
                            backgroundColor: $(this).css("background-color"),
                            borderColor: $(this).css("border-color"),
                            team: team
                        };
                        $.ajax({
                            url: 'php/calendar_process.php',
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            cache: false,
                            async: false,
                            success: function (response) {
                                copiedEventObject.id = response.event_id;
                            },
                            error: function (e) {
                                console.log(e.responseText);
                            }
                        });
                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
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
                            url: 'php/calendar_process.php',
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

                        // var shiftId = $("#work_shift").val();
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
                            end: end,
                            team: team
                        };
                        $.ajax({
                            url: 'php/calendar_process.php',
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
                    eventDragStop: function (event, jsEvent, ui, view) {

                    }
                });

            });


        </script>

    </body>
</html>