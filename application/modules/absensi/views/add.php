<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label for="username" class="col-sm-2 form-label wahlabel">NIM<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="username" id="username" value="<?php echo $this->form_validation->set_value('username'); ?>" class="form-control" autofocus required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="fullname" class="col-sm-2 form-label wahlabel">Full Name<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="fullname" id="fullname" value="<?php echo $this->form_validation->set_value('fullname'); ?>" class="form-control" required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        
                        <div class="row mb-3">
                              <label for="email" class="col-sm-2 form-label wahlabel">Email Address<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="email" name="email" id="email" value="<?php echo $this->form_validation->set_value('email'); ?>" class="form-control" required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="phone" class="col-sm-2 form-label wahlabel">Phone</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" name="phone" id="phone" value="<?php echo $this->form_validation->set_value('phone'); ?>" class="form-control">
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="password" class="col-sm-2 form-label wahlabel">Password<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                              </div>
                              <div class="col-sm-2">
                                    <button type="button" data-toggle="modal" data-target="#generatePassword" class="mt-1 btn btn-sm btn-default"><i class="fas fa-shuffle"></i> Generate</button>
                              </div>
                        </div>

                        <div class="row mb-3">
                              <label for="password_confirm" class="col-sm-2 form-label wahlabel">Confirm Password<i class="required">*</i></label>
                              <div class="col-sm-8 input-group">
                                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                              </div>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        <a href="<?php echo base_url('mahasiswa') ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>
<div class="modal fade" id="generatePassword" data-backdrop="static" data-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">Generate Random Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="input-group mb-2">
                              <input type="text" id="randomPassword" class="form-control border border-secondary">
                              <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" id="generate" type="button"><i class="fas fa-shuffle"></i></button>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>
<script>
      document.getElementById('generate').addEventListener('click', function() {
            var random = Math.random().toString(36).slice(2, 10);
            document.getElementById('randomPassword').value = random;
            document.getElementById('password').value = random;
            document.getElementById('password_confirm').value = random;
      })
</script>