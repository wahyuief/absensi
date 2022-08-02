<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link <?php echo ($this->uri->segment(3) === 'general') ? 'active' : ''; ?>" href="<?php echo base_url('administrator/settings/general') ?>">General</a></li>
    <li class="nav-item"><a class="nav-link <?php echo ($this->uri->segment(3) === 'absensi') ? 'active' : ''; ?>" href="<?php echo base_url('administrator/settings/absensi') ?>">Absensi</a></li>
</ul>