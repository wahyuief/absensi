<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="name" class="col-sm-2 form-label wahlabel">Name<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="name" id="name" value="<?php echo $this->form_validation->set_value('name'); ?>" class="form-control" autofocus required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-layer-group"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="description" class="col-sm-2 form-label wahlabel">Description</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="description" id="description" value="<?php echo $this->form_validation->set_value('description'); ?>" class="form-control">
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-comment-dots"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <?php echo form_hidden($csrf); ?>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('administrator/groups') ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>