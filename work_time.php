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
$team = $_SESSION['team'];

$team = $_GET['t'];
//echo "xxxxxxxxxxx".$team;
$teamArr = array("All" => "All", "AC" => "Account", "FP" => "Floorplan", "PE" => "Photo Edit", "3D" => "3D", "CAD" => "CAD", "SU" => "SU", "IT" => "IT");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">       
        <title>BZ Timestamp</title>               
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>  
        <link href="../libs/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        <link href="../libs/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />


        <link href="../assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

        <link href="../css/custom.css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->  

    </head>
    <body>

        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="../images/logopattaya.png" style="max-width:100px; margin-top: -12px;"></a>                   
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="#">Work Time For <?php echo $teamArr[$team] ?></a></li> 
                        <li style="position: absolute;right: 0"><a href="javascript:close_window();" class="fa-logout" title="Close Window"><i class="fa fa-close fa-1x "></i></a></li> 
                    </ul>
                </div>
            </div>
        </nav>       

        <div class="container">
            <div class="col-md-12">                
                <div class="row ">                    
                    <div class="col-md-10 col-md-offset-1">                       
                        <div class="box">
                            <div class="box-body">
                                <table id="data-table-project" class="table table-bordered table-striped responsive" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:20px">#</th>
                                            <th class="text-center" style="width:20px">id</th>
                                            <th>Name</th>                                            
                                            <th>Team</th>
                                            <th>Access</th>
                                            <th>Work time</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">id</th>
                                            <th>Name</th>                                            
                                            <th>Team</th>
                                            <th>Access</th>
                                            <th>Work time</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
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


        <script src="../libs/plugins/jQuery/jQuery-2.1.4.min.js"></script>  
        <script src="../libs/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../libs/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="../assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <script>
            $(function () {

                $('#data-table-project').dataTable({
                    "ajax": "../libs/php/work_time.php?team=<?php echo $team ?>",
                    "iDisplayLength": 12,
                    "aLengthMenu": [[5, 10,12, 15, 25, 50, -1], [5, 10,12, 15, 25, 50, "All"]],
                    "columns": [{
                            "data": "rownum"
                        }, {
                            "data": "id"
                        }, {
                            "data": "name"
                        }, {
                            "data": "team"
                        }, {
                            "data": "access"
                        }, {
                            "data": "work_time"
                        }],
                    columnDefs: [{
                            targets: 0,
                            "data": "rownum",
                            "orderable": false,
                            className: "text-center",
                            render: function (data, type, full, meta) {
                                return data;
                            }
                        }, {
                            targets: 4,
                            "data": "access",
                            "orderable": false,
                            className: "text-center",
                            render: function (data, type, full, meta) {
                                var html = '<div class="checkbox" style="margin:0px">' +
                                        '<label>' +
                                        '<input type="checkbox" data-id="' + full.id + '" data-prop="is_operator"  class="minimal" onclick="update(this);"  value="" ' + (data == 1 ? 'checked' : '') + '/>' +
                                        '</label>' +
                                        '</div>';
                                return html;
                            }
                        }, {
                            targets: 5,
                            "data": "work_time",
                            "orderable": false,
                            className: "text-center",
                            render: function (data, type, full, meta) {

                                var html = '<select data-id="' + full.id + '" id="' + full.id + '" data-prop="work_shift_id" class="form-control " onchange="update(this);">';
                                full.shift.forEach(function (value) {
                                    var condition = (value[0] === data ? "selected" : "");
                                    html += '<option ' + condition + '  value=' + value[0] + '>' + value[1] + '</option>';
                                });

                                html += '</select>';
                                return html;

                            }
                        }]
                });
            });

            function update(element) {

                var data = {
                    id: $(element).data('id'),
                    prop: $(element).data('prop'),
                    checked: $(element).is(':checked'),
                    val: $(element).val()
                };

                $.ajax({
                    url: "../setting/php/update_work_time.php",
                    data: data,
                    type: 'POST',
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                    }
                });

            }
            function close_window() {
                if (confirm("Close Window?")) {
                    close();
                }
            }

        </script>
    </body>
</html>