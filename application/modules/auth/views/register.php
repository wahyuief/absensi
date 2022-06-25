<?php echo form_open(); ?>
<div id="register">
    <p class="text-center">Sign up to create your account</p>

    <label for="email" class="form-label">Email Address</label>
    <div class="input-group mb-3">
        <input type="email" name="email" id="email" value="<?php echo $this->form_validation->set_value('email'); ?>" class="form-control" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <label for="password" class="form-label">Password</label>
    <div class="input-group mb-3">
        <input type="password" name="password" id="password" class="form-control" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <label for="password_confirm" class="form-label">Confirm Password</label>
    <div class="input-group mb-3">
        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-8">
            <div class="input-group icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                <label for="agreeTerms">I agree to the <a href="#">terms</a></label>
            </div>
        </div>
        <div class="col-4">
            <?php echo form_hidden($csrf); ?>
            <input type="submit" value="Register" class="btn btn-primary w-100">
        </div>
    </div>

    <div class="text-center">
        <p class="mb-0">Already have an account? <a href="<?php echo base_url('auth/login'); ?>">Sign In</a></p>
    </div>
</div>
<?php echo form_close(); ?>