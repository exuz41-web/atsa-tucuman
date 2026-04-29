import jsQR from 'jsqr';

const form = document.querySelector('[data-auto-scan]');
const qrInput = document.getElementById('qr-input');
const startScanner = document.getElementById('start-scanner');
const retryScanner = document.getElementById('retry-scanner');
const captureScanner = document.getElementById('capture-scanner');
const stopScanner = document.getElementById('stop-scanner');
const scannerPanel = document.getElementById('scanner-panel');
const scannerError = document.getElementById('scanner-error');
const qrVideo = document.getElementById('qr-video');
const qrCanvas = document.getElementById('qr-canvas');
const qrImageInput = document.getElementById('qr-image-input');

let scannerStream = null;
let animationFrame = null;
let isSubmitting = false;

const showScannerError = (message) => {
    if (!scannerError) {
        return;
    }

    scannerError.textContent = message;
    scannerError.classList.remove('d-none');
};

const clearScannerError = () => {
    scannerError?.classList.add('d-none');
};

const setIdleState = () => {
    startScanner?.classList.remove('d-none');
    retryScanner?.classList.add('d-none');
    captureScanner?.classList.add('d-none');
};

const stopCamera = () => {
    if (animationFrame) {
        cancelAnimationFrame(animationFrame);
        animationFrame = null;
    }

    if (scannerStream) {
        scannerStream.getTracks().forEach((track) => track.stop());
        scannerStream = null;
    }

    if (qrVideo) {
        qrVideo.srcObject = null;
    }

    scannerPanel?.classList.add('d-none');
};

const submitWithQr = (value) => {
    if (!value || isSubmitting || !qrInput || !form) {
        return;
    }

    isSubmitting = true;
    qrInput.value = value;
    stopCamera();
    form.submit();
};

const scanFrame = () => {
    if (!qrVideo || !qrCanvas || qrVideo.readyState < HTMLMediaElement.HAVE_CURRENT_DATA) {
        animationFrame = requestAnimationFrame(scanFrame);
        return;
    }

    const width = qrVideo.videoWidth;
    const height = qrVideo.videoHeight;

    if (!width || !height) {
        animationFrame = requestAnimationFrame(scanFrame);
        return;
    }

    qrCanvas.width = width;
    qrCanvas.height = height;

    const context = qrCanvas.getContext('2d', { willReadFrequently: true });
    context.drawImage(qrVideo, 0, 0, width, height);

    const imageData = context.getImageData(0, 0, width, height);
    const code = jsQR(imageData.data, imageData.width, imageData.height, {
        inversionAttempts: 'dontInvert',
    });

    if (code?.data) {
        submitWithQr(code.data);
        return;
    }

    animationFrame = requestAnimationFrame(scanFrame);
};

const decodeImageFile = (file) => {
    if (!file || !qrCanvas) {
        return;
    }

    clearScannerError();

    const image = new Image();
    image.onload = () => {
        const maxSide = 1600;
        const scale = Math.min(1, maxSide / Math.max(image.naturalWidth, image.naturalHeight));
        const width = Math.max(1, Math.round(image.naturalWidth * scale));
        const height = Math.max(1, Math.round(image.naturalHeight * scale));

        qrCanvas.width = width;
        qrCanvas.height = height;

        const context = qrCanvas.getContext('2d', { willReadFrequently: true });
        context.drawImage(image, 0, 0, width, height);

        const imageData = context.getImageData(0, 0, width, height);
        const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: 'attemptBoth',
        });

        URL.revokeObjectURL(image.src);

        if (code?.data) {
            submitWithQr(code.data);
            return;
        }

        showScannerError('No pude leer el QR de esa imagen. Acercá más la cámara, evitá reflejos y probá de nuevo.');
        captureScanner?.classList.remove('d-none');
    };

    image.onerror = () => {
        showScannerError('No pude abrir esa imagen. Probá tomar la foto nuevamente.');
        captureScanner?.classList.remove('d-none');
    };

    image.src = URL.createObjectURL(file);
};

const startCamera = async () => {
    clearScannerError();

    if (!navigator.mediaDevices?.getUserMedia) {
        showScannerError('Este navegador no habilita cámara en vivo desde esta dirección. Tocá "Tomar foto del QR" y lo leo desde la imagen.');
        retryScanner?.classList.remove('d-none');
        captureScanner?.classList.remove('d-none');
        return;
    }

    try {
        scannerStream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: { ideal: 'environment' },
                width: { ideal: 1280 },
                height: { ideal: 720 },
            },
            audio: false,
        });

        qrVideo.srcObject = scannerStream;
        await qrVideo.play();

        scannerPanel.classList.remove('d-none');
        startScanner?.classList.add('d-none');
        retryScanner?.classList.add('d-none');
        animationFrame = requestAnimationFrame(scanFrame);
    } catch (error) {
        stopCamera();
        showScannerError('No se pudo abrir la cámara en vivo. Permití el acceso o tocá "Tomar foto del QR".');
        retryScanner?.classList.remove('d-none');
        captureScanner?.classList.remove('d-none');
    }
};

startScanner?.addEventListener('click', startCamera);
retryScanner?.addEventListener('click', startCamera);
captureScanner?.addEventListener('click', () => qrImageInput?.click());
qrImageInput?.addEventListener('change', (event) => decodeImageFile(event.target.files?.[0]));

stopScanner?.addEventListener('click', () => {
    stopCamera();
    setIdleState();
});

window.addEventListener('beforeunload', stopCamera);

if (form?.dataset.autoScan === '1') {
    startCamera();
}
