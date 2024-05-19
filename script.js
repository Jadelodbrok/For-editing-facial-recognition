document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('video');
    const startButton = document.getElementById('startButton');
    const captureButton = document.getElementById('captureButton');

    // Load models
    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('http://localhost/gabfinal/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('http://localhost/gabfinal/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('http://localhost/gabfinal/models')
    ]).then(startVideo)
    .catch(error => {
        console.error('Error loading models:', error);
    });

    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.play();
            })
            .catch(err => console.error(err));
    }

    startButton.addEventListener('click', () => {
        video.style.display = 'block';
        startVideo();

        video.addEventListener('play', async () => {
            const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptors();

            if (detections.length > 0) {
                const descriptor = detections[0].descriptor;

                const response = await fetch('face_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ descriptor })
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = 'main.php';
                } else {
                    alert('Face not recognized');
                }
            } else {
                alert('No face detected. Please try again.');
            }
        });
    });

    // Event listener for the capture button
    captureButton.addEventListener('click', () => {
        video.style.display = 'block';
        startVideo();
    });
});
