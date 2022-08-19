<?php
session_start();

include_once('../libs/connect/connect.php');
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
        <link href="../libs/plugins/fullcalendar.css" rel="stylesheet" type="text/css" />
        <link href="../libs/plugins/calendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />
        <!-- Theme style -->
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />      
        <link href="../libs/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

        <link href="../libs/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <style>
            .fc-header{
                margin: 15px;
                width: 97% !important;
            }
        </style>
        

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
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <h4 class="box-title">Draggable Events</h4>
                                </div>
                                <div class="box-body">
                                    <!-- the events -->
                                    <div id='external-events'>
                                        <div class='external-event bg-teal'>Lunch</div>
                                        <div class='external-event bg-yellow'>Go home</div>
                                        <div class='external-event bg-aqua'>Do homework</div>
                                        <div class='external-event bg-light-blue'>Work on UI design</div>
                                        <div class='external-event bg-maroon'>Sleep tight</div>
                                        <!--bg-fuchsia,bg-gray-->
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


        <!-- jQuery 2.1.4 -->
        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>  

        <script src="../libs/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../libs/dist/js/app.min.js" type="text/javascript"></script> 
        <script src="../libs/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../libs/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

        <!-- jQuery UI-->      
        <script src="../libs/plugins/calendar/moment.min.js" type="text/javascript"></script>
        <script src="../libs/plugins/calendar/jquery-ui.custom.min.js" type="text/javascript"></script>        
        <script src="../libs/plugins/fullcalendar.min.js" type="text/javascript"></script>

        <script src="script/login.js" type="text/javascript"></script>
        <script src="script/main.js" type="text/javascript"></script>

        <!-- Page specific script -->
        <script type="text/javascript">
            $(function () {

                JSClass.calendarUser();

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
                            alert("Event click");
//                            $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
//                            $("#endTime").html(moment(event.end).format('MMM Do h:mm A'));
//                            $("#eventInfo").html(event.description);
//                            $("#eventLink").attr('href', event.url);
//                            $("#eventContent").dialog({modal: true, title: event.title, width: 350});
                        });
                    },
                    //Random default events                   
                    editable: true,
                    events: [
                        {
                            title: 'All Day Event',
                            start: new Date(y, m, 1),
                            backgroundColor: "#f56954", //red
                            borderColor: "#f56954" //red

                        },
                        {
                            title: 'Long Event',
                            start: new Date(y, m, d - 5),
                            end: new Date(y, m, d - 2),
                            backgroundColor: "#f39c12", //yellow
                            borderColor: "#f39c12", //yellow
                            editable: true,
                            overlap: true
                        },
                        {
                            title: 'Meeting',
                            start: new Date(y, m, d, 07, 30),
                            allDay: false,
                            backgroundColor: "#0073b7", //Blue
                            borderColor: "#0073b7" //Blue
                        },
                        {
                            title: 'Lunch',
                            start: new Date(y, m, d, 08, 0),
                            end: new Date(y, m, d, 17, 0),
                            allDay: false,
                            backgroundColor: "#00c0ef", //Info (aqua)
                            borderColor: "#00c0ef" //Info (aqua)
                        },
                        {
                            title: 'Click for Google',
                            start: new Date(y, m, 28),
                            end: new Date(y, m, 28),
                            url: 'http://google.com/',
                            backgroundColor: "#3c8dbc", //Primary (light-blue)
                            borderColor: "#3c8dbc" //Primary (light-blue)
                        }
                    ],
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

                        console.log(copiedEventObject);

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);


                    },
                    eventResizeStart: function (event, jsEvent, ui, view) {
                        console.log('RESIZE START ' + event.title);
                        event.changing = true;
                    },
                    eventResizeStop: function (event, jsEvent, ui, view) {
                        console.log('RESIZE STOP ' + event.title);

                    },
                    eventResize: function (event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view) {
                        console.log('RESIZE!! ' + event.title);
                        console.log(dayDelta + ' days'); //this will give the number of days you extended the event
                        console.log(minuteDelta + ' minutes');

                    },
                    eventDrop: function (event, delta, revertFunc) {

                        console.log('eventDrop', event.title + " was dropped on " + event.start, event.end);
//                        var DATA = {
//                            "event_title": event.title,
//                            "event_id": event.id,
//                            "event_date": event.start,
//                            "dayDelta": dayDelta,
//                            "minuteDelta": minuteDelta,
//                            "allDay": allDay
//                        };
//                        $.ajax({
//                            type: "POST",
//                            url: "move",
//                            data: DATA,
//                            cache: false,
//                            success: function (data) {
//                                //to do in case of success
//                            }
//                        });

                    }
                });
            });
        </script>

    </body>
</html>