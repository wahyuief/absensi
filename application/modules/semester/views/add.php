<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="tahun" class="col-sm-2 form-label wahlabel">Tahun<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="tahun" id="tahun" value="<?php echo $this->form_validation->set_value('tahun'); ?>" class="form-control" autofocus required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="keterangan" class="col-sm-2 form-label wahlabel">Keterangan<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="keterangan" id="keterangan" value="<?php echo $this->form_validation->set_value('keterangan'); ?>" class="form-control" required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-newspaper"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('semester') ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>