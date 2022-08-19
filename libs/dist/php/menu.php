<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">


        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php
        $url = $_SERVER['REQUEST_URI'];
        $explo = explode('/', $url);
        $count = count($explo);
        $page = $explo[$count - 1];


        $role_key = (empty($_SESSION['role_key']) ? '' : $_SESSION['role_key']);
        ?>

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>

            <?php
            $active = "";
            if ($page == 'employee_time.php') {
                $active = "active";
                $staff = 'active';
            }
            ?>    

            <li class="li-disabled treeview <?php echo $active; ?> " >
                <a href="#">
                    <i class="fa fa-gear"></i> <span>Staff setting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">                    
                    <li  class="li-disabled <?php echo $staff; ?>">
                        <a href="employee_time.php"><i class="fa fa-calendar-o"></i> <span>Staff dayshift</span></a>
                    </li>                                      
                </ul>
            </li>





            <?php
            $active = "";
            $reason = "";
            $work_shift = "";
            $sync_user = "";
            if ($page == 'reason.php') {
                $active = " active";
                $reason = 'active';
            } else if ($page == 'work_shift.php') {
                $active = " active";
                $work_shift = 'active';
            }
            ?>
            <li class="li-disabled treeview<?php echo $active; ?> " >
                <a href="#">
                    <i class="fa fa-gear"></i> <span>Configuration</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">                    
                    <li  class="li-disabled <?php echo $reason; ?>">
                        <a href="reason.php"><i class="fa fa-calendar"></i> <span>Reason</span></a>
                    </li>
                    <li class="li-disabled <?php echo $work_shift; ?>" >
                        <a href="work_shift.php"><i class="fa fa-clock-o"></i> <span>Work shift</span></a>
                    </li>                    
                </ul>
            </li>
            <?php
            $active = "";
            $role = "";
            $user = "";
            $sync_user = "";
            if ($page == 'role.php') {
                $active = " active";
                $role = 'active';
            } else if ($page == 'user.php') {
                $active = " active";
                $user = 'active';
            } else if ($page == 'sync_user.php') {
                $active = " active";
                $sync_user = 'active';
            }
            ?>
            <li class="li-disabled treeview<?php echo $active; ?> " >
                <a href="#">
                    <i class="fa fa-user"></i> <span>Admin setting</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="li-disabled <?php echo $sync_user; ?>">
                        <a href="sync_user.php"><i class="fa fa fa-retweet"></i>Sync user</a>
                    </li>
                    <li  class="li-disabled <?php echo $user; ?>">
                        <a href="user.php"><i class="fa fa-user"></i> <span>User</span></a>
                    </li>
                    <li class="li-disabled <?php echo $role; ?>" >
                        <a href="role.php"><i class="fa fa-users"></i> <span>Role</span></a>
                    </li>                    
                </ul>
            </li>
            <?php
            $bin = "";
            if ($page == 'bin.php') {
                $bin = "active";
            }
            ?>
            <li class="li-disabled <?php echo $bin; ?>" >
                <a href="bin.php"><i class="fa fa-trash-o"></i> <span>Bin</span></a>
            </li>            
            <li class="fa-logout"><a href="#"><i class="fa fa-power-off "></i> <span>Log out</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<input type="hidden" name="role_key" id="role_key" value="<?php echo $role_key; ?>">

