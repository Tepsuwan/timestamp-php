<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Timestamp | Login</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>        
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />        
        <link href="libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>        
        <link href="libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->  
        <style>
            .login-box, .register-box {
                width: 450px;
                margin: 7% auto;
            }
        </style>
    </head>
    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a>BZ Timestamp</a>               
                <div class="alert alert-info alert-dismissible"> 
                    <h4>กรุณาใช้ YourEmail@baezeni.com ในการ Log in.</h4>                  
                </div>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form class="form-signin" id="myform" name="myform"  method="post" enctype="multipart/form-data" >
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Email" />
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" />
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-primary btn-block btn-flat btn-submit">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>  
        <script src="js/login.js"></script>
    </body>
</html>