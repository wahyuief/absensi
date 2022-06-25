<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="mahasiswa" class="col-sm-2 form-label wahlabel">Nama Mahasiswa<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                              <select name="mahasiswa" id="mahasiswa" class="form-control" required>
                                          <?php foreach($mahasiswa as $mah): ?>
                                                <option <?php if ($data->id_mahasiswa === $mah['id']) echo 'selected'; ?> value="<?php echo $mah['id'] ?>"><?php echo $mah['fullname'] ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="kelas" class="col-sm-2 form-label wahlabel">Kelas<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="kelas" id="kelas" class="form-control" required>
                                          <?php foreach($kelas as $kel): ?>
                                                <option <?php if ($data->id_kelas === $kel->id_kelas) echo 'selected'; ?> value="<?php echo $kel->id_kelas ?>"><?php echo $kel->nama_kelas ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-building-user"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="semester" class="col-sm-2 form-label wahlabel">Semester<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="semester" id="semester" class="form-control" required>
                                          <?php foreach($semester as $sem): ?>
                                                <option <?php if ($data->id_semester === $sem->id_semester) echo 'selected'; ?> value="<?php echo $sem->id_semester ?>"><?php echo $sem->tahun .' '. $sem->keterangan ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-hourglass"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <?php echo form_hidden('id', wah_encode($data->id_km));?>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('kelas/mahasiswa/' . wah_encode($data->id_kelas)) ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>