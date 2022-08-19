<?php
session_start();
include_once('../libs/connect/connect.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin | User</title>
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
        <link href="../bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
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
                        User 
                        <small>setting</small>
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content">

                    <div class="row">
                        <div class="col-md-5" style="min-width: 600px">

                            <div class="box box-solid  box-form" >
                                <div class="box-header">                                
                                    <h3 class="box-title">User Form</h3>
                                </div>                                
                                <div class="box-body">
                                    <form role="form" name="frm" id="frm"   method="post" enctype="multipart/form-data" >
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>User name :</label>                                            
                                        </div> 
                                        <div class="form-group">
                                            <select id="staff" name="staff" class="selectpicker" data-hide-disabled="true" data-live-search="true">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Role :</label>
                                            <select class="form-control" name="role_key" id="role_key">
                                                <option value=""></option>
                                                <?php
                                                $sql = "SELECT `role_id`, `role_name`, `role_key`"
                                                        . " FROM `t_role` WHERE 1";
                                                $result = $mysqli->query($sql);
                                                while ($fetch = $result->fetch_assoc()) {
                                                    ?>
                                                    <option value="<?php echo $fetch['role_key']; ?>"><?php echo $fetch['role_name']; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>                                       
                                        <div class="box-footer">                                          
                                            <button type="button" data-page="user" class="btn btn-primary btn-insert">
                                                <i class="fa fa-save"></i>
                                                Save
                                            </button>
                                            <button type="reset" class="btn btn-default">
                                                <i class="fa fa-refresh"></i>
                                                Reset
                                            </button>
                                            <button type="button"  class="btn btn-default cancel"> 
                                                Cancel
                                            </button>
                                        </div>
                                        <input  name="action" id="action" value="add" type="hidden">
                                        <input  name="id" id="id" value="" type="hidden">

                                    </form>

                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                        </div><!-- /.col -->
                    </div> <!-- /.row -->

                    <div class="row">
                        <div id="col-detail" class="col-md-5" style="min-width: 600px">                        
                            <div class="box box-default">
                                <div class="box-header">
                                    <h3 class="box-title">                              
                                        <span class="btn btn-xs btn-success add-proj">
                                            <i class="fa fa-plus-square-o"></i>  Add   
                                        </span>
                                    </h3>
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table  class="table table-bordered table-hover">                                    
                                        <thead>
                                            <tr>
                                                <th style="width: 10px" class="nosort">#</th>                                            
                                                <th>Name</th>                                            
                                                <th>Role</th>                                                 
                                                <th style="width:40px" class="nosort"></th>
                                                <th style="width:40px" class="nosort"></th>                                         
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT @rownum := @rownum + 1 AS rownum,"
                                                    . "a. admin_user_id as id,CONCAT(b.titlename,' ',b.Name,' ( ',b.NickName,' )') as name,c.role_name"
                                                    . " FROM bz_timestamp.t_admin_user a "
                                                    . " LEFT JOIN baezenic_people.t_people b ON b.id=a.uid"
                                                    . " LEFT JOIN bz_timestamp.t_role c ON c.role_key=a.role_key"
                                                    . " ,(SELECT @rownum := 0) r"
                                                    . " WHERE b.status<>'Y' AND b.Office!='Vietnam'"
                                                    . " ORDER BY b.id ASC";
                                            //echo $sql;
                                            $result = $mysqli->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $row['rownum'] ?></td>                                               
                                                    <td><?php echo $row['name'] ?></td>
                                                    <td><?php echo $row['role_name'] ?></td>                                                    
                                                    <td class="text-center">
                                                        <a class="btn btn-xs btn-info btn-edit" href="#" data-page="user" data-id="<?php echo $row['id']; ?>">
                                                            <i class="fa fa-edit"></i> edit 
                                                        </a>                                                    
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn btn-xs btn-danger btn-del" href="#" data-page="user" data-id="<?php echo $row['id']; ?>">
                                                            <i class="fa fa fa-trash-o"></i> del 
                                                        </a>                                                    
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
        <script src="../bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>        
        <script src="script/login.js" type="text/javascript"></script>
        <script src="script/main.js" type="text/javascript"></script>
        <script>
            $(function () {
                JSClass.loadStaff();
            });
        </script>

    </body>
</html>