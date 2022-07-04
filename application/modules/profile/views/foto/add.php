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
            <div id="infopendaftaran" class="alert alert-success" style="display: none;">
                  <i class="fas fa-info"></i> Pendaftaran wajah berhasil.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <div id="warningpendaftaran" class="alert alert-danger" style="display: none;">
                  <i class="fas fa-warning"></i> Pendaftaran wajah gagal, silakan coba lagi, pastikan wajah terlihat jelas.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <div class="card">
                  <?php echo form_open();?>
                  <div class="card-body rounded login-card-body">
                        <div class="row mb-3">
                              <label for="nik" class="col-sm-2 form-label wahlabel">NIK</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="nik" class="form-control" value="<?php echo $user->username; ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">

                              <label for="user" class="col-sm-2 form-label wahlabel">Nama Mahasiswa</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="user" class="form-control" value="<?php echo $user->fullname; ?>" readonly required>
                                    <input type="hidden" name="user" id="iduser" value="<?php echo wah_encode($user->id); ?>">
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="jumlah" class="col-sm-2 form-label wahlabel">Foto Tersimpan</label>
                              <div class="col-sm-8 input-group">
                                    <input type="text" id="jumlah" class="form-control" value="<?php echo count($foto); ?>" readonly required>
                                    <div class="input-group-append">
                                          <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                              </div>
                        </div>
                        <div class="row mb-3">
                              <label for="ambil" class="col-sm-2 form-label wahlabel"></label>
                              <div class="col-sm-8">
                                    <input type="button" id="ambil" class="form-control btn btn-primary" value="<?php if(!$foto): ?>Ambil Foto<?php else: ?>Perbaharui Wajah<?php endif; ?>">
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
        const canvas = faceapi.createCanvasFromMedia(video)
        document.getElementById('canvas').append(canvas)
        const displaySize = { width: video.width, height: video.height }
        faceapi.matchDimensions(canvas, displaySize)
    })
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
        const result = await faceapi.detectAllFaces(canvas, new faceapi.TinyFaceDetectorOptions());
        if (!result) {
            $('#ambil').val('Ambil Foto');
            $('#warningpendaftaran').css('display', 'block');
            $(window).scrollTop(0);
            setTimeout(() => {
                  window.location.replace(base_url + 'profile/foto/add');
            }, 3000);
            return reject(new Error('wajah tidak ditemukan'));
        }

        if (result.length > 1) {
            $('#ambil').val('Ambil Foto');
            $('#warningpendaftaran').css('display', 'block');
            $(window).scrollTop(0);
            setTimeout(() => {
                  window.location.replace(base_url + 'profile/foto/add');
            }, 3000);
            return reject(new Error(`Foto tidak valid, ${result.length} wajah ditemukan`));
        }

        if (result.length === 0) {
            $('#ambil').val('Ambil Foto');
            $('#warningpendaftaran').css('display', 'block');
            $(window).scrollTop(0);
            setTimeout(() => {
                  window.location.replace(base_url + 'profile/foto/add');
            }, 3000);
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
      $('#ambil').on('click', function(){
            $('#ambil').val('Proses..');
            document.getElementById("ambil").disabled = true;
            for (let index = 1; index <= 50; index++) {
                  try {
                        getFaceAndDescriptor(video).then((result)=>simpanfoto(result.thumbnail.b64, index))
                  } catch (error) {
                        console.log(error)
                        alert('Pendaftaran wajah gagal, silakan coba lagi');
                        $('#ambil').val('Ambil Foto');
                  }
            }
      })

      function simpanfoto(str, index) {
            var user = $('#iduser').val()
            $.ajax({
                  type: "POST",
                  data: {user:user,foto:str,index:index},
                  url: "<?php echo base_url('profile/foto/upload') ?>",
                  success: function(data){
                        $('#ambil').val('Ambil Foto');
                        document.getElementById("ambil").disabled = false;
                        data = JSON.parse(data)
                        $('#jumlah').val(data.jumlah)
                        if (data.jumlah >= 50) {
                            $('#infopendaftaran').css('display', 'block');
                            $(window).scrollTop(0);
                            setTimeout(() => {
                              window.location.replace(base_url + 'profile/foto');
                            }, 3000);
                        }
                  }
            });
      }
</script>