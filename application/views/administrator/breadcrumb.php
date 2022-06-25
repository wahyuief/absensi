<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo ucwords($this->uri->segment(1));if ($this->uri->segment(2)) echo ' <small class="text-sm muted">' . ucwords($this->uri->segment(2)) . '</small>'; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active"><?php echo ($this->uri->segment(2) ? '<a href="' . base_url('administrator/' . $this->uri->segment(1)) . '">' . ucwords($this->uri->segment(1)) . '</a>' : ucwords($this->uri->segment(1))); ?></li>
                    <?php if ($this->uri->segment(2)) echo '<li class="breadcrumb-item active">' . ucwords($this->uri->segment(2)) . '</li>'; ?>
                </ol>
            </div>
        </div>
    </div>
</div>