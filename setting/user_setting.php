<?php
session_start();

include_once('../libs/connect/connect.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
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
                        <i class="fa fa-users"></i> User 
                        <small>setting</small>                        
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div id="col-detail" class="col-xs-12 col-md-8">                        
                            <div class="box">                                
                                <div class="box-body">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px" class="nosort">#</th>
                                                <th style="width: 10px"></th>
                                                <th>Name</th>
                                                <th style="width: 100px">Work shift</th>
                                                <th class="text-center nosort" style="width: 40px" title="xxx">Leader</th>
                                                <th class="text-center nosort" style="width: 100px">Administartor</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "p.id,CONCAT(p.titlename,' ',Name,' ( ',p.NickName,' )') as Name,u.admin,u.work_shift_id,u.is_leader"
                                                    . " FROM baezenic_people.t_people p "
                                                    . " LEFT JOIN bz_timestamp.t_user_setting u ON u.uid=p.id"
                                                    . " ,(SELECT @rownum := 0) r"
                                                    . " WHERE status<>'Y' AND Office!='Vietnam'"
                                                    . " ORDER BY p.id ASC";
                                            $result = $mysqli->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                $shift_id = $row['work_shift_id'];
                                                $is_leader = $row['is_leader'];
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $row['rownum']; ?></td>
                                                    <td><?php echo $row['id']; ?></td> 
                                                    <td><?php echo $row['Name']; ?></td> 
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="work_shift" class="work_shift" data-uid="<?php echo $row['id']; ?>">
                                                                <?php
                                                                $sql = "SELECT work_shift_id, if(work_shift_start='none',work_shift_start,concat(work_shift_start,'-', work_shift_stop)) as staff_work_shift"
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
                                                        <a href="work_shift.php">
                                                            <span class="label label-default"><i class="fa fa-plus-square"></i></span>
                                                        </a>
                                                    </td> 
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="leader" class="leader" data-uid="<?php echo $row['id']; ?>">
                                                                <?php
                                                                $sql = "SELECT distinct Team"
                                                                        . " FROM baezenic_people.t_people"
                                                                        . " WHERE 1 AND status<>'Y'";
                                                                $result3 = $mysqli->query($sql);
                                                                while ($row3 = $result3->fetch_assoc()) {
                                                                    ?>
                                                                    <option value="<?php echo $row3['Team']; ?>" <?php if ($row3['Team'] == $is_leader) { ?> selected="" <?php } ?> >
                                                                        <?php echo $row3['Team']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>                                                        
                                                    </td>  
                                                    <td class="text-center">
                                                        <div class="checkbox" style="margin:0px">
                                                            <label>
                                                                <input type="checkbox" class="minimal checkbox-checked"  data-prop="admin" name="checkbox1" value="<?php echo $row['id']; ?>" <?php
                                                                       if ($row['admin'] == '1') {
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