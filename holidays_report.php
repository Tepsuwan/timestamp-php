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
$toDate = date('d/m/Y');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">       
        <title>BZ Timestamp::Report</title>      
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>  
        <link href="libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="libs/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
        <link href="handsontable/0.24.3/dist/handsontable.full.css" rel="stylesheet" media="screen">
        <link href="bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>

        <link href="css/custom.css" rel="stylesheet">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->     
    </head>
    <body>
        <div id="loadingExcel">
            <div class= "progress-container">
                <div class="progress progress-success progress-striped active xs">
                    <div class="bar"></div>
                </div>
            </div>
        </div>       
        <?php include './libs/dist/php/menu_fontpage.php'; ?>       
        <div class="container"> 
            <div class="row">                
                <div class="col-md-12">

                    <div class="box">                                             
                        <div class="box-body">                           
                            <table class="table">
                                <tbody>
                                    <tr>                                    
                                        <th rowspan="2" style="width: 100px;padding-top: 0">
                                <div class="box-time">
                                    <table>
                                        <tr>
                                            <td>                                                
                                                <h2 class="time"></h2>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>                                                
                                                <div class="dayText text-right time-text-size"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>                                                
                                                <div class="month text-right time-text-size"></div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                </th>                                
                                <th style="background:rgba(255, 153, 0, 0.80);">NAME</th>                                
                                </tr>                                   
                                <tr>
                                    <td> 
                                        <select id="staff" name="staff"  class="selectpicker" data-hide-disabled="true" data-live-search="true">
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
                        <div class="box-body" >                             
                            <div class="box-header">                                                               
                                <h2 class="box-title text-bold">Holidays Report</h2>
<!--                                <small class="console">Changes will be autosaved</small>                               -->
<!--                                <a id="file-excel" href="javascript:void(0);" class="btn btn-sm btn-social btn-flat pull-right">
                                    <i class="fa fa-file-excel-o text-green"></i> Download to Excel
                                </a>-->
                            </div><!-- /.box-header -->
                            <div id="data-hot" style="overflow:auto;height:600px;max-width: 1500px"></div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="muted credit">
                    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="http://baezeni.com/">Baezeni</a>.</strong> All rights reserved.
                <div class="infrom">                                     
                </div>
                </p> 

            </div>
        </footer>
        <div class="box-loading-top">            
            <div class="loading" >
                <p>Loading.....</p>
            </div>           
        </div>
        <div class="box-loading">
            <div class="sync" >  
                <p>Sync....</p>
            </div>            
        </div>
        <input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>">
        <input type="hidden" name="role_key" id="role_key" value="<?php echo $_SESSION['role_key']; ?>">
        <input type="hidden" name="work_shift" id="work_shift" value="<?php echo $_SESSION['work_shift_id']; ?>"> 
        <input type="hidden" name="page" id="page" value="timestampReport"> 


        <script src="libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="libs/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>  
        <script src="libs/plugins/moment.min.js" type="text/javascript"></script> 
        <script src="socket.io.js" type="text/javascript"></script>        
        <script src="js/JSClass.js"></script>
        <script src="js/login.js"></script>
        <script src="js/holidaysReport.js"></script> 

        <script src="bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>        
        <script src="handsontable/0.24.3/dist/handsontable.full.js"></script>	
        <script src="js/hotHolidays.js"></script>
        <script src="js/nodeJS.js"></script>


    </body>
</html>