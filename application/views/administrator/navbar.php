<nav class="main-header navbar navbar-expand navbar-dark border-bottom-0 text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?php echo base_url(); ?>" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge"
                    id="numnotif"><?php echo notif_list($user_sess->id, null, null, false, ['notification.read_on' => null])->num_rows(); ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <?php $navnotification = notif_list($user_sess->id, 5, 0, false, ['notification.read_on' => null])->result();if($navnotification): foreach ($navnotification as $navnotif): ?>
                <a href="<?php echo base_url('administrator/notification?q=' . $navnotif->title) ?>"
                    class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> <?php echo substr_replace($navnotif->title, '..', 17) ?>
                    <span
                        class="float-right text-muted text-sm"><?php echo time_elapsed_string($navnotif->sent_on) ?></span>
                </a>
                <?php endforeach; ?>
                <div class="dropdown-divider"></div>
                <a href="<?php echo base_url('administrator/notification'); ?>"
                    class="dropdown-item dropdown-footer">See all notification</a>
                <?php else: ?>
                <a href="#" class="dropdown-item dropdown-footer">No data available</a>
                <?php endif; ?>
            </div>
        </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                <img src="<?php echo base_url('assets/avatar/' . $user_sess->avatar); ?>" class="user-image img-circle elevation-2" alt="User Avatar" onerror="this.onerror=null;this.src='<?php echo base_url('assets/avatar/default.png'); ?>';">
                <span class="d-none d-md-inline"><?php echo $user_sess->fullname; ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">

                <li class="user-header bg-primary">
                    <img src="<?php echo base_url('assets/avatar/' . $user_sess->avatar); ?>" class="img-circle elevation-2" alt="User Avatar" onerror="this.onerror=null;this.src='<?php echo base_url('assets/avatar/default.png'); ?>';">
                    <p>
                        <?php echo $user_sess->fullname; ?>
                        <small>Last Login: <?php echo date('Y-m-d H:i:s', $user_sess->last_login); ?></small>
                    </p>
                </li>

                <li class="user-footer">
                    <a href="<?php echo base_url('profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                    <a href="<?php echo base_url('logout'); ?>" class="btn btn-default btn-flat float-right">Sign out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>