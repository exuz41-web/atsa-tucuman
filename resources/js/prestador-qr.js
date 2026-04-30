import jsQR from 'jsqr';

const form = document.querySelector('[data-auto-scan]');
const qrInput = document.getElementById('qr-input');
const startScanner = document.getElementById('start-scanner');
const retryScanner = document.getElementById('retry-scanner');
const stopScanner = document.getElementById('stop-scanner');
const scannerPanel = document.getElementById('scanner-panel');
const scannerStatus = document.getElementById('scanner-status');
const scannerError = document.getElementById('scanner-error');
const scannerHelp = document.getElementById('scanner-help');
const qrVideo = document.getElementById('qr-video');
const qrCanvas = document.getElementById('qr-canvas');

let scannerStream = null;
let animationFrame = null;
let isSubmitting = false;
let nativeDetectorPromise = null;

const showScannerError = (message) => {
    if (!scannerError) return;
    scannerStatus?.classList.add('d-none');
    scannerError.textContent = message;
    scannerError.classList.remove('d-none');
    scannerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

const clearScannerError = () => {
    scannerError?.classList.add('d-none');
};

const showScannerStatus = (message) => {
    if (!scannerStatus) return;
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
};

const setImageOnlyState = () => {
    startScanner?.classList.add('d-none');
    retryScanner?.classList.add('d-none');

    if (scannerHelp) {
        scannerHelp.textContent = 'El lector QR necesita abrir la cámara en vivo. Para usarlo desde celular, ingresá al portal con HTTPS.';
    }
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
    if (!value || isSubmitting || !qrInput || !form) return;

    isSubmitting = true;
    showScannerStatus('QR leído. Validando afiliado...');
    qrInput.value = value;
    stopCamera();
    form.submit();
};

const getNativeDetector = async () => {
    if (!('BarcodeDetector' in window)) return null;

    if (!nativeDetectorPromise) {
        nativeDetectorPromise = (async () => {
            try {
                const formats = await window.BarcodeDetector.getSupportedFormats?.();
                if (Array.isArray(formats) && !formats.includes('qr_code')) return null;
                return new window.BarcodeDetector({ formats: ['qr_code'] });
            } catch {
                return null;
            }
        })();
    }

    return nativeDetectorPromise;
};

const decodeNative = async (source) => {
    const detector = await getNativeDetector();
    if (!detector) return null;

    try {
        const codes = await detector.detect(source);
        return codes?.[0]?.rawValue || null;
    } catch {
        return null;
    }
};

const scanFrame = async () => {
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

    const nativeCode = await decodeNative(qrVideo);
    if (nativeCode) {
        submitWithQr(nativeCode);
        return;
    }

    qrCanvas.width = width;
    qrCanvas.height = height;

    const context = qrCanvas.getContext('2d', { willReadFrequently: true });
    context.drawImage(qrVideo, 0, 0, width, height);

    const code = decodeCanvas(context, width, height);

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

const startCamera = async () => {
    clearScannerError();

    if (!window.isSecureContext) {
        showScannerError('No se puede abrir el lector QR porque el portal está en HTTP. En celular Chrome exige HTTPS para usar cámara en vivo.');
        setImageOnlyState();
        return;
    }

    if (!navigator.mediaDevices?.getUserMedia) {
        showScannerError('Tu dispositivo o navegador no permite abrir la cámara en vivo para leer QR.');
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

        if (scannerHelp) {
            scannerHelp.textContent = 'Apuntá la cámara al QR del carnet digital del afiliado. Al detectarlo, el sistema valida automáticamente.';
        }

        animationFrame = requestAnimationFrame(scanFrame);
    } catch {
        stopCamera();
        showScannerError('No se pudo abrir la cámara. Verificá los permisos del navegador y volvé a intentar.');
        retryScanner?.classList.remove('d-none');
    }
};

startScanner?.addEventListener('click', startCamera);
retryScanner?.addEventListener('click', startCamera);

stopScanner?.addEventListener('click', () => {
    stopCamera();
    setIdleState();
});

window.addEventListener('beforeunload', stopCamera);

if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia) {
    setImageOnlyState();

    if (!window.isSecureContext && scannerError) {
        scannerError.textContent = 'Lector QR no disponible en HTTP. Para escanear sin foto desde celular, el portal debe abrirse por HTTPS.';
        scannerError.classList.remove('d-none');
        scannerError.className = scannerError.className.replace('alert-warning', 'alert-info');
    }
}

if (form?.dataset.autoScan === '1') {
    startCamera();
}
