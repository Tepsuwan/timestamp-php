<?php
session_start();
if (empty($_SESSION['adminID'])) {
    header("Location: index.php");
}
include_once('../../lib/conn.inc.php');

$val = (empty($_GET['val']) ? '' : $_GET['val']);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin | User setting</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="../css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <?php include "menu.php"; ?>
        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Deleted
                    <small>setting</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>                        
                    <li class="active">User setting</li>
                </ol>
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
                                <table id="table" class="table table-bordered table-hover">
                                    <div style="width:200px;position: absolute;margin: 0px 10px">

                                        <select class="form-control customer" name="customer" >
                                            <option value="11" <?php if ($val == '11') { ?> selected <?php } ?> >Soafoto</option>
                                            <option value="18" <?php if ($val == '18') { ?> selected <?php } ?>>Dirdal foto</option>
                                            <option value="19" <?php if ($val == '19') { ?> selected <?php } ?>>Inviso</option>
                                            <option value="25" <?php if ($val == '25') { ?> selected <?php } ?>>Zentuvo</option>
                                            <option value="27" <?php if ($val == '27') { ?> selected <?php } ?>>Care</option>
                                            <option value="29" <?php if ($val == '29') { ?> selected <?php } ?>>Semeraro</option>
                                        </select>
                                    </div>
                                    <thead>
                                        <tr>
                                            <th style="width: 10px" class="nosort">#</th>
                                            <th>Job Name</th>
                                            <th style="width: 60px" class="nosort">Photo in</th>
                                            <th style="width: 65px" class="nosort">Photo out</th>
                                            <th>Ordered</th>
                                            <th>Start</th>
                                            <th>Operator</th>
                                            <th>Delete</th>                                            
                                            <th class="text-center nosort" style="width: 100px"></th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($val == '11' || $val == '18' || $val == '') {
                                            if ($val == '') {
                                                $val = 11;
                                            }
                                            $tbname = 'PhotoOperator';
                                            $field = 'PE_ID';
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "PE_ID as id, Job_Name as job_name, Photo_IN as pin, Photo_OUT as pout,"
                                                    . " DateUploaded as dateupload,OperatorStart as start, b.NickName as operator,c.NickName as delete_user"
                                                    . " FROM PhotoOperator a "
                                                    . " LEFT JOIN baezenic_people.t_people b ON a.Opertor=b.id"
                                                    . " LEFT JOIN baezenic_people.t_people c ON a.delete_user=c.id"
                                                    . ",(SELECT @rownum := 0) r"
                                                    . " WHERE a.isDel='true' AND DirectoryId='$val'";
                                        } else if ($val == '19') {
                                            $tbname = 'Inviso';
                                            $field = 'inviso_id';
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "inviso_id as id, job_name as job_name, photo_in as pin, photo_out as pout,"
                                                    . "dateupload as dateupload,operator_start as start, b.NickName as operator,"
                                                    . "c.NickName as delete_user"
                                                    . " FROM Inviso a "
                                                    . " LEFT JOIN baezenic_people.t_people b ON a.operator=b.id"
                                                    . " LEFT JOIN baezenic_people.t_people c ON a.delete_user=c.id"
                                                    . ",(SELECT @rownum := 0) r"
                                                    . " WHERE a.isDel='true'";
                                        } else if ($val == '25') {
                                            $tbname = 'Zentuvo';
                                            $field = 'zentuvo_id';
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "zentuvo_id as id, tour_id as job_name, photo_in as pin, photo_out as pout,"
                                                    . " dateupload as dateupload,operator_start as start, b.NickName as operator,"
                                                    . "c.NickName as delete_user"
                                                    . " FROM Zentuvo a "
                                                    . " LEFT JOIN baezenic_people.t_people b ON a.operator=b.id"
                                                    . " LEFT JOIN baezenic_people.t_people c ON a.delete_user=c.id"
                                                    . ",(SELECT @rownum := 0) r"
                                                    . " WHERE a.isDel='true'";
                                        } else if ($val == '27') {
                                            $tbname = 't_case';
                                            $field = 'case_id';
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "case_id as id, job_name as job_name, photo_in as pin, photo_out as pout,"
                                                    . " date_uploaded as dateupload,operator_start as start, b.NickName as operator,"
                                                    . "c.NickName as delete_user"
                                                    . " FROM t_case a "
                                                    . " LEFT JOIN baezenic_people.t_people b ON a.operator=b.id"
                                                    . " LEFT JOIN baezenic_people.t_people c ON a.delete_user=c.id"
                                                    . ",(SELECT @rownum := 0) r"
                                                    . " WHERE a.isDel='true'";
                                        } else if ($val == '29') {
                                            $tbname = 'semeraro';
                                            $field = 'semeraro_id';
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "semeraro_id as id, job_name as job_name, photo_in as pin, photo_out as pout,"
                                                    . " date_uploaded as dateupload,operator_start as start, b.NickName as operator,"
                                                    . "c.NickName as delete_user"
                                                    . " FROM semeraro a "
                                                    . " LEFT JOIN baezenic_people.t_people b ON a.operator=b.id"
                                                    . " LEFT JOIN baezenic_people.t_people c ON a.delete_user=c.id"
                                                    . ",(SELECT @rownum := 0) r"
                                                    . " WHERE a.isDel='true'";
                                        }
                                        $result = $mysqli->query($sql);
                                        while ($row = $result->fetch_assoc()) {
                                            ?>

                                            <tr>
                                                <td  ><?php echo $row['rownum'] ?></td>
                                                <td><?php echo $row['job_name'] ?></td>
                                                <td class="text-center"><?php echo $row['pin'] ?></td>
                                                <td class="text-center"><?php echo $row['pout'] ?></td>
                                                <td class="text-center"><?php echo $row['dateupload'] ?></td>
                                                <td class="text-center"><?php echo $row['start'] ?></td>
                                                <td ><?php echo $row['operator'] ?></td>
                                                <td ><?php echo $row['delete_user'] ?></td>                                                
                                                <td class="text-center">
                                                    <div class="checkbox" style="margin:0px">
                                                        <label>
                                                            <input type="checkbox" class="minimal del" id="del[<?php echo $row['id']; ?>]" name="checkbox1" value="<?php echo $row['id']; ?>" />                                                            
                                                        </label>
                                                    </div>                                                         
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="8" ></td>
                                            <td class="text-center">
                                                <a class="btn btn-xs btn-success btn-reuse" href="javascript:void(0);"  data-table="<?php echo $tbname; ?>" data-field="<?php echo $field; ?>" >
                                                    <i class="fa fa-reply" title="reuse"></i>  Reuse
                                                </a> 
                                                <a class="btn btn-xs btn-danger delete" href="javascript:void(0);" data-id="<?php echo $row['id']; ?>" data-table="<?php echo $tbname; ?>" data-field="<?php echo $field; ?>">
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
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->


    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>    
    <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../js/AdminLTE/demo.js" type="text/javascript"></script>
    <script src="../js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="../script/check_delete.js" type="text/javascript"></script>


</body>
</html>
