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