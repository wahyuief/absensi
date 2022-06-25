<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <?php require_once 'nav-tabs.php'; ?>
            </div>
            <?php echo form_open(); ?>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group row">
                                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $this->form_validation->set_value('username', $user_sess->username); ?>" placeholder="Username" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $this->form_validation->set_value('email', $user_sess->email); ?>" placeholder="Email" readonly required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fullname" class="col-sm-2 col-form-label">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="fullname" id="fullname" value="<?php echo $this->form_validation->set_value('fullname', $user_sess->fullname); ?>" placeholder="Full Name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $this->form_validation->set_value('phone', $user_sess->phone); ?>" placeholder="Phone" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company" class="col-sm-2 col-form-label">Company</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="company" id="company" value="<?php echo $this->form_validation->set_value('company', $user_sess->company); ?>" placeholder="Company">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top">
                <button type="submit" name="submit" value="general" class="btn btn-primary">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>