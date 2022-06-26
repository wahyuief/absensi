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
                                                <option <?php echo($data->id_matkul === $mk->id_matkul) ? 'selected' : ''; ?> value="<?php echo $mk->id_matkul ?>"><?php echo $mk->nama_matkul ?></option>
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
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Senin') ? 'selected' : '' ?> value="Senin">Senin</option>
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Selasa') ? 'selected' : '' ?> value="Selasa">Selasa</option>
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Rabu') ? 'selected' : '' ?> value="Rabu">Rabu</option>
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Kamis') ? 'selected' : '' ?> value="Kamis">Kamis</option>
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Jumat') ? 'selected' : '' ?> value="Jumat">Jumat</option>
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Sabtu') ? 'selected' : '' ?> value="Sabtu">Sabtu</option>
                                          <option <?php echo(hariIndo(date('l', $data->jadwal_mulai)) === 'Minggu') ? 'selected' : '' ?> value="Minggu">Minggu</option>
                                    </select>
                                    <input type="time" name="waktu_mulai" id="waktu_mulai" value="<?php echo date('H:i', $data->jadwal_mulai) ?>" class="form-control">
                                    <div class="input-group-append bg-secondary border-right-0 rounded-0">
                                          <div class="input-group-text bg-secondary border-0 rounded-0">to</div>
                                    </div>
                                    <input type="time" name="waktu_selesai" id="waktu_selesai" value="<?php echo date('H:i', $data->jadwal_selesai) ?>" class="form-control border-right">
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <?php echo form_hidden('id', wah_encode($data->id_km));?>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('kelas/matkul/' . wah_encode($data->id_kelas)) ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>