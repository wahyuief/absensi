<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="matkul" class="col-sm-2 form-label wahlabel">Mata Kuliah<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="matkul" id="matkul" class="form-control" required>
                                          <option value="" disabled selected>Pilih Mata Kuliah</option>
                                          <?php foreach($matkul as $mk): ?>
                                                <option value="<?php echo $mk->id_matkul ?>"><?php echo $mk->nama_matkul ?></option>
                                          <?php endforeach; ?>
                                    </select>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-building-user"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="jadwal" class="col-sm-2 form-label wahlabel">Jadwal<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <select name="hari" id="hari" class="form-control">
                                          <option value="" disabled selected>Pilih Hari</option>
                                          <option value="Senin">Senin</option>
                                          <option value="Selasa">Selasa</option>
                                          <option value="Rabu">Rabu</option>
                                          <option value="Kamis">Kamis</option>
                                          <option value="Jumat">Jumat</option>
                                          <option value="Sabtu">Sabtu</option>
                                          <option value="Minggu">Minggu</option>
                                    </select>
                                    <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control">
                                    <div class="input-group-append bg-secondary border-right-0 rounded-0">
                                          <div class="input-group-text bg-secondary border-0 rounded-0">to</div>
                                    </div>
                                    <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control border-right">
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('kelas/matkul/' . $this->uri->segment(4)) ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>