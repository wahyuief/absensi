<section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
          <a href="<?php echo base_url('mahasiswa') ?>" >
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $jml_mahasiswa ?></h3>

                <p>Mahasiswa</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-graduate"></i>
              </div>
            </div>
            </a>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
          <a href="<?php echo base_url('dosen') ?>" >
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $jml_dosen ?></h3>

                <p>Dosen</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-tie"></i>
              </div>
            </div>
            </a>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
          <a href="<?php echo base_url('kelas') ?>" >
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $jml_kelas ?></h3>

                <p>Kelas</p>
              </div>
              <div class="icon">
                <i class="fas fa-building-user"></i>
              </div>
            </div>
          </a>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
          <a href="<?php echo base_url('matkul') ?>" >
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $jml_matkul ?></h3>

                <p>Mata Kuliah</p>
              </div>
              <div class="icon">
                <i class="fas fa-book-bookmark"></i>
              </div>
            </div>
          </a>
          </div>
          <!-- ./col -->
        </div>
        <?php if ($this->ion_auth->in_group('mahasiswa')): ?>
        <div class="card">
          <div class="card-header">
              <div class="card-title">
                <div class="btn-group">
                  <?php if(!$this->ion_auth->in_group('mahasiswa')): ?>
                  <a href="<?php echo base_url('mahasiswa') ?>" class="btn btn-sm btn-default">Back</a> 
                  <a href="<?php echo base_url('mahasiswa/matkul/add/' . $this->uri->segment(3)); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add New</a>
                    <?php endif; ?>
                    <?php echo $user->fullname ?>
                </div>
              </div>
          </div>
          <div class="card-body p-0 table-responsive">
            <table class="table table-striped">
              <thead>
                  <tr>
                      <th width="5">#</th>
                      <th>Mata Kuliah</th>
                      <th>Kelas</th>
                      <th class="text-center">Presensi</th>
                      <th>Rekap Absen</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if(!empty($datas)): $i=1;foreach ($datas as $data):
                $presensi = $this->absensi_model->get(['absensi.id_user' => $user->id, 'absensi.id_matkul' => $data->id_matkul], $search)->num_rows();
                ?>
                  <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $data->nama_matkul; ?></td>
                      <td><?php echo $data->nama_kelas; ?></td>
                      <td class="text-center"><?php echo round($presensi*100/14); ?>%</td>
                      <td><a href="<?php echo base_url('mahasiswa/matkul/absensi/' . wah_encode($data->id_matkul) . '/' . wah_encode($data->id_mahasiswa)); ?>">Lihat Rekap Absen</a></td>
                  </tr>
                  <?php endforeach;else: ?>
                  <tr>
                    <td colspan="10">No data available</td>
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
        <?php endif; ?>

        <div>
        <?php if ($this->ion_auth->in_group('admin')): ?>
          <h4>Grafik Jumlah User</h4?>
          <canvas id="oilChart" width="500" height="150"></canvas>
          <script src="./assets/Chart.js"></script>
          <script type="text/javascript">
          var oilCanvas = document.getElementById("oilChart");

          Chart.defaults.global.defaultFontFamily = "Lato";
          Chart.defaults.global.defaultFontSize = 18;

          var oilData = {
          labels: [
              "Active",
              "InActive",
          ],
          datasets: [
              {
                  data: [
                  <?php echo $jml_active ?>.0, 
                  <?php echo $jml_inactive ?>.0],
                  backgroundColor: [
                      "#0080ff",
                      "#d801ff",
                  ]
              }]
          };

          var pieChart = new Chart(oilCanvas, {
          type: 'pie',
          data: oilData
          });
          </script>
          </div>
          <?php endif; ?>
          </div>
</section>
