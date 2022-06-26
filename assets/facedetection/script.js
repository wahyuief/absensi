const video = document.getElementById('video')

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
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6)
        const canvas = faceapi.createCanvasFromMedia(video)
        document.body.append(canvas)
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
                const box = resizedDetections[i].detection.box
                const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() })
                drawBox.draw(canvas)
            })
        }, 100)
    })
}

function loadLabeledImages() {
    const labels = ['Wahyu'] 
    return Promise.all(
        labels.map(async label => {
            const descriptions = []
            for (let i = 1; i <= 2; i++) {
                const img = await faceapi.fetchImage('images/'+`${label}/${i}.jpg`)
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
        const result = await faceapi.detectAllFaces(canvas, new faceapi.TinyFaceDetectorOptions());
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
  
function canvas2blob(can, type = 'image/jpeg', quality = 0.97) {
    return Promise.resolve().then(() => {
      if (!can) throw new Error('Empty canvas');
      return new Promise((resolve, reject) => {
        can.toBlob(resolve, type, quality);
      });
    });
  }

function resizeToMax(can, max_size = 640) {
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