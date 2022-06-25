<?php echo form_open(); ?>
<div id="reset_password">
    <p class="text-center">Please enter your new password</p>

    <label for="new" class="form-label">New Password (at least <?php echo $min_password_length; ?> characters long)</label>
    <div class="input-group mb-3">
        <input type="password" name="new" id="new" class="form-control" autofocus required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <label for="new_confirm" class="form-label">Confirm New Password</label>
    <div class="input-group mb-3">
        <input type="password" name="new_confirm" id="new_confirm" class="form-control" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <div class="mb-4">
		<?php echo form_input($user_id);?>
		<?php echo form_hidden($csrf); ?>
        <input type="submit" value="Change" class="btn btn-primary w-100">
    </div>

    <div class="text-center">
        <p class="mb-0"><a href="<?php echo base_url('auth/login'); ?>">Back to login</a></p>
    </div>
</div>
<?php echo form_close(); ?>