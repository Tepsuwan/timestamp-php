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
                        Reason
                        <small>setting</small>
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content">

                    <div class="row">
                        <div class="col-md-5">

                            <div class="box box-solid" >                                                               
                                <div class="box-body">
                                    <div class="embed-responsive embed-responsive-16by9">
<!--                                        <iframe class="embed-responsive-item" src="http://www.baezeni.com/time/photo-backup-db.php"></iframe>-->
                                        <iframe class="embed-responsive-item" src="http://time.baezeni.com/photo-backup-db.php"></iframe>
                                    </div>

                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                        </div><!-- /.col -->
                    </div> <!-- /.row -->

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
        <script src="script/login.js" type="text/javascript"></script>


    </body>
</html>