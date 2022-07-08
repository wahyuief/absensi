<script src="<?php echo base_url('assets/facedetection/face-api.min.js') ?>"></script>
<script defer src="<?php echo base_url('assets/facedetection/es6-promise.min.js') ?>"></script>
<style>
  #canvas {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
      canvas {
            position: absolute;
      }
</style>
<section class="content">
      <div class="container-fluid">
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">
                        <div class="row mb-3">
                              <label for="nama_matkul" class="col-sm-2 form-label wahlabel">Mata Kuliah</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="nama_matkul" class="form-control" value="<?php echo $data->nama_matkul; ?>" readonly required>
                                    <input type="hidden" id="matkul" class="form-control" value="<?php echo wah_encode($data->id_matkul); ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="kelas" class="col-sm-2 form-label wahlabel">Kelas</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="kelas" class="form-control" value="<?php echo $data->nama_kelas; ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="dosen" class="col-sm-2 form-label wahlabel">Dosen</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="dosen" class="form-control" value="<?php echo $data->fullname; ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="semester" class="col-sm-2 form-label wahlabel">Semester</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="semester" class="form-control" value="<?php echo $data->tahun.' '.$data->keterangan; ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="jadwal" class="col-sm-2 form-label wahlabel">Jadwal</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="jadwal" class="form-control" value="<?php echo hariIndo(date('l', $data->jadwal_mulai)) .', '. date('H:i', $data->jadwal_mulai) .'-'. date('H:i', $data->jadwal_selesai); ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="status" class="col-sm-2 form-label wahlabel">Status</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="status" class="form-control" value="<?php echo ($this->absensi_model->get(['absensi.id_matkul' => $data->id_matkul, 'DAYNAME(tanggal_absen)' => date('l'), 'DATE(tanggal_absen)' => date('Y-m-d'), 'id_user' => $this->session->userdata('user_id')])->num_rows() > 0 ? 'Sudah Absen' : 'Belum Absen'); ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div id="canvas"><video id="video" width="720" height="560" autoplay muted></video></div>

                  </div>
                  <?php echo form_close();?>
            </div>
      </div>
</div>
<script defer>
const video = document.getElementById('video');
video.setAttribute('autoplay', '');
video.setAttribute('muted', '');
video.setAttribute('playsinline', '');

Promise.all([
  faceapi.nets.tinyFaceDetector.loadFromUri('/assets/facedetection/models'),
  faceapi.nets.faceRecognitionNet.loadFromUri('/assets/facedetection/models'),
  faceapi.nets.faceLandmark68Net.loadFromUri('/assets/facedetection/models'),
  faceapi.nets.faceLandmark68TinyNet.loadFromUri('/assets/facedetection/models'),
  faceapi.nets.ssdMobilenetv1.loadFromUri('/assets/facedetection/models'),
  faceapi.nets.faceExpressionNet.loadFromUri('/assets/facedetection/models')
]).then(start)

function start() {
    navigator.getUserMedia(
        { audio: false, video: {facingMode: 'user'} },
        stream => video.srcObject = stream,
        err => console.error(err)
    )

    video.addEventListener('play', async () => {
        const labeledFaceDescriptors = await loadLabeledImages()
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6)
        const canvas = faceapi.createCanvasFromMedia(video)
        document.getElementById('canvas').append(canvas)
        const displaySize = { width: video.width, height: video.height }
        faceapi.matchDimensions(canvas, displaySize)
        setInterval(async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks(true).withFaceDescriptors().withFaceExpressions()
            const resizedDetections = faceapi.resizeResults(detections, displaySize)
            const results = await resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
            faceapi.draw.drawDetections(canvas, resizedDetections)
            faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
            faceapi.draw.drawFaceExpressions(canvas, resizedDetections)
            results.forEach((result, i) => {
                var check = result.toString().match(/\((.*?)\)/)[1];
                if (check % 1 >= 0.1) {
                  simpanabsen(result.toString());
                }
                const box = resizedDetections[i].detection.box
                const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() })
                drawBox.draw(canvas)
            })
        }, 100)
    })
}

function loadLabeledImages() {
    const labels = ['<?php echo $user->fullname ?>'] 
    return Promise.all(
        labels.map(async label => {
            const descriptions = []
            for (let i = 1; i <= <?php echo $photos ?>; i++) {
                const img = await faceapi.fetchImage('/assets/facedetection/images/'+`${label}/${i}.jpg`)
                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks(true).withFaceDescriptor()
                try {
                  descriptions.push(detections.descriptor)
                } catch (error) {
                  console.log(error)
                }
            }

            return new faceapi.LabeledFaceDescriptors(label, descriptions)
        })
    )
}
</script>

<script>
      function simpanabsen(str) {
            var matkul = $('#matkul').val();
            $.ajax({
                  type: "POST",
                  data: {matkul:matkul,name:str},
                  url: "<?php echo base_url('absensi/upload') ?>",
                  success: function(data){
                        data = JSON.parse(data)
                        $('#status').val("Sudah Absen")
                  }
            });
      }
</script>