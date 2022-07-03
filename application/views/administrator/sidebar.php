<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?php echo base_url('assets/backend/img/AdminLTELogo.png'); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo get_option('site_name') ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-header text-sm">MAIN NAVIGATION</li>
                <li class="nav-item">
                    <a href="<?php echo base_url('dashboard') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'dashboard') echo 'active'; ?>">
                        <i class="nav-icon fas fa-dashboard"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('notification') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'notification') echo 'active'; ?>">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notification</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('jadwal') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'jadwal') echo 'active'; ?>">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>Jadwal</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('kelas') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'kelas') echo 'active'; ?>">
                        <i class="nav-icon fas fa-building-user"></i>
                        <p>Kelas</p>
                    </a>
                </li>
                <?php if(!$this->ion_auth->in_group('mahasiswa')): ?>
                <li class="nav-header text-sm">MASTER DATA</li>
                <?php if($this->ion_auth->is_admin()): ?>
                <li class="nav-item">
                    <a href="<?php echo base_url('mahasiswa') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'mahasiswa') echo 'active'; ?>">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>Mahasiswa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('dosen') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'dosen') echo 'active'; ?>">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Dosen</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?php echo base_url('semester') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'semester') echo 'active'; ?>">
                        <i class="nav-icon fas fa-hourglass"></i>
                        <p>Semester</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('kelas') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'kelas') echo 'active'; ?>">
                        <i class="nav-icon fas fa-building-user"></i>
                        <p>Kelas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('matkul') ?>" class="nav-link <?php if ($this->uri->segment(1) === 'matkul') echo 'active'; ?>">
                        <i class="nav-icon fas fa-book-bookmark"></i>
                        <p>Mata Kuliah</p>
                    </a>
                </li>
                <?php endif;if($this->ion_auth->is_admin()): ?>
                <li class="nav-header text-sm">ADMINISTRATOR</li>
                <li class="nav-item">
                    <a href="<?php echo base_url('administrator/users') ?>" class="nav-link <?php if ($this->uri->segment(2) === 'users') echo 'active'; ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('administrator/groups') ?>" class="nav-link <?php if ($this->uri->segment(2) === 'groups') echo 'active'; ?>">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Groups</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('administrator/settings') ?>" class="nav-link <?php if ($this->uri->segment(2) === 'settings') echo 'active'; ?>">
                        <i class="nav-icon fas fa-gears"></i>
                        <p>Settings</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<div class="content-wrapper">