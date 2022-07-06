<section class="content">
    <div class="container-fluid">
        <div class="card">
          <div class="card-header">
              <div class="card-title"><?php if(!$this->ion_auth->in_group('mahasiswa')): ?>
                <a href="<?php echo base_url('mahasiswa/matkul/add/' . $this->uri->segment(3)); ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add New</a>
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Export</button>
                    <div class="dropdown-menu" role="menu" style="">
                      <a class="dropdown-item" href="<?php echo base_url('mahasiswa/matkul/export_pdf'); ?>">PDF</a>
                      <a class="dropdown-item" href="<?php echo base_url('mahasiswa/matkul/export_excel'); ?>">Excel</a>
                    </div>
                  </div><?php endif; ?>
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
                      <th>Mata Kuliah</th>
                      <th>Kelas</th>
                      <th>SKS</th>
                      <th>Dosen</th>
                      <th>Semester</th>
                      <th>Jadwal</th>
                      <th>Rekap Absen</th><?php if(!$this->ion_auth->in_group('mahasiswa')): ?>
                      <th width="100" class="text-center">Action</th><?php endif; ?>
                  </tr>
              </thead>
              <tbody>
                  <?php if(!empty($datas)): $i=1;foreach ($datas as $data): ?>
                  <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $data->nama_matkul; ?></td>
                      <td><?php echo $data->nama_kelas; ?></td>
                      <td><?php echo $data->sks; ?></td>
                      <td><?php echo $data->fullname; ?></td>
                      <td><?php echo $data->tahun . ' ' . $data->keterangan; ?></td>
                      <td><?php echo hariIndo(date('l', $data->jadwal_mulai)) .', '. date('H:i', $data->jadwal_mulai) .'-'. date('H:i', $data->jadwal_selesai); ?></td>
                      <td><a href="<?php echo base_url('mahasiswa/matkul/absensi/' . wah_encode($data->id_matkul)); ?>">Lihat Rekap Absen</a></td>
                      <?php if(!$this->ion_auth->in_group('mahasiswa')): ?><td class="text-center">
                        <div class="btn-group">
                          <a href="#" class="text-lg text-dark" data-toggle="dropdown" aria-expanded="true"><i class="fas fa-ellipsis"></i></a>
                          <div class="dropdown-menu" role="menu" style="">
                            <a class="dropdown-item" href="<?php echo base_url('mahasiswa/matkul/edit/' . wah_encode($data->id_km)); ?>">Edit</a>
                            <a class="dropdown-item" data-toggle="confirmation" data-title="Are you sure want to delete?" data-placement="left" href="<?php echo base_url('mahasiswa/matkul/delete/' . wah_encode($data->id_kmm)); ?>">Delete</a>
                          </div>
                        </div>
                      </td><?php endif; ?>
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