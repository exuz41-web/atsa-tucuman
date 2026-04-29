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

const waitForPaint = () => new Promise((resolve) => setTimeout(resolve, 25));

const loadImage = async (file) => {
    if ('createImageBitmap' in window) {
        try {
            return {
                image: await createImageBitmap(file, { imageOrientation: 'from-image' }),
                cleanup: () => {},
            };
        } catch (error) {
            // Fall back to HTMLImageElement below.
        }
    }

    const objectUrl = URL.createObjectURL(file);
    const image = await new Promise((resolve, reject) => {
        const fallbackImage = new Image();
        fallbackImage.onload = () => resolve(fallbackImage);
        fallbackImage.onerror = reject;
        fallbackImage.src = objectUrl;
    });

    return {
        image,
        cleanup: () => URL.revokeObjectURL(objectUrl),
    };
};

const decodeImageFile = async (file) => {
    if (!file || !qrCanvas) {
        return;
    }

    clearScannerError();
    showScannerStatus('Procesando foto del QR...');
    captureScanner?.setAttribute('disabled', 'disabled');

    let loaded = null;

    try {
        loaded = await loadImage(file);
        const image = loaded.image;
        const context = qrCanvas.getContext('2d', { willReadFrequently: true });
        const sourceWidth = image.width || image.naturalWidth;
        const sourceHeight = image.height || image.naturalHeight;
        const maxSideOptions = [1200, 900, 700];
        const cropRatios = [1, 0.86, 0.7];
        let code = null;

        for (const maxSide of maxSideOptions) {
            const scale = Math.min(1, maxSide / Math.max(sourceWidth, sourceHeight));
            const width = Math.max(1, Math.round(sourceWidth * scale));
            const height = Math.max(1, Math.round(sourceHeight * scale));

            qrCanvas.width = width;
            qrCanvas.height = height;
            context.clearRect(0, 0, width, height);
            context.drawImage(image, 0, 0, width, height);

            for (const cropRatio of cropRatios) {
                if (cropRatio === 1) {
                    code = decodeCanvas(context, width, height);
                } else {
                    const cropSize = Math.floor(Math.min(width, height) * cropRatio);
                    const cropX = Math.max(0, Math.floor((width - cropSize) / 2));
                    const cropY = Math.max(0, Math.floor((height - cropSize) / 2));
                    const fullImage = context.getImageData(cropX, cropY, cropSize, cropSize);
                    qrCanvas.width = cropSize;
                    qrCanvas.height = cropSize;
                    context.putImageData(fullImage, 0, 0);
                    code = decodeCanvas(context, cropSize, cropSize);
                }

                if (code?.data) {
                    break;
                }
            }

            if (code?.data) {
                break;
            }

            await waitForPaint();
        }

        if (code?.data) {
            submitWithQr(code.data);
            return;
        }

        showScannerError('No pude leer el QR de esa imagen. Sacá la foto más cerca, que solo se vea el QR grande, y evitá reflejos.');
        captureScanner?.classList.remove('d-none');
    } catch (error) {
        clearScannerStatus();
        showScannerError('No pude procesar esa foto. Probá sacar otra más cerca del QR.');
        captureScanner?.classList.remove('d-none');
    } finally {
        loaded?.cleanup();
        captureScanner?.removeAttribute('disabled');
        clearScannerStatus();
        qrImageInput.value = '';
    }
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
