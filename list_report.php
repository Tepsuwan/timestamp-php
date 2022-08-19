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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>BZ Timestamp::Report</title>
        <!-- Bootstrap -->        
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>  
        <link href="libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="libs/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>        
        <link href="handsontable/0.24.3/dist/handsontable.full.css" rel="stylesheet" media="screen">
        <link href="bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
        <link href="css/custom.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->     
    </head>
    <body>
        <!-- Fixed navbar -->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="images/logopattaya.png" style="max-width:100px; margin-top: -12px;"></a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li ><a href="stamp">Home</a></li>
                        <li class="dropdown active">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Report <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="sum-report "><a href="#">Summary report</a></li>
                                <li role="separator" class="divider"></li>                                 
                                <li class="detail-report active"><a href="#">List report</a></li>
                            </ul>
                        </li>
                        <li><a href="setting/" target="_bank">Setting</a></li>
                        <li style="position: absolute;right: 0"><a href="#" class="fa-logout" title="Logout"><i class="fa fa-power-off"></i></a></li> 
                    </ul>
                </div><!--/.nav-collapse -->                     
            </div>
        </nav>
        <!-- Begin page content -->
        <div class="container"> 
            <div class="row">                
                <div class="col-md-12">
                    <!-- Block buttons -->                    
                    <div class="box">                                             
                        <div class="box-body">
                            <div class="box-header">                                                               
                                <h2 class="box-title date-format"></h2>
                            </div><!-- /.box-header -->
                            <table class="table">
                                <tbody>
                                    <tr>                                    
                                        <th style="width: 100px;background:#fb9e5f;">FROM</th>
                                        <th style="width: 100px;background:#fb9e5f;">TO</th>
                                        <th style="width: 2%;background:#fb9e5f;">MONTH</th>
                                        <th style="width: 20%;background:#fb9e5f;">NAME</th>
                                        <th style="width: 40%;background:#fb9e5f;">TEAM</th> 
                                    </tr>                                   
                                    <tr>                                    
                                        <td><input type="text" class="form-control" name="txt_fdate" id="txt_fdate" value="<?php echo $fromDate; ?>" ></td>
                                        <td><input type="text" class="form-control" name="txt_tdate" id="txt_tdate" value="<?php echo $toDate; ?>"  ></td>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="monthly" id="monthly"> Monthly
                                                </label>
                                            </div>
                                        </td>
                                        <td> 
                                            <select id="staff" name="staff" class="selectpicker" data-hide-disabled="true" data-live-search="true">
                                            </select>
                                        </td> 
                                        <td>                                                                                    
                                            <select id="team" name="team" class="selectpicker" data-hide-disabled="true" data-live-search="true">
                                            </select>
                                        </td> 

                                    </tr>                                
                                </tbody>
                            </table> 
                        </div> 
                    </div><!-- /.box --> 
                </div>
            </div>
            <div class="row">                
                <div class="col-md-12">
                    <div class="box">                        
                        <div class="box-body" style="position: relative">  
                            <div class="loading" >
                                <p>Loading.....</p>
                            </div>
                            <div class="box-header">
                                <button class="btn btn-warning btn-sm btn-back" style="margin-right: 5px;margin-top: -3px;display: none"><i class="fa fa-reply"></i> Back</button>
                                <button class="btn btn-instagram btn-sm btn-detail" style="margin-right: 5px;margin-top: -3px;"><i class="fa  fa-list "></i>  View detail</button> 
                            </div>
                            <div id="data-hot" style="overflow:auto;height:600px;"></div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="muted credit">Power by <a href="http://Baezeni.com">Baezeni</a></p>
            </div>
        </footer>
        <input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>">
        <input type="hidden" name="isAdmin" id="isAdmin" value="<?php echo $_SESSION['isAdmin']; ?>">
        <input type="hidden" name="work_shift" id="work_shift" value="<?php echo $_SESSION['work_shift_id']; ?>">
        <input type="hidden" name="is_edit" id="is_edit" value="<?php echo $_SESSION['isEdit']; ?>">

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="libs/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

        <script src="js/JSClass.js"></script>
        <script src="js/login.js"></script>
        <script src="js/detail-report.js"></script>        

        <script src="handsontable/0.24.3/dist/handsontable.full.js"></script>  
        <script src="bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
        <script src="jquery-cookie/jquery.cookie.js" type="text/javascript"></script>  
        <script src="js/hotDetailReport .js"></script>


    </body>
</html>