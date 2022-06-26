<section class="content">
    <div class="container-fluid">
        <div class="card">
          <div class="card-header">
              <div class="card-title">
                <a href="<?php echo base_url('dosen/foto/add/' . $this->uri->segment(3)); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add New</a>
                  <?php echo $user->fullname ?>
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
                      <th>Nama Dosen</th>
                      <th>Foto</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if(!empty($datas)): $i=1;foreach ($datas as $data): ?>
                  <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $data->fullname; ?></td>
                      <td><img src="<?php echo base_url('assets/facedetection/images/'.$data->fullname.'/'.$data->photo); ?>" alt="<?php echo $data->fullname; ?>"></td>
                  </tr>
                  <?php endforeach;else: ?>
                  <tr>
                    <td colspan="8">No data available</td>
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