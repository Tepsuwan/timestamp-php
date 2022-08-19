<?php
session_start();
//if (empty($_SESSION['role_key'])) {
//    echo "<script>";
//    echo "window.location = '../login';";
//    echo "</script>";
//}


include_once('../libs/connect/connect.php');
$isLeader = (empty($_SESSION['isLeader']) ? '' : $_SESSION['isLeader']);
$isAdmin = (empty($_SESSION['isAdmin']) ? '' : $_SESSION['isAdmin']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Admin</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

        <link href="../libs/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="../libs/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="../libs/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../libs/plugins/bootstrap-select/dist/css/bootstrap-select.css"/>

        <link href="css/custom.css" rel="stylesheet" type="text/css"/>
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="skin-blue sidebar-mini fixed">
        <!-- Site wrapper -->
        <div class="wrapper">
            <?php include '../libs/dist/php/header.php'; ?>
            <?php include '../libs/dist/php/menu.php'; ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <i class="fa fa-users"></i> Staff
                        <small>setting</small>
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div id="col-detail" class="col-xs-12 col-md-6" style="min-width: 600px">
                            <div class="box">
                                <div class="box-body">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px" class="nosort">#</th>
                                                <th style="width: 50px"></th>
                                                <th>Name</th>
                                                <th class="text-center">Team</th>
                                                <th style="width: 80px" class="nosort text-center">Work shift</th>
                                                <th class="nosort text-center">Extra</th>
                                                <th style="width: 30px" class="nosort text-center">Access</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "p.id,CONCAT(p.titlename,' ',Name,' ( ',p.NickName,' )') as Name,u.work_shift_id,u.is_operator,p.Main_Team as Team,p.status"
                                                    . " FROM baezenic_people.t_people p "
                                                    . " LEFT JOIN bz_timestamp.t_employee_time u ON u.uid=p.id"
                                                    . " ,(SELECT @rownum := 0) r"
                                                    . " WHERE status<>'Y' AND (sub_office='PTY' or sub_office='BKK' or sub_office='BZID')"
                                                    . " ORDER BY p.id ASC";
                                            $result = $mysqli->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                $shift_id = $row['work_shift_id'];
                                                ?>
                                                <tr >
                                                    <td class="text-center"><?php echo $row['rownum']; ?></td>
                                                    <td class="text-center"><?php echo $row['id']; ?></td>
                                                    <td><?php echo $row['Name']; ?></td>
                                                    <td class="text-center"><?php echo $row['Team']; ?></td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="work_shift" class="work_shift form-control" data-uid="<?php echo $row['id']; ?>" >
                                                                <option value="">---Select---</option>
                                                                <?php
                                                                $sql = "SELECT work_shift_id, "
                                                                        . " CASE work_shift_start"
                                                                        . " WHEN 'none' then 'none'"
                                                                        . " WHEN 'OT' then 'OT'"
                                                                        . " ELSE concat(`work_shift_start`,'-', `work_shift_stop`)"
                                                                        . " END as staff_work_shift"
                                                                        . " FROM t_work_shift WHERE 1 ORDER BY work_shift_start ASC";
                                                                $result2 = $mysqli->query($sql);
                                                                while ($row2 = $result2->fetch_assoc()) {
                                                                    ?>
                                                                    <option value="<?php echo $row2['work_shift_id']; ?>" <?php if ($shift_id == $row2['work_shift_id']) { ?>selected=""<?php } ?>>
                                                                        <?php echo $row2['staff_work_shift']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="text-center"><a href="javascript:void(0);" id="btn_extra" data-uid="<?php echo $row['id']; ?>" class="btn btn-default btn-sm">::</a></td>
                                                    <td class="text-center">
                                                        <div class="checkbox" style="margin:0px">
                                                            <label>
                                                                <input type="checkbox" class="minimal checkbox-checked"  data-prop="is_operator" name="checkbox1" value="<?php echo $row['id']; ?>" <?php
                                                                if ($row['is_operator'] == '1') {
                                                                    echo "checked";
                                                                }
                                                                ?>   />
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                        </div>
                    </div>
                </section><!-- /.content -->


            </div><!-- /.content-wrapper -->

            <!-- =============================================== -->
            <?php include '../libs/dist/php/footer.php'; ?>
            <!-- =============================================== -->


        </div><!-- ./wrapper -->

        <div class="modal fade modal-wide" id="openExtraModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Add Extra Dayshift</h4>
                    </div>
                    <div class="modal-body">

                        <div class="box box-primary" id="box_form" style="display: none">
                            <div class="box-header with-border">
                                <h3 class="box-title">Add Day shift</h3>
                            </div>
                            <form role="form" id="form_data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="text" name="txt_date" id="txt_date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Days</label>
                                        <select name="days[]" id="days" class="form-control selectpicker" data-hide-disabled="true" multiple="multiple" title="------">
                                            <?php
                                            $timestamp = strtotime('next Monday');
                                            $days = array();
                                            for ($i = 0; $i < 7; $i++) {
                                                $days[] = strftime('%A', $timestamp);
                                                echo ' <option>' . strftime('%A', $timestamp) . '</option>';
                                                $timestamp = strtotime('+1 day', $timestamp);
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Work shift</label>
                                        <select name="work_shift_modal" id="work_shift_modal" class="form-control">
                                            <?php
                                            $sql = "SELECT work_shift_id, "
                                                    . " CASE work_shift_start"
                                                    . " WHEN 'none' then 'none'"
                                                    . " WHEN 'OT' then 'OT'"
                                                    . " ELSE concat(`work_shift_start`,'-', `work_shift_stop`)"
                                                    . " END as staff_work_shift"
                                                    . " FROM t_work_shift WHERE 1 ORDER BY work_shift_start ASC";
                                            $result2 = $mysqli->query($sql);
                                            while ($row2 = $result2->fetch_assoc()) {
                                                ?>
                                                <option value="<?php echo $row2['work_shift_id']; ?>" <?php if ($shift_id == $row2['work_shift_id']) { ?>selected=""<?php } ?>>
                                                    <?php echo $row2['staff_work_shift']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="button" id="btn_submit" class="btn btn-primary" data-action="add" data-id="" data-uid="">Submit</button>
                                </div>
                            </form>
                        </div>

                        <div class="row">
                            <div class=" col-sm-12 col-xs-12 col-md-12 col-lg-12">
                                <div class="box">
                                    <div class="box-body">
                                        <table id="dt_table_task" class="table table-bordered table-hover table-striped responsive " cellspacing="0" width="100%">
                                            <thead id="dt_thead">
                                                <tr>
                                                    <th style="width: 120px" class="text-center"><button type="button" data-toggle="tooltip" title=""  data-original-title="Add" class="btn btn-success btn-add btn-xs"><i class="fa fa-plus-circle"></i> Add</button></th>
                                                    <th>Day</th>
                                                    <th>Date</th>
                                                    <th>Time</th>

                                                </tr>
                                            </thead>
                                            <tbody id="dt_tbody">
                                                <tr>
                                                    <td colspan="4">No data in table</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>

        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../libs/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>

        <script src="../libs/dist/js/app.min.js" type="text/javascript"></script>
        <script src="../libs/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../libs/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="../libs/plugins/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
        <script src="../assets/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="script/login.js" type="text/javascript"></script>
        <script src="script/main.js" type="text/javascript"></script>

        <script>
            $(function() {
                $("#txt_date").datepicker({
                    format: 'yyyy-mm-dd',
                    todayHighlight: true,
                    autoclose: true,
                }).on('changeDate', function(e) {
                    $("#days").val("");
                    $('.selectpicker').selectpicker('refresh');
                });
                $("#days").change(function() {
                    $("#txt_date").val("");
                });
            });
        </script>
    </body>
</html>