<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <?php require_once 'nav-tabs.php'; ?>
            </div>
            <?php echo form_open(); ?>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group row">
                                    <label for="latitude" class="col-sm-2 col-form-label">Latitude<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="latitude" id="latitude" value="<?php echo $this->form_validation->set_value('latitude', get_option('latitude')); ?>" placeholder="Latitude" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="longitude" class="col-sm-2 col-form-label">Longitude<i class="required">*</i></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="longitude" id="longitude" value="<?php echo $this->form_validation->set_value('longitude', get_option('longitude')); ?>" placeholder="Longitude" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="location" id="location" value="<?php echo find_location(get_option('latitude'), get_option('longitude')); ?>" placeholder="Location" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top">
                <button type="submit" name="submit" value="general" class="btn btn-primary">Save</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<script>
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();
    $('#longitude').on('keyup', function(){
        checkLocation(latitude, longitude)
    })
    $('#latitude').on('keyup', function(){
        checkLocation(latitude, longitude)
    })
    checkLocation(latitude, longitude)
    function checkLocation(latitude, longitude) {
        var darta = new FormData();
        darta.append( 'latitude', latitude );
        darta.append( 'longitude', longitude );
        $.ajax({
            data: darta,
            type: 'POST',
            url: 'check_location',
            processData: false,
            contentType: false,
            success:function(data){
                $('#location').val(data);
            }
        });
    }
</script>