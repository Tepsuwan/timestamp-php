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
        <title>BZ Timestamp::Report</title>             
        <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>  
        <link href="libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="libs/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>        
        <link href="bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
        <link href="handsontable/dist/handsontable.full.css" rel="stylesheet" media="screen">
        <link href="css/custom.css" rel="stylesheet">
        <link href="css/progress-bar.css" rel="stylesheet">        
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
                                        <th rowspan="2" style="width: 100px;">
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
                                        <th style="width: 150px;background:rgba(255, 153, 0, 0.80);border-top-left-radius: 5px">FROM</th>
                                        <th style="width: 150px;background:rgba(255, 153, 0, 0.80);">TO</th>
                                        <th style="width: 2%;background:rgba(255, 153, 0, 0.80);">MONTH</th>
                                        <th style="width: 20%;background:rgba(255, 153, 0, 0.80);">NAME</th>
                                        <th style="width: 15%;background:rgba(255, 153, 0, 0.80);">TEAM</th> 
                                        <th style="width: 15%;background:rgba(255, 153, 0, 0.80);">OFFICE</th> 
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
                                        <td>                                                                                    
                                            <select id="office" name="office" class="selectpicker" data-hide-disabled="true" data-live-search="true">
                                            </select>
                                        </td> 
                                    </tr>                                
                                </tbody>
                            </table> 
                        </div> 
                    </div>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-12">
                    <div class="box box-solid">                        
                        <div class="box-body" >                             
                            <div class="box-header">
                                <h2 class="box-title text-bold" >Summary report</h2>                                
                                <a href="javascript:void(0);" style="display: none;margin-left: 10px"  class="btn btn-sm btn-social btn-flat btn-back "><i class="fa fa-reply"></i> Back</a>
                                <a href="javascript:void(0);" style="margin-left: 10px" id="all_user" class="btn btn-sm btn-social btn-flat">
                                    <i class="fa  fa-plus-square-o"></i> View All
                                </a>

                                <a id="file-excel" href="javascript:void(0);" class="btn btn-sm btn-social btn-flat pull-right">
                                    <i class="fa fa-file-excel-o text-green"></i> Download to Excel
                                </a>

                            </div> 
                            <div id="hot" style="overflow:auto;height:600px;max-width:1500px"></div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p class="muted credit">
                    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="http://baezeni.com/">Baezeni</a>.</strong> All rights reserved.              
                </p>                
            </div>
        </footer>
        <div class="box-loading-top">            
            <div class="loading" >
                <p>Loading.....</p>
            </div>           
        </div>
        <input type="hidden" name="uid" id="uid" value="<?php echo $userId; ?>">
        <input type="hidden" name="role_key" id="role_key" value="<?php echo $_SESSION['role_key']; ?>">
        <input type="hidden" name="work_shift" id="work_shift" value="<?php echo $_SESSION['work_shift_id']; ?>"> 
        <input type="hidden" name="page" id="page" value="summaryReport"> 

        <script src="libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="libs/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>

        <script src="js/JSClass.js"></script>
        <script src="js/login.js"></script>
        <script src="js/rpt.js"></script>
        <script src="handsontable/dist/handsontable.full.js"></script>	
        <script src="bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>         
        <script src="js/hotReport.js"></script>
    </body>
</html>