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
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
        <link href="../libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>       
        <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />        
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />       
        <link href="../libs/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />       
        <link href="css/custom.css" rel="stylesheet" type="text/css"/>        
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
            <div class="content-wrapper">              
                <section class="content-header">
                    <h1>
                        Sync people DB
                        <small>setting</small>
                    </h1>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="box box-solid" >                                                               
                                <div class="box-body">
                                    <!--                                    <div class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item" src="http://www.baezeni.com/time/photo-backup-db.php"></iframe>
                                                                            <iframe class="embed-responsive-item" src="http://time.baezeni.com/photo-backup-db.php"></iframe>
                                    
                                                                        </div>-->
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Submit - DO NOT SYNC PEOPLE DB, This will break the whole login account</h3>
                                        </div> 
                                        <div class="box-body">                                             
                                            <button type="button" id="sync_people_db" class="btn btn-primary">Sync People DB</button>                                            
                                        </div> 
                                        <div id="Syncing" class="hidden">Syncing...................</div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- =============================================== -->
            <?php include '../libs/dist/php/footer.php'; ?>
            <!-- =============================================== -->

        </div><!-- ./wrapper -->

        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>       
        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>  
        <script src="../libs/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>   
        <script src="../libs/dist/js/app.min.js" type="text/javascript"></script> 
        <script src="script/login.js" type="text/javascript"></script>
        <script>
            $(function () {
                $("#sync_people_db").click(function () {
                    $("#Syncing").removeClass("hidden");
                    $.ajax({
                        url: "https://time.baezeni.com/timestamp/get_peoples?source=WVRVMk1EYzBObU0xWkdGbU5qaGxPREJsTkdVMk1XVmlNelExTmpNek16QXlZMk5oTm1VNVpXVTBNR1poTVdWaVptRXhNR015T1ROalpUWTBNR1JoTXc9PTpkRzl0UUdKaFpYcGxibWt1WTI5dDpNakF5TURFeE1ETXdPRFExTWpVPQ==",
                        dataType: 'jsonp',
                        success: function(res){

                            res['obj'].map(function(item, i){
                                let key = Object.keys(item);
                                key.map(function(val, v){
                                    item[val] = escape_string(item[val]);
                                });
                            });
                            
                            if(res.obj.length > 0){
                                var d=JSON.stringify(res.obj);

                                $.post("/libs/php/sync_people_db.php", {data: d}, function (data) {                    
                                    alert("Successfully.");
                                    $("#Syncing").addClass("hidden");
                                });
                            }
                        }
                    });
                });
            });

            function escape_string(str) {
                if(str){
                    return str.replace(/[\0\x08\x09\x1a\n\r"'\\\%]/g, function (char) {
                        switch (char) {
                            case "\0":
                                return "\\0";
                            case "\x08":
                                return "\\b";
                            case "\x09":
                                return "\\t";
                            case "\x1a":
                                return "\\z";
                            case "\n":
                                return "\\n";
                            case "\r":
                                return "\\r";
                            case "\"":
                            case "'":
                            case "\\":
                            case "%":
                                return "\\"+char; // prepends a backslash to backslash, percent,
                                                  // and double/single quotes
                            default:
                                return char;
                        }
                    });
                }else{
                    return str;
                }
            }
        </script>



    </body>
</html>