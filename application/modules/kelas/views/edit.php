<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="nama_kelas" class="col-sm-2 form-label wahlabel">Nama Kelas<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="nama_kelas" id="nama_kelas" value="<?php echo $this->form_validation->set_value('nama_kelas', $data->nama_kelas); ?>" class="form-control" autofocus required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-building-user"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <?php echo form_hidden('id', wah_encode($data->id_kelas));?>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('kelas') ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>