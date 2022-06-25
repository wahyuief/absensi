<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <div class="card-body rounded login-card-body">

                        <div class="row mb-3">
                              <label class="col-sm-2 form-label wahlabel">From</label>
                              <?php echo $notif->sender_name ?>
                        </div>

                        <div class="row mb-3">
                              <label class="col-sm-2 form-label wahlabel">Title</label>
                              <?php echo $notif->title ?>
                        </div>

                        <div class="row mb-3">
                              <label class="col-sm-2 form-label wahlabel">Message</label>
                              <?php echo $notif->message ?>
                        </div>

                        <div class="row mb-3">
                              <label class="col-sm-2 form-label wahlabel">Date</label>
                              <?php echo $notif->sent_on ?>
                        </div>

                  </div>
                  <div class="card-footer border-top">
                        <a href="<?php echo base_url('notification') ?>" class="btn btn-sm btn-default">Back</a>
                  </div>
            </div>
      </div>
</div>