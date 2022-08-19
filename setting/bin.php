<?php
session_start();
include_once('../libs/connect/connect.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="../libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <!-- Ionicons -->    
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="../libs/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="../libs/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue sidebar-mini fixed">
        <!-- Site wrapper -->
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
                        <i class="fa fa-trash-o"></i>
                        Bin
                        <small>setting</small>
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div id="col-detail" class="col-lg-10">                        
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title"></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table id="dataTable" class="table table-bordered table-hover">                                        
                                        <thead>
                                            <tr>
                                                <th style="width: 10px" class="nosort">#</th>
                                                <th class="text-center">Date</th>
                                                <th >Name</th>
                                                <th class="text-center">Start</th>
                                                <th class="text-center">Stop</th>
                                                <th>Reason</th>
                                                <th>Note</th>
                                                <th class="text-center">Start IP</th> 
                                                <th class="text-center">Stop IP</th> 
                                                <th class="text-center nosort" style="width: 100px"></th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . " a.stamp_id as id, concat(b.Name ,' (',b.NickName,')') as stamp_uid,"
                                                    . " DATE_FORMAT(a.stamp_date,'%d/%m/%Y') as stamp_date,"
                                                    . " if(a.stamp_start='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_start,'%H:%i:%s')) as stamp_start ,"
                                                    . " if(a.stamp_stop='0000-00-00 00:00:00','',DATE_FORMAT(a.stamp_stop,'%H:%i:%s')) as stamp_stop,"
                                                    . " a.stamp_stop_ip, d.reason_name as reason_id,a.stamp_start_ip ,a.stamp_note"
                                                    . " FROM bz_timestamp.t_stamp a "
                                                    . " LEFT JOIN baezenic_people.t_people b "
                                                    . " ON CONVERT(b.id USING utf8) = CONVERT(a.stamp_uid USING utf8)"
                                                    . " LEFT JOIN bz_timestamp.t_reason d ON d.reason_id=a.reason_id"
                                                    . ",(SELECT @rownum := 0) r"
                                                    . " WHERE a.is_delete=1";
                                            $result = $mysqli->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                ?>

                                                <tr>
                                                    <td  ><?php echo $row['rownum'] ?></td>
                                                    <td class="text-center"><?php echo $row['stamp_date'] ?></td>
                                                    <td><?php echo $row['stamp_uid'] ?></td>                                                    
                                                    <td class="text-center"><?php echo $row['stamp_start'] ?></td>
                                                    <td class="text-center"><?php echo $row['stamp_stop'] ?></td>
                                                    <td ><?php echo $row['reason_id'] ?></td>
                                                    <td ><?php echo $row['stamp_note'] ?></td>
                                                    <td class="text-center"><?php echo $row['stamp_start_ip'] ?></td>
                                                    <td class="text-center"><?php echo $row['stamp_stop_ip'] ?></td>  
                                                    <td class="text-center">
                                                        <div class="checkbox" style="margin:0px">
                                                            <label>
                                                                <input type="checkbox" class="minimal checkbox-bin" id="checkbox[<?php echo $row['id']; ?>]" name="checkbox1" value="<?php echo $row['id']; ?>" />                                                            
                                                            </label>
                                                        </div>                                                         
                                                    </td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8" ></td>
                                                <td class="text-center" colspan="2">
                                                    <a class="btn btn-xs btn-success btn-bin" href="javascript:void(0);"  data-action="reuse" >
                                                        <i class="fa fa-reply" title="reuse"></i>  Reuse
                                                    </a> 
                                                    <a class="btn btn-xs btn-danger btn-bin" href="javascript:void(0);" data-action="del">
                                                        <i class="fa fa fa-trash-o"></i> del 
                                                    </a>                                                    
                                                </td>

                                            </tr>
                                        </tfoot>
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

        <!-- jQuery 2.1.4 -->
        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>  
        <script src="../libs/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../libs/dist/js/app.min.js" type="text/javascript"></script> 
        <script src="../libs/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../libs/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
        <script src="script/login.js" type="text/javascript"></script>
        <script src="script/main.js" type="text/javascript"></script>

    </body>
</html>