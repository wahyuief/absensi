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
                              <label for="matkul" class="col-sm-2 form-label wahlabel">Mata Kuliah<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="matkul" id="matkul" class="form-control" required>
                                          <?php foreach($matkul as $mk): ?>
                                                <option <?php if ($data->id_matkul === $mk['id_matkul']) echo 'selected'; ?> value="<?php echo $mk['id_matkul'] ?>"><?php echo $mk['nama_matkul'] ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-book-bookmark"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <?php echo form_hidden('id', wah_encode($data->id_km));?>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('matkul/mahasiswa/' . wah_encode($data->id_matkul)) ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>