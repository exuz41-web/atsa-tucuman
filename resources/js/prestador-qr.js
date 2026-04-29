import jsQR from 'jsqr';

const form = document.querySelector('[data-auto-scan]');
const qrInput = document.getElementById('qr-input');
const startScanner = document.getElementById('start-scanner');
const retryScanner = document.getElementById('retry-scanner');
const captureScanner = document.getElementById('capture-scanner');
const stopScanner = document.getElementById('stop-scanner');
const scannerPanel = document.getElementById('scanner-panel');
const scannerStatus = document.getElementById('scanner-status');
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

    scannerStatus?.classList.add('d-none');
    scannerError.textContent = message;
    scannerError.classList.remove('d-none');
    scannerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

const clearScannerError = () => {
    scannerError?.classList.add('d-none');
};

const showScannerStatus = (message) => {
    if (!scannerStatus) {
        return;
    }

    scannerError?.classList.add('d-none');
    scannerStatus.textContent = message;
    scannerStatus.classList.remove('d-none');
    scannerStatus.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

const clearScannerStatus = () => {
    scannerStatus?.classList.add('d-none');
};

const setIdleState = () => {
    startScanner?.classList.remove('d-none');
    retryScanner?.classList.add('d-none');
    captureScanner?.classList.add('d-none');
};

const setImageOnlyState = () => {
    startScanner?.classList.add('d-none');
    retryScanner?.classList.add('d-none');
    captureScanner?.classList.remove('d-none');
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
    showScannerStatus('QR leído. Validando afiliado...');
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

const decodeCanvas = (context, width, height) => {
    const imageData = context.getImageData(0, 0, width, height);

    return jsQR(imageData.data, imageData.width, imageData.height, {
        inversionAttempts: 'attemptBoth',
    });
};

const drawImageVariant = (context, image, width, height, rotation = 0, mirror = false) => {
    qrCanvas.width = width;
    qrCanvas.height = height;
    context.save();
    context.clearRect(0, 0, width, height);

    if (rotation === 90) {
        context.translate(width, 0);
        context.rotate(Math.PI / 2);
    } else if (rotation === 180) {
        context.translate(width, height);
        context.rotate(Math.PI);
    } else if (rotation === 270) {
        context.translate(0, height);
        context.rotate((3 * Math.PI) / 2);
    }

    if (mirror) {
        context.scale(-1, 1);
        context.translate(-image.naturalWidth, 0);
    }

    if (rotation === 90 || rotation === 270) {
        context.drawImage(image, 0, 0, image.naturalHeight, image.naturalWidth);
    } else {
        context.drawImage(image, 0, 0, width, height);
    }

    context.restore();
};

const decodeImageFile = (file) => {
    if (!file || !qrCanvas) {
        return;
    }

    clearScannerError();
    showScannerStatus('Procesando foto del QR...');
    captureScanner?.setAttribute('disabled', 'disabled');

    const image = new Image();
    image.onload = () => {
        const context = qrCanvas.getContext('2d', { willReadFrequently: true });
        const maxSideOptions = [2600, 2000, 1600, 1200, 900];
        const rotations = [0, 90, 180, 270];
        let code = null;

        for (const maxSide of maxSideOptions) {
            const scale = Math.min(1, maxSide / Math.max(image.naturalWidth, image.naturalHeight));

            for (const rotation of rotations) {
                const rotated = rotation === 90 || rotation === 270;
                const width = Math.max(1, Math.round((rotated ? image.naturalHeight : image.naturalWidth) * scale));
                const height = Math.max(1, Math.round((rotated ? image.naturalWidth : image.naturalHeight) * scale));

                qrCanvas.width = width;
                qrCanvas.height = height;
                context.save();
                context.clearRect(0, 0, width, height);

                if (rotation === 90) {
                    context.translate(width, 0);
                    context.rotate(Math.PI / 2);
                    context.drawImage(image, 0, 0, height, width);
                } else if (rotation === 180) {
                    context.translate(width, height);
                    context.rotate(Math.PI);
                    context.drawImage(image, 0, 0, width, height);
                } else if (rotation === 270) {
                    context.translate(0, height);
                    context.rotate((3 * Math.PI) / 2);
                    context.drawImage(image, 0, 0, height, width);
                } else {
                    context.drawImage(image, 0, 0, width, height);
                }

                context.restore();

                code = decodeCanvas(context, width, height);

                if (code?.data) {
                    break;
                }

                const cropSize = Math.floor(Math.min(width, height) * 0.82);
                const cropX = Math.floor((width - cropSize) / 2);
                const cropY = Math.floor((height - cropSize) / 2);

                const fullImage = context.getImageData(cropX, cropY, cropSize, cropSize);
                qrCanvas.width = cropSize;
                qrCanvas.height = cropSize;
                context.putImageData(fullImage, 0, 0);

                code = decodeCanvas(context, cropSize, cropSize);

                if (code?.data) {
                    break;
                }
            }

            if (code?.data) {
                break;
            }
        }

        URL.revokeObjectURL(image.src);
        captureScanner?.removeAttribute('disabled');
        clearScannerStatus();

        if (code?.data) {
            submitWithQr(code.data);
            return;
        }

        showScannerError('No pude leer el QR de esa imagen. Acercá más la cámara, evitá reflejos y probá de nuevo.');
        captureScanner?.classList.remove('d-none');
    };

    image.onerror = () => {
        captureScanner?.removeAttribute('disabled');
        clearScannerStatus();
        showScannerError('No pude abrir esa imagen. Probá tomar la foto nuevamente.');
        captureScanner?.classList.remove('d-none');
    };

    image.src = URL.createObjectURL(file);
    qrImageInput.value = '';
};

const startCamera = async () => {
    clearScannerError();

    if (!window.isSecureContext) {
        setImageOnlyState();
        return;
    }

    if (!navigator.mediaDevices?.getUserMedia) {
        setImageOnlyState();
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
        showScannerError('No se pudo abrir la cámara en vivo. Permití el acceso o usá la foto del QR.');
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

if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
    setImageOnlyState();
}

if (form?.dataset.autoScan === '1') {
    startCamera();
}
