<section class="content">
    <div class="container-fluid">
        <div class="card">
          <div class="card-header">
              <div class="card-title">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Export</button>
                    <div class="dropdown-menu" role="menu" style="">
                      <a class="dropdown-item" href="<?php echo base_url('notification/export_pdf'); ?>">PDF</a>
                      <a class="dropdown-item" href="<?php echo base_url('notification/export_excel'); ?>">Excel</a>
                    </div>
                  </div>
              </div>
              <div class="card-tools w-25">
                <form method="get">
                  <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" value="<?php echo input_get('q') ?>" placeholder="Search..">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
          </div>
          <div class="card-body p-0 table-responsive">
            <table class="table table-striped">
              <thead>
                  <tr>
                      <th width="5">#</th>
                      <th width="200">From</th>
                      <th>Title</th>
                      <th width="200">Date</th>
                      <th width="100" class="text-center">Status</th>
                      <th width="100" class="text-center">Action</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if(!empty($notification)): $i=1;foreach ($notification as $notif): ?>
                  <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $notif->sender_name; ?></td>
                      <td><?php echo $notif->title; ?></td>
                      <td><?php echo $notif->sent_on; ?></td>
                      <td class="text-center"><?php echo ($notif->read_on ? '<span class="badge badge-success">Read</span>' : '<span class="badge badge-warning">Unread</span>'); ?></td>
                      <td class="text-center">
                        <div class="btn-group">
                          <a href="#" class="text-lg text-dark" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis"></i></a>
                          <div class="dropdown-menu" role="menu" style="">
                            <a class="dropdown-item" href="<?php echo base_url('notification/read/' . wah_encode($notif->id)); ?>">Read Message</a>
                            <a class="dropdown-item" data-toggle="confirmation" data-title="Are you sure want to delete?"  data-placement="left" href="<?php echo base_url('notification/delete/' . wah_encode($notif->id)); ?>">Delete</a>
                          </div>
                        </div>
                      </td>
                  </tr>
                  <?php endforeach;else: ?>
                  <tr>
                    <td colspan="6">No data available</td>
                  </tr>
                  <?php endif; ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer border-top text-center row">
            <div class="col-sm-6"><p class="float-sm-left m-0" style="line-height: 2;">Showing <?php echo $start; ?> to <?php echo $end; ?> of <?php echo $total; ?> entries</p></div>
            <div class="col-sm-6">
            <?php echo yidas\widgets\Pagination::widget([
              'pagination' => $pagination,
              'ulCssClass' => 'pagination pagination-sm float-sm-right m-0'
            ]); ?>
            </div>
          </div>
        </div>
    </div>
</section>