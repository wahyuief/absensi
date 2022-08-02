<section class="content">
    <div class="container-fluid">
        <div class="card">
          <div class="card-header">
              <div class="card-title"><div class="btn-group">
              <a href="<?php echo base_url('mahasiswa/matkul/'.$this->uri->segment(5) ) ?>" class="btn btn-sm btn-default">Back</a>
              <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Export</button>
                    <div class="dropdown-menu" role="menu" style="">
                      <a class="dropdown-item" href="<?php echo base_url('mahasiswa/matkul/export_pdf/'.$this->uri->segment(4) . '/' . $this->uri->segment(5)); ?>">PDF</a>
                      <a class="dropdown-item" href="<?php echo base_url('mahasiswa/matkul/export_excel/'.$this->uri->segment(4) . '/' . $this->uri->segment(5)); ?>">Excel</a>
                    </div>
                  </div>
                <?php echo $matkul->nama_matkul ?>
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
                      <th>Nama Mahasiswa</th>
                      <th>Tanggal Absen</th>
                      <th>Keterangan</th>
                      <th>Foto Absen</th>
                      <th>Lokasi Absen</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if(!empty($datas)): $i=1;foreach ($datas as $data): ?>
                  <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $data->fullname; ?></td>
                      <td><?php echo $data->tanggal_absen; ?></td>
                      <td><?php echo $data->keterangan; ?></td>
                      <td><img src="<?php echo base_url('assets/absensi/'.$data->foto); ?>" width="100" alt="Tidak ada foto"></td>
                      <td><?php echo 'Latitude: '.$data->latitude.'<br>Longitude: '.$data->longitude.'<br>Lokasi: '.find_location($data->latitude, $data->longitude).'<br>Jarak ke Unsada: ±'.number_format(jaraknya(get_option('latitude'), get_option('longitude'), $data->latitude, $data->longitude, 'K'), 2, '.', '').' KM'; ?></td>
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