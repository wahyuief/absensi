<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="nama_matkul" class="col-sm-2 form-label wahlabel">Nama Mata Kuliah<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="nama_matkul" id="nama_matkul" value="<?php echo $this->form_validation->set_value('nama_matkul'); ?>" class="form-control" autofocus required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-book-bookmark"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="sks" class="col-sm-2 form-label wahlabel">SKS<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="number" name="sks" id="sks" value="<?php echo $this->form_validation->set_value('sks'); ?>" class="form-control" autofocus required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-book-bookmark"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="dosen" class="col-sm-2 form-label wahlabel">Dosen<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="dosen" id="dosen" class="form-control" required>
                                          <option value="" disabled selected>Pilih Dosen</option>
                                          <?php foreach($dosen as $dsn): ?>
                                                <option value="<?php echo $dsn->id ?>"><?php echo $dsn->fullname ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="semester" class="col-sm-2 form-label wahlabel">Semester<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="semester" id="semester" class="form-control" required>
                                          <option value="" disabled selected>Pilih Semester</option>
                                          <?php foreach($semester as $smstr): ?>
                                                <option value="<?php echo $smstr->id_semester ?>"><?php echo $smstr->tahun . ' ' . $smstr->keterangan ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-hourglass"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('matkul') ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>