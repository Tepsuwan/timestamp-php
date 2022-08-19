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
$date_now = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>BZ Timestamp</title>  
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>  
        <link href="libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="libs/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/> 
        <link href="handsontable/0.24.3/dist/handsontable.full.css" rel="stylesheet" media="screen">
        <link href="css/custom.css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->  
    </head>
    <body>
        <?php include './libs/dist/php/menu_fontpage.php'; ?>        
        <div class="container">
            <div class="col-md-12">
                <div class="row " >
                    <div class="col-md-12" style="margin-bottom: -10px" >                        
                        <div class="box box-warning">
                            <div class="box-body" style="padding:0 10px 0 10px ">
                                <div class="box-header">                                    
                                </div>
                                <table class="table">
                                    <tbody>
                                        <tr>  
                                            <th rowspan="2" style="padding-top: 0" >
                                                <div class="box-time">
                                                    <table>
                                                        <tr>
                                                            <td><h2 class="time"></h2></td>
                                                        </tr>
                                                        <tr>
                                                            <td><div class="dayText text-right time-text-size"></div></td>
                                                        </tr>
                                                        <tr>
                                                            <td><div class="month text-right time-text-size"></div></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </th>
                                            <th style="width: 25%;background:rgb(243, 156, 18);border-top-left-radius: 5px">NAME</th>
                                            <th style="width: 28%;background:rgb(243, 156, 18);">WORK SHIFTS (เวลาทำงาน)</th> 
                                            <th style="width: 20%;background:rgb(243, 156, 18);">TEAM</th>
                                            <th style="width: 20%;background:rgb(243, 156, 18);">EMAIL</th>
                                            <th style="width: 20%;background:rgb(243, 156, 18);">OFFICE</th>
                                        </tr>
                                        <?php
                                        $days = date('l', strtotime($date_now));
                                        $sql = "SELECT if(x1.staff_work_shift is null,x2.staff_work_shift,x1.staff_work_shift) as staff_work_shift ";
                                        $sql .= "FROM t_extra_dayshift a ";
                                        $sql .= "INNER JOIN t_work_shift b ON a.work_shift_id=b.work_shift_id ";
                                        $sql .= "LEFT JOIN( ";
                                        $sql .= "SELECT a.uid, concat(b.work_shift_start,\" - \",b.work_shift_stop) as staff_work_shift ";
                                        $sql .= "FROM t_extra_dayshift a ";
                                        $sql .= "INNER JOIN t_work_shift b ON a.work_shift_id=b.work_shift_id ";
                                        $sql .= "WHERE a.uid='$userId' and a.status=0 and date='$date_now' order by a.date ";
                                        $sql .= ") as x1 on x1.uid=a.uid ";
                                        $sql .= "LEFT JOIN( ";
                                        $sql .= "SELECT a.uid, concat(b.work_shift_start,\" - \",b.work_shift_stop) as staff_work_shift ";
                                        $sql .= "FROM t_extra_dayshift a ";
                                        $sql .= "INNER JOIN t_work_shift b ON a.work_shift_id=b.work_shift_id ";
                                        $sql .= "WHERE a.uid='$userId' and a.status=0 and days='$days' order by a.date ";
                                        $sql .= ") as x2 on x2.uid=a.uid ";
                                        $sql .= "WHERE a.uid='$userId' and a.status=0 group by a.uid";
                                        $result = $mysqli->query($sql);
                                        $num_rows = $result->num_rows;
                                        $work_shift = "";
                                        if ($num_rows > 0) {
                                            $fetch = $result->fetch_assoc();
                                            $work_shift = $fetch['staff_work_shift'];
                                        }

                                        if ($work_shift === "") {
                                            $sql = "SELECT "
                                                    . " CASE c.work_shift_start"
                                                    . " WHEN 'none' then '-'"
                                                    . " WHEN 'OT' then '-'"
                                                    . " ELSE concat(c.work_shift_start,'-', c.work_shift_stop)"
                                                    . " END as staff_work_shift,c.work_shift_id"
                                                    . " FROM bz_timestamp.t_calendar a"
                                                    . " LEFT JOIN bz_timestamp.t_employee_time b ON b.work_shift_id=a.work_shift_id"
                                                    . " LEFT JOIN bz_timestamp.t_work_shift c ON c.work_shift_id=a.work_shift_id"
                                                    . " WHERE a.uid='$userId'"
                                                    . " AND DATE_FORMAT(a.calendar_date_start,'%Y-%m-%d')<='$date_now' "
                                                    . " AND DATE_FORMAT(a.calendar_date_end,'%Y-%m-%d')>='$date_now' ";

                                            $result = $mysqli->query($sql);
                                            $num_rows = $result->num_rows;
                                            $work_shift = "";
                                            if ($num_rows > 0) {
                                                $fetch = $result->fetch_assoc();
                                                $work_shift = $fetch['staff_work_shift'];
                                                $work_shift_id = $fetch['work_shift_id'];
                                            }
                                        }
                                        $sql = "SELECT "
                                                . " p.id,concat(p.titlename,' ',p.Name,' (',p.NickName,')') as fname,"
                                                . " p.NickName,p.Office,p.Email,p.Main_Team as Team,p.Office,"
                                                . " CASE c.work_shift_start"
                                                . " WHEN 'none' then '-'"
                                                . " WHEN 'OT' then '-'"
                                                . " ELSE concat(c.work_shift_start,'-', c.work_shift_stop)"
                                                . " END as staff_work_shift"
                                                . " FROM baezenic_people.t_people p "
                                                . " LEFT JOIN bz_timestamp.t_employee_time b ON b.uid=p.id"
                                                . " LEFT JOIN bz_timestamp.t_work_shift c ON c.work_shift_id=b.work_shift_id"
                                                . " WHERE p.status<>'Y' "
                                                . " AND p.id='" . $userId . "'";
                                        $rs = $mysqli->query($sql);
                                        $row = $rs->fetch_assoc();
                                        if ($work_shift == "") {
                                            $work_shift = $row['staff_work_shift'];
                                        }
                                        ?>
                                        <tr> 
                                            <td style="padding: 15px 8px;"><label><?php echo $row['fname']; ?></label></td>
                                            <td style="padding: 15px 8px;">
                                                <label> <?php echo $work_shift; ?></label>
                                                <!--                                                <div class="form-group">                                                   
                                                                                                    <select class="form-control" id="workshifts"> </select>
                                                                                                </div>-->
                                            </td>
                                            <td style="padding: 15px 8px;"><label><?php echo $row['Team']; ?></label></td>
                                            <td style="padding: 15px 8px;"><label><?php echo $row['Email']; ?></label></td>
                                            <td style="padding: 15px 8px;"><label><?php echo $row['Office']; ?></label></td>  
                                        </tr>                                
                                    </tbody>
                                </table>                        
                            </div><!-- /.box-body -->
                        </div><!-- /.box -->
                    </div>
                </div>                         
                <div class="row " style="margin-bottom: 10px">
                    <div id="box_st" class="col-md-12" >                        
                    </div>
                </div>
                <div class="row ">                    
                    <div class="col-md-12">
                        <!-- Block buttons -->
                        <div class="box ">
                            <div class="box-header" style="padding:0">                            
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="txt_fdate" class="col-sm-1 control-label" style="width: 50px">From</label>
                                        <div class="col-xs-2" >                                        
                                            <input type="text" class="form-control" name="txt_fdate" id="txt_fdate" value="<?php echo $fromDate; ?>" >
                                        </div>
                                        <label for="txt_tdate" class="col-sm-1 control-label" style="width: 20px">To</label>
                                        <div class="col-xs-2">
                                            <input type="text" class="form-control" name="txt_tdate" id="txt_tdate" value="<?php echo $toDate; ?>"  >
                                        </div> 
                                        <div class="col-xs-1">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="today" id="today"> Today
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="monthly" id="monthly" checked=""> Monthly
                                                </label>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="nav-tabs-custom" style="cursor: move;margin-bottom:0">
                                <ul id="nav-tabs-photos" class="nav nav-tabs ui-sortable-handle">
                                    <li class="pull-left header"><i class="fa  fa-file-excel-o text-green"></i></li>                                    
                                    <li id="normal" class="active tab-manu">
                                        <a href="#dz-photos" data-toggle="tab" aria-expanded="false" data-id="0"><i class="fa text-green fa-user"></i> <?php echo $row['fname']; ?></a>
                                    </li>
                                    <li id="logon" class="tab-manu"><a href="#dz-photos" data-toggle="tab" aria-expanded="false" data-id="1"><i class="fa text-green fa-user"></i> Online</a></li>
                                    <li id="holidays" class="tab-manu"><a href="#dz-photos" data-toggle="tab" aria-expanded="false" data-id="1"><i class="fa text-orange fa-sun-o"></i> Holidays (<?php echo date('Y'); ?>)</a></li>
                                    <li class="pull-right header-custom"><small class="console">Changes will be autosaved</small></li>   
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="chart tab-pane active " id="dz-photos" style="position: relative;">
                                    <div id="data-hot" style="overflow:auto;height:500px" class="handsontable"></div>             
                                </div>
                            </div>

                        </div><!-- /.box -->
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
        <div id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p><h1>Do you want to stop work?</h1></p>
                        <p class="text-warning"><small>If you don't Ok, your changes will be lost.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="btn_stop" class="btn btn-primary">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-loading">
            <div class="forget" >                
            </div>            
        </div>

        <div class="box-loading-top">
            <div class="saveing" >
                <p>Saveing.....</p>
            </div>
            <div class="loading" >
                <p>Loading.....</p>
            </div> 
            <div class="error-stop" >
                <p>error-stop.</p>
            </div>
        </div>

        <input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>">
        <input type="hidden" name="role_key" id="role_key" value="<?php echo $_SESSION['role_key']; ?>">
        <input type="hidden" name="work_shift" id="work_shift" value="<?php echo $_SESSION['work_shift_id']; ?>">
        <input type="hidden" name="page" id="page" value="stamp"> 

        <div id="reasonModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Information</h4>
                    </div>
                    <div class="modal-body reason-body">                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                       
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="assets/plugins/waitingdialog/waitingDialog.js" type="text/javascript"></script>
        <script src="socket.io.js" type="text/javascript"></script>
        <script src="js/nodeJS.js"></script>
        <script src="js/JSClass.js?d=<?php echo date('Ymdhis') ?>"></script>        
        <script src="js/login.js"></script>
        <script src="js/stamp.js?d=<?php echo date('Ymdhis') ?>"></script>
        <script src="handsontable/0.24.3/dist/handsontable.full.js"></script>  
        <script src="js/hotStamp.js"></script>

    </body>
</html>