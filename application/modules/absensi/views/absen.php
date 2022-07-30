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
                        <h3>Detail Mata Kuliah</h3>
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

                        <h3>Detail Absensi</h3>
                        <div class="row mb-3">
                              <label for="tanggal_absen" class="col-sm-2 form-label wahlabel">Tanggal Absen</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="tanggal_absen" class="form-control" value="" readonly>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="latitude" class="col-sm-2 form-label wahlabel">Latitude</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="latitude" class="form-control" value="" readonly>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="longitude" class="col-sm-2 form-label wahlabel">Longitude</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="longitude" class="form-control" value="" readonly>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="lokasi_absen" class="col-sm-2 form-label wahlabel">Lokasi Absen</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="lokasi_absen" class="form-control" value="" readonly>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="foto" class="col-sm-2 form-label wahlabel">Foto</label>
                              <div class="col-sm-8 input-group">
                                    <div id="foto" class="form-control"></div>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="status" class="col-sm-2 form-label wahlabel">Status</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="status" class="form-control" value="<?php echo ($this->absensi_model->get(['absensi.id_matkul' => $data->id_matkul, 'DAYNAME(tanggal_absen)' => date('l'), 'DATE(tanggal_absen)' => date('Y-m-d'), 'id_user' => $this->session->userdata('user_id')])->num_rows() > 0 ? 'Sudah Absen' : 'Belum Absen'); ?>" readonly>
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
        { video: {} },
        stream => video.srcObject = stream,
        err => console.error(err)
    )

    video.addEventListener('play', async () => {
        const labeledFaceDescriptors = await loadLabeledImages()
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.4)
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
                var check = result.toString().split(' (');
                if (check[0] == '<?php echo $user->fullname ?>') {
                  simpanabsen(result.toString());
                  getFaceAndDescriptor(video).then((result)=>simpanfoto(result.thumbnail.b64))
                }
                const box = resizedDetections[i].detection.box
                console.log(box)
                const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() })
                drawBox.draw(canvas)
            })
        }, 1000)
    })
}

function loadLabeledImages() {
    const labels = ['<?php echo $user->fullname ?>'];
    return Promise.all(
        labels.map(async label => {
            const descriptions = []
            for (let i = 1; i <= <?php echo $photos ?>; i++) {
                  const img = await faceapi.fetchImage('/assets/facedetection/images/'+`${label}/${i}.jpg`)
                  const detections = await faceapi.detectSingleFace(img).withFaceLandmarks(true).withFaceDescriptor()
                  descriptions.push(detections.descriptor)
            }

            return new faceapi.LabeledFaceDescriptors(label, descriptions)
        })
    )
}

const getFaceAndDescriptor = (canvas, type = 'base64') => {
    if (!canvas) return Promise.reject(new Error('Foto tidak valid'));
  
    return new Promise(async (resolve, reject) => {
      try {
        const photo = {
          thumbnail: {
            b64: null,
            blob: null,
          },
          faces: [],
        };
        resizeToMax(canvas);
        const result = await faceapi.detectAllFaces(canvas, new faceapi.SsdMobilenetv1Options());
        if (!result) return reject(new Error('wajah tidak ditemukan'));
        if (result.length > 1) {
          return reject(new Error(`Foto tidak valid, ${result.length} wajah ditemukan`));
        }
        if (result.length === 0) {
          return reject(new Error('Gambar tidak berisi wajah atau terlalu jauh'));
        }
  
        let { box } = result[0];
        let region = new faceapi.Rect(box._x, box._y, box._width, box._height);
        const boxFace = box;
        let face = await faceapi.extractFaces(canvas, [region]);
  
        const landmarks = await faceapi.detectFaceLandmarksTiny(face[0]);
        box = landmarks.align();
        region = new faceapi.Rect(box._x, box._y, box._width, box._height);
        face = await faceapi.extractFaces(face[0], [region]);
        canvas2gray(face[0]);
        const blobFace = await canvas2blob(face[0]);
        let descriptor = await faceapi.computeFaceDescriptor(face[0]);
        descriptor =  btoa(String.fromCharCode.apply(null, new Uint8Array(descriptor.buffer)))
        photo.faces.push({
          blob: blobFace,
          descriptor,
        });
  
        box = boxFace;
        if (canvas.height > 240 && canvas.width > 320) {
          let x;
          let y;
          let h = canvas.height * 0.9;
          let w = box.width;
          const centerX = box.width / 2;
          const centerY = box.height / 2;
          x = box.x - centerX;
          if (x < 0) x = box.x;
          else w = box.width * 2;
          y = box.y - centerY;
          if (y < 0) y = 0;
          if (h - y < canvas.height) h -= y;
          else h = box.height;
          region = new faceapi.Rect(x, y, w, h);
        } else {
          region = new faceapi.Rect(box.x, box.y, box.width, box.height);
        }
  
        const thumbnail = await faceapi.extractFaces(canvas, [region]);
        const blobThumbnail = await canvas2blob(thumbnail[0]);
        photo.thumbnail = {
          blob: blobThumbnail,
        };
        if (type === 'base64') photo.thumbnail.b64 = thumbnail[0].toDataURL('image/jpeg');
  
        resolve(photo);
      } catch (error) {
        console.error(error);
        reject(error);
      }
    });
};
  
function canvas2blob(can, type = 'image/jpeg', quality = 1) {
    return Promise.resolve().then(() => {
      if (!can) throw new Error('Empty canvas');
      return new Promise((resolve, reject) => {
        can.toBlob(resolve, type, quality);
      });
    });
  }

function resizeToMax(can, max_size = 720) {
    if (!can || !can.width || !can.height) return;
    let { width } = can;
    let { height } = can;
    if (width <= max_size && height <= max_size) return;
    if (width > height) {
      if (width > max_size) {
        height *= max_size / width;
        width = max_size;
      }
    } else if (height > max_size) {
      width *= max_size / height;
      height = max_size;
    }
    can.width = width;
    can.height = height;
}
  
function canvas2gray(c) {
    if (!c || !c.width || !c.height) return;
    const ctx = c.getContext('2d');
  
    const idataSrc = ctx.getImageData(0, 0, c.width, c.height);
    const idataTrg = ctx.createImageData(c.width, c.height);
    const dataSrc = idataSrc.data;
    const dataTrg = idataTrg.data;
    const len = dataSrc.length;
    let i = 0;
    let luma;
  
    for (; i < len; i += 4) {
      luma = dataSrc[i] * 0.2126 + dataSrc[i + 1] * 0.7152 + dataSrc[i + 2] * 0.0722;
      dataTrg[i] = dataTrg[i + 1] = dataTrg[i + 2] = luma;
      dataTrg[i + 3] = dataSrc[i + 3];
    }
    ctx.putImageData(idataTrg, 0, 0);
}
</script>

<script>
      function simpanabsen(str) {
            navigator.geolocation.getCurrentPosition(function(location) {
                  var lat = location.coords.latitude;
                  var lon = location.coords.longitude;
                  var matkul = $('#matkul').val();
                  $.ajax({
                        type: "POST",
                        data: {matkul:matkul,name:str,latitude:lat,longitude:lon},
                        url: "<?php echo base_url('absensi/upload') ?>",
                        success: function(data){
                              data = JSON.parse(data)
                              $('#status').val("Sudah Absen")
                              detailAbsensi()
                        }
                  });
            });
      }

      function simpanfoto(str) {
            var matkul = $('#matkul').val();
            $.ajax({
                  type: "POST",
                  data: {matkul:matkul,foto:str},
                  url: "<?php echo base_url('absensi/upload_foto') ?>",
                  success: function(data){
                        data = JSON.parse(data)
                        detailAbsensi()
                  }
            });
      }

      function detailAbsensi() {
            var darta = new FormData();
            darta.append( 'id_matkul', $('#matkul').val() );
            $.ajax({
                  data: darta,
                  type: 'POST',
                  url: base_url + 'absensi/detail_absensi',
                  processData: false,
                  contentType: false,
                  success:function(data){
                        data = JSON.parse(data);
                        $('#tanggal_absen').val(data.tanggal_absen);
                        $('#latitude').val(data.latitude);
                        $('#longitude').val(data.longitude);
                        $('#lokasi_absen').val(data.lokasi_absen);
                        $('#foto').html('<a href="<?php echo base_url('assets/absensi/') ?>'+data.foto+'" target="_blank">'+data.foto+'</a>');
                  }
            });
      }

      detailAbsensi()
</script>