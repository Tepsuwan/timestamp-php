<?php
session_start();
if (!empty($_SESSION['role_key'])) {
    if ($_SESSION['role_key'] == '1') {
        echo "<script>";
        echo "window.location = 'user.php';";
        echo "</script>";
    }// else if ($_SESSION['role_key'] == '3') {
//        echo "<script>";
//        echo "window.location = 'fp_calendar.php';";
//        echo "</script>";
//    } else if ($_SESSION['role_key'] == '4') {
//        echo "<script>";
//        echo "window.location = 'fp_calendar.php';";
//        echo "</script>";
//    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="../libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <!-- Theme style -->
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a>Admin panel</a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Web admin control panel</p>
                <form class="form-signin" id="myform" name="myform"  method="post" enctype="multipart/form-data" >
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" />
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">

                        <div class="col-xs-4">
                            <button type="button" class="btn btn-primary btn-block btn-flat btn-submit">Sign In</button>
                        </div><!-- /.col -->
                    </div>
                </form>


            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <!-- jQuery 2.1.4 -->
        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="script/login.js"></script>

    </body>
</html>