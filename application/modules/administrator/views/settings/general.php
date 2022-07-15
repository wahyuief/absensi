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
                                    <label for="site_name" class="col-sm-2 col-form-label">Site Name<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="site_name" id="site_name" value="<?php echo $this->form_validation->set_value('site_name', get_option('site_name', 'Baseigniter')); ?>" placeholder="Site Name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="site_description" class="col-sm-2 col-form-label">Site Description<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <textarea name="site_description" id="site_description" class="form-control" required><?php echo $this->form_validation->set_value('site_description', get_option('site_description', 'Baseigniter')); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="author" class="col-sm-2 col-form-label">Author<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="author" id="author" value="<?php echo $this->form_validation->set_value('author', get_option('author', 'Wahyu Arief')); ?>" placeholder="Author" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $this->form_validation->set_value('email', get_option('email', 'wahyuief@gmail.com')); ?>" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="timezone" class="col-sm-2 col-form-label">Timezone<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <select name="timezone" id="timezone" class="form-control" style="width: 100%" required>
                                            <option value="<?php echo $this->form_validation->set_value('timezone', get_option('timezone', 'Asia/Jakarta')); ?>" selected><?php echo $this->form_validation->set_value('timezone', get_option('timezone', 'Asia/Jakarta')); ?></option>
                                        </select>
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