<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#f05423">
    <title>Enviar Soporte de Pago - ASESCO BPO</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_asesco.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #f05423;
            --primary-hover: #dc481b;
            --gradient: linear-gradient(130deg, #f05423 0%, #ea580c 25%, #e11d48 70%, #c01358 100%);
            --gradient-btn: linear-gradient(135deg, #e8452e 0%, #db2777 100%);
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-sub: #475569;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-focus: #f05423;
            --radius: 18px;
            --radius-input: 14px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 0;
        }

        /* Mobile top decorative banner */
        .mobile-header-banner {
            width: 100%;
            background: var(--gradient);
            padding: 28px 20px 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 20px rgba(240, 84, 35, 0.2);
        }

        .mobile-logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 26px;
            border-radius: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            margin-bottom: 16px;
        }

        .mobile-logo-img {
            height: 64px;
            width: auto;
            max-width: 240px;
            object-fit: contain;
            display: block;
        }

        .mobile-banner-title {
            color: #ffffff;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .mobile-banner-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.85rem;
            margin-top: 4px;
            max-width: 320px;
            line-height: 1.4;
        }

        /* Floating form container */
        .mobile-card-container {
            width: 100%;
            max-width: 480px;
            padding: 0 16px;
            margin-top: -24px;
            margin-bottom: 30px;
            z-index: 10;
        }

        .mobile-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            padding: 28px 22px;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-sub);
            margin-bottom: 8px;
        }

        .optional-badge {
            font-size: 0.725rem;
            font-weight: 500;
            color: var(--text-muted);
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .mobile-input {
            width: 100%;
            height: 52px;
            padding: 12px 16px 12px 46px;
            font-size: 1rem; /* >= 16px prevents iOS zoom */
            font-weight: 500;
            color: var(--text-main);
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-input);
            outline: none;
            transition: all 0.2s ease;
        }

        .mobile-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(240, 84, 35, 0.15);
        }

        .mobile-input.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        .error-text {
            color: #ef4444;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* File Upload Area */
        .upload-dropzone {
            border: 2px dashed var(--border);
            border-radius: var(--radius-input);
            padding: 22px 16px;
            text-align: center;
            background: #fbfcfe;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }

        .upload-dropzone:hover, .upload-dropzone.dragover {
            border-color: var(--primary);
            background: rgba(240, 84, 35, 0.04);
        }

        .upload-icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(240, 84, 35, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }

        .upload-text-main {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .upload-text-sub {
            font-size: 0.775rem;
            color: var(--text-muted);
        }

        .file-hidden-input {
            position: absolute;
            inset: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        /* Selected file preview card */
        .file-preview-card {
            display: none;
            margin-top: 12px;
            background: #ffffff;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-input);
            padding: 12px;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .preview-thumb {
            width: 54px;
            height: 54px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .preview-pdf-badge {
            width: 54px;
            height: 54px;
            border-radius: 10px;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .preview-details {
            flex: 1;
            min-width: 0;
        }

        .preview-filename {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-filesize {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .btn-remove-file {
            background: none;
            border: none;
            color: #94a3b8;
            padding: 6px;
            cursor: pointer;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove-file:hover {
            color: #ef4444;
            background: #fee2e2;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            height: 54px;
            background: var(--gradient-btn);
            color: #ffffff;
            border: none;
            border-radius: var(--radius-input);
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(232, 69, 46, 0.35);
            transition: all 0.2s ease;
            margin-top: 26px;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        /* Security trust note */
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 0.775rem;
            color: var(--text-muted);
            text-align: center;
        }

        /* Mobile footer */
        .mobile-footer {
            margin-top: auto;
            padding: 20px;
            font-size: 0.75rem;
            color: var(--text-muted);
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Top Visual Banner -->
    <div class="mobile-header-banner">
        <div class="mobile-logo-badge">
            <img src="{{ asset('images/logo_asesco.png') }}" alt="ASESCO BPO" class="mobile-logo-img">
        </div>
        <h1 class="mobile-banner-title">Reporte de Pago</h1>
        <p class="mobile-banner-subtitle">
            Ingresa tu identificación y sube el comprobante de pago de tu obligación con ASESCO BPO.
        </p>
    </div>

    <!-- Main Mobile Card -->
    <div class="mobile-card-container">
        <div class="mobile-card">
            @if ($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 14px; border-radius: 12px; margin-bottom: 20px;">
                    <div style="color: #991b1b; font-size: 0.85rem; font-weight: 700; margin-bottom: 4px;">
                        ⚠️ Por favor revisa los datos ingresados:
                    </div>
                    <ul style="color: #b91c1c; font-size: 0.8rem; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('soporte.store') }}" method="POST" enctype="multipart/form-data" id="soporteForm">
                @csrf

                <!-- Cédula -->
                <div class="form-group">
                    <label for="cedula" class="form-label">
                        <span>Número de Cédula / Documento <span style="color: #ef4444;">*</span></span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="22" height="22">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="cedula" 
                            id="cedula" 
                            class="mobile-input @error('cedula') is-invalid @enderror" 
                            placeholder="Ej. 1020304050" 
                            value="{{ old('cedula') }}" 
                            inputmode="numeric" 
                            required 
                            autofocus
                        >
                    </div>
                    @error('cedula')
                        <div class="error-text">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Celular (Opcional) -->
                <div class="form-group">
                    <label for="celular" class="form-label">
                        <span>Teléfono / WhatsApp</span>
                        <span class="optional-badge">Opcional</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="22" height="22">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                        </span>
                        <input 
                            type="tel" 
                            name="celular" 
                            id="celular" 
                            class="mobile-input @error('celular') is-invalid @enderror" 
                            placeholder="Ej. 3001234567" 
                            value="{{ old('celular') }}" 
                            inputmode="tel"
                        >
                    </div>
                    @error('celular')
                        <div class="error-text">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Soporte de Pago (Imagen o PDF) -->
                <div class="form-group">
                    <label class="form-label">
                        <span>Soporte de Pago (Imagen o PDF) <span style="color: #ef4444;">*</span></span>
                    </label>

                    <div class="upload-dropzone" id="dropzoneBox">
                        <input 
                            type="file" 
                            name="soporte" 
                            id="soporteInput" 
                            class="file-hidden-input" 
                            accept="image/png, image/jpeg, image/jpg, image/webp, application/pdf" 
                            required
                        >
                        <div class="upload-icon-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="26" height="26">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </div>
                        <div class="upload-text-main" id="dropzoneText">Toca aquí para seleccionar foto o PDF</div>
                        <div class="upload-text-sub">Formatos: JPG, PNG, WEBP o PDF (Máx. 12 MB)</div>
                    </div>

                    <!-- File Preview Card -->
                    <div class="file-preview-card" id="filePreviewCard">
                        <img id="imgPreview" class="preview-thumb" src="" alt="Vista previa" style="display: none;">
                        <div id="pdfBadge" class="preview-pdf-badge" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>PDF</span>
                        </div>
                        <div class="preview-details">
                            <div class="preview-filename" id="previewFilename">comprobante.jpg</div>
                            <div class="preview-filesize" id="previewFilesize">1.2 MB</div>
                        </div>
                        <button type="button" class="btn-remove-file" id="btnRemoveFile" title="Cambiar archivo">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @error('soporte')
                        <div class="error-text">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit" id="btnSubmit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                    </svg>
                    <span id="btnSubmitText">Enviar Comprobante de Pago</span>
                </button>

                <!-- Security Trust -->
                <div class="security-note">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="15" height="15">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                    </svg>
                    <span>Información encriptada y enviada directamente a ASESCO BPO</span>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile Footer -->
    <footer class="mobile-footer">
        &copy; {{ date('Y') }} ASESCO BPO &bull; Todos los derechos reservados.
    </footer>

    <!-- Interactive Mobile Upload Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('soporteInput');
            const dropzone = document.getElementById('dropzoneBox');
            const dropzoneText = document.getElementById('dropzoneText');
            const previewCard = document.getElementById('filePreviewCard');
            const imgPreview = document.getElementById('imgPreview');
            const pdfBadge = document.getElementById('pdfBadge');
            const filenameEl = document.getElementById('previewFilename');
            const filesizeEl = document.getElementById('previewFilesize');
            const removeBtn = document.getElementById('btnRemoveFile');
            const form = document.getElementById('soporteForm');
            const submitBtn = document.getElementById('btnSubmit');
            const submitText = document.getElementById('btnSubmitText');

            function formatBytes(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                filenameEl.textContent = file.name;
                filesizeEl.textContent = formatBytes(file.size);

                const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

                if (isPdf) {
                    imgPreview.style.display = 'none';
                    pdfBadge.style.display = 'flex';
                } else {
                    pdfBadge.style.display = 'none';
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imgPreview.src = event.target.result;
                        imgPreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }

                previewCard.style.display = 'flex';
                dropzone.style.display = 'none';
            });

            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                input.value = '';
                previewCard.style.display = 'none';
                dropzone.style.display = 'block';
                imgPreview.src = '';
            });

            // Prevent double submission & show loading
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitText.textContent = 'Enviando soporte...';
            });
        });
    </script>
</body>
</html>
