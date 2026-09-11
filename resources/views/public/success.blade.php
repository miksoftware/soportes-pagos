<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="theme-color" content="#10b981">
    <title>Soporte Enviado - ASESCO BPO</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo_asesco.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #f05423;
            --success: #10b981;
            --gradient: linear-gradient(130deg, #10b981 0%, #059669 100%);
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-sub: #475569;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 20px;
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
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .success-card {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            padding: 36px 24px;
            text-align: center;
            border: 1px solid var(--border);
            position: relative;
        }

        .check-circle {
            width: 76px;
            height: 76px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.35);
            animation: bounceScale 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes bounceScale {
            0% { transform: scale(0.3); opacity: 0; }
            70% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .success-message {
            font-size: 0.9rem;
            color: var(--text-sub);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .receipt-summary-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            text-align: left;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 0.85rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .summary-value {
            color: var(--text-main);
            font-weight: 700;
        }

        .btn-new-support {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #f05423 0%, #db2777 100%);
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(240, 84, 35, 0.25);
            transition: transform 0.15s ease;
        }

        .btn-new-support:active {
            transform: scale(0.98);
        }

        .brand-footer {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.775rem;
            color: var(--text-muted);
        }

        .brand-footer img {
            height: 22px;
            width: auto;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="check-circle">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.6" stroke="currentColor" width="38" height="38">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>

        <h1 class="success-title">¡Soporte Registrado!</h1>
        <p class="success-message">
            Hemos recibido satisfactoriamente tu comprobante de pago. Un gestor de cobro de ASESCO BPO validará tu soporte en el menor tiempo posible.
        </p>

        <div class="receipt-summary-box">
            <div class="summary-row">
                <span class="summary-label">Radicado de Envío:</span>
                <span class="summary-value" style="color: #f05423;">#{{ $soporte->id ?? 'CONFIRMADO' }}</span>
            </div>
            @if($soporte && $soporte->cedula)
                <div class="summary-row">
                    <span class="summary-label">Cédula:</span>
                    <span class="summary-value">{{ $soporte->cedula }}</span>
                </div>
            @endif
            @if($soporte && $soporte->celular)
                <div class="summary-row">
                    <span class="summary-label">Teléfono:</span>
                    <span class="summary-value">{{ $soporte->celular }}</span>
                </div>
            @endif
            <div class="summary-row">
                <span class="summary-label">Fecha y Hora:</span>
                <span class="summary-value">{{ $soporte ? $soporte->created_at->format('d/m/Y h:i A') : date('d/m/Y h:i A') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tipo de Comprobante:</span>
                <span class="summary-value" style="text-transform: uppercase;">
                    {{ $soporte->tipo ?? 'ARCHIVO' }} ({{ $soporte->archivo_extension ?? 'OK' }})
                </span>
            </div>
        </div>

        <a href="{{ route('soporte.create') }}" class="btn-new-support">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Enviar otro comprobante
        </a>

        <div class="brand-footer">
            <img src="{{ asset('images/logo_asesco.png') }}" alt="ASESCO">
            <span>Gestión de Cobranzas y Asesorías ASESCO BPO</span>
        </div>
    </div>
</body>
</html>
