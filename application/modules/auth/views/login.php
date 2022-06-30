<?php echo form_open(); ?>
<div id="login">
    <p class="text-center">Sign in to start your session</p>

    <label for="email" class="form-label">Email Address</label>
    <div class="input-group mb-3">
        <input type="email" name="email" id="email" value="<?php echo $this->form_validation->set_value('email'); ?>" class="form-control" autofocus required>
        <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between"><label for="password" class="form-label">Password</label><a href="<?php echo base_url('auth/password/forgot'); ?>">Forgot Password?</a></div>
    <div class="input-group mb-3">
        <input type="password" name="password" id="password" class="form-control" required>
        <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">
                    Remember Me
                </label>
            </div>
        </div>

        <div class="col-4">
            <?php echo form_hidden($csrf); ?>
            <input type="submit" value="Login" class="btn btn-primary btn-block">
        </div>
    </div>

    <!-- <div class="text-center">
        <p class="mb-0">Don't have an account? <a href="<?php echo base_url('auth/register'); ?>">Sign Up</a></p>
    </div> -->
</div>
<?php echo form_close(); ?>