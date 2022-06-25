<?php echo form_open(); ?>
<div id="forgot_password">
    <p class="text-center">Please enter your email address so we can send you an email to reset your password</p>

    <label for="email" class="form-label">Email Address</label>
    <div class="input-group mb-3">
        <input type="email" name="email" id="email" value="<?php echo $this->form_validation->set_value('email'); ?>" class="form-control" autofocus required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <?php echo form_hidden($csrf); ?>
        <input type="submit" value="Submit" class="btn btn-primary w-100">
    </div>

    <div class="text-center">
        <p class="mb-0"><a href="<?php echo base_url('auth/login'); ?>">Back to login</a></p>
    </div>
</div>
<?php echo form_close(); ?>