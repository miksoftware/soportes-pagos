@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Principal')

@section('content')
    <!-- Welcome Hero Banner -->
    <div class="welcome-hero-card">
        <div class="welcome-hero-content">
            <h2>¡Bienvenido al Sistema ASESCO BPO, {{ auth()->user()->name }}! 👋</h2>
            <p>
                @if(auth()->user()->isAdmin())
                    Tienes privilegios de <strong>Administrador</strong>. Puedes gestionar todos los usuarios, consultar y validar los soportes de pago cargados por los clientes.
                @else
                    Has iniciado sesión como <strong>Usuario Estándar</strong>. Tienes acceso a tus módulos y revisión de soportes de clientes.
                @endif
            </p>
        </div>
        <div class="d-none d-md-block">
            <span style="background: rgba(255, 255, 255, 0.2); padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; backdrop-filter: blur(4px);">
                {{ date('d/m/Y') }} &bull; PHP {{ PHP_VERSION }}
            </span>
        </div>
    </div>

    <!-- Quick Public Link Banner for WhatsApp/SMS -->
    <div style="background: #ffffff; border: 1.5px solid var(--border-color); border-radius: var(--radius-lg); padding: 18px 24px; margin-bottom: 28px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 14px;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: var(--asesco-orange-light); color: var(--asesco-orange); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            </div>
            <div>
                <div style="font-weight: 700; color: var(--text-primary); font-size: 0.95rem;">
                    📱 Enlace Móvil para Clientes:
                    <span style="color: var(--asesco-orange); font-weight: 800;" id="dashPublicUrl">{{ route('soporte.create') }}</span>
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted);">
                    Envía este link por WhatsApp o SMS para que los clientes adjunten su cédula y soporte (JPG o PDF).
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn-primary" onclick="copyDashLink()" id="btnDashCopy" style="padding: 8px 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                </svg>
                <span id="btnDashCopyText">Copiar Enlace</span>
            </button>
            <a href="{{ route('soporte.create') }}" target="_blank" class="btn-secondary" style="padding: 8px 14px;">
                Probar en Móvil
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Card 1: Soportes de Pago -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Soportes Recibidos</div>
                <div class="stat-value">{{ $totalSoportes }}</div>
            </div>
            <div class="stat-icon-wrapper stat-icon-orange">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="26" height="26">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Total Usuarios -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Usuarios</div>
                <div class="stat-value">{{ $totalUsers }}</div>
            </div>
            <div class="stat-icon-wrapper stat-icon-magenta">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="26" height="26">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Administradores -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Administradores</div>
                <div class="stat-value">{{ $adminUsers }}</div>
            </div>
            <div class="stat-icon-wrapper stat-icon-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="26" height="26">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
        </div>

        <!-- Card 4: Base de Datos & Estado -->
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Motor de Base de Datos</div>
                <div class="stat-value" style="font-size: 1.25rem; color: #10b981; display: flex; align-items: center; gap: 8px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #10b981; display: inline-block;"></span>
                    MySQL 8.4 Activo
                </div>
            </div>
            <div class="stat-icon-wrapper stat-icon-emerald">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="26" height="26">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Recent Payment Supports Received from Clients -->
    <div class="card" style="margin-bottom: 28px;">
        <div class="card-header">
            <div>
                <h3 class="card-title">Comprobantes de Pago Recientes (Clientes)</h3>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-top: 2px;">
                    Últimos comprobantes enviados a través de la ruta pública móvil.
                </p>
            </div>
            <a href="{{ route('soportes.index') }}" class="btn-primary" style="padding: 8px 16px;">
                Ver Todos los Soportes ({{ $totalSoportes }})
            </a>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Cédula</th>
                        <th>Celular</th>
                        <th>Archivo</th>
                        <th>Fecha</th>
                        <th style="text-align: right;">Descarga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSoportes as $soporte)
                        <tr>
                            <td><strong style="color: var(--asesco-orange);">#{{ $soporte->id }}</strong></td>
                            <td><strong style="color: var(--text-primary);">{{ $soporte->cedula }}</strong></td>
                            <td>{{ $soporte->celular ?? '-' }}</td>
                            <td>
                                <button type="button"
                                    onclick="openSoporteModal(this)"
                                    data-ref="#{{ $soporte->id }}"
                                    data-cedula="{{ $soporte->cedula }}"
                                    data-celular="{{ $soporte->celular ?? 'No proporcionado' }}"
                                    data-walink="{{ $soporte->celular ? 'https://wa.me/57'.preg_replace('/[^0-9]/', '', $soporte->celular) : '' }}"
                                    data-fecha="{{ $soporte->created_at ? $soporte->created_at->format('d/m/Y h:i A') : '-' }}"
                                    data-formato="{{ strtoupper($soporte->archivo_extension) }} • {{ $soporte->archivo_tamano }}"
                                    data-fileurl="{{ $soporte->file_url }}"
                                    data-ispdf="{{ $soporte->isPdf() ? '1' : '0' }}"
                                    data-downloadurl="{{ route('soportes.descargar', $soporte) }}"
                                    data-downloadname="{{ $soporte->cedula }}-{{ $soporte->created_at ? $soporte->created_at->format('d-m-Y_h-ia') : 'soporte' }}.{{ $soporte->archivo_extension }}"
                                    style="color: var(--text-primary); text-decoration: underline; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; background: none; border: none; cursor: pointer; padding: 0;"
                                    title="Visualizar en modal"
                                >
                                    @if($soporte->isPdf())
                                        📄 PDF
                                    @else
                                        🖼️ Imagen
                                    @endif
                                    <span>({{ $soporte->archivo_tamano }})</span>
                                </button>
                            </td>
                            <td>{{ $soporte->created_at->format('d/m/Y h:i A') }}</td>
                            <td style="text-align: right;">
                                <div class="action-buttons-group" style="justify-content: flex-end;">
                                    <button type="button" 
                                        class="btn-action" 
                                        onclick="openSoporteModal(this)"
                                        data-ref="#{{ $soporte->id }}"
                                        data-cedula="{{ $soporte->cedula }}"
                                        data-celular="{{ $soporte->celular ?? 'No proporcionado' }}"
                                        data-walink="{{ $soporte->celular ? 'https://wa.me/57'.preg_replace('/[^0-9]/', '', $soporte->celular) : '' }}"
                                        data-fecha="{{ $soporte->created_at ? $soporte->created_at->format('d/m/Y h:i A') : '-' }}"
                                        data-formato="{{ strtoupper($soporte->archivo_extension) }} • {{ $soporte->archivo_tamano }}"
                                        data-fileurl="{{ $soporte->file_url }}"
                                        data-ispdf="{{ $soporte->isPdf() ? '1' : '0' }}"
                                        data-downloadurl="{{ route('soportes.descargar', $soporte) }}"
                                        data-downloadname="{{ $soporte->cedula }}-{{ $soporte->created_at ? $soporte->created_at->format('d-m-Y_h-ia') : 'soporte' }}.{{ $soporte->archivo_extension }}"
                                        title="Visualizar comprobante"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    <a href="{{ route('soportes.descargar', $soporte) }}" class="btn-action" style="color: var(--asesco-orange); border-color: rgba(240, 84, 35, 0.4);" title="Descargar como: {{ $soporte->cedula }}-{{ $soporte->created_at->format('d-m-Y_h-ia') }}.{{ $soporte->archivo_extension }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                No se han recibido comprobantes de pago aún. Envía el enlace público a un cliente para recibir el primero.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Usuarios del Sistema</h3>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-top: 2px;">
                    Personal interno registrado en la plataforma.
                </p>
            </div>
            @if(auth()->user()->isAdmin())
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('users.index') }}" class="btn-secondary">
                        Ver Todos
                    </a>
                    <a href="{{ route('users.index', ['crear' => '1']) }}" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nuevo Usuario
                    </a>
                </div>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                        <tr>
                            <td>
                                <div class="user-table-cell">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="user-table-avatar">
                                    <div>
                                        <div style="font-weight: 700; color: var(--text-primary);">{{ $user->name }}</div>
                                        <div style="font-size: 0.775rem; color: var(--text-light);">ID #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
                                    {{ $user->isAdmin() ? 'Administrador' : 'Usuario' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Previsualización en Dashboard -->
    <div class="modal-overlay" id="modalSoportePreview" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog modal-dialog-lg">
            <div class="modal-header">
                <div class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="color: var(--asesco-orange);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span>Comprobante de Pago</span>
                    <span id="modalPreviewRef" style="background: rgba(240, 84, 35, 0.12); color: var(--asesco-orange); padding: 3px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">#</span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeSoporteModal()" title="Cerrar modal (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="preview-info-bar">
                    <div class="preview-info-item">
                        <span class="preview-info-label">Cédula</span>
                        <span class="preview-info-value" id="modalPreviewCedula" style="color: var(--asesco-orange);">-</span>
                    </div>
                    <div class="preview-info-item">
                        <span class="preview-info-label">Celular</span>
                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                            <span class="preview-info-value" id="modalPreviewCelular">-</span>
                            <a id="modalPreviewWa" href="#" target="_blank" style="display: none; color: #22c55e;" title="Chatear en WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="preview-info-item">
                        <span class="preview-info-label">Fecha de Envío</span>
                        <span class="preview-info-value" id="modalPreviewFecha">-</span>
                    </div>
                    <div class="preview-info-item">
                        <span class="preview-info-label">Formato / Peso</span>
                        <span class="preview-info-value" id="modalPreviewFormato">-</span>
                    </div>
                </div>

                <div class="preview-media-wrapper">
                    <img id="modalPreviewImg" src="" alt="Comprobante" class="preview-media-img" style="display: none;">
                    <iframe id="modalPreviewIframe" src="" class="preview-media-iframe" style="display: none;" title="Visor de PDF"></iframe>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeSoporteModal()">
                    Cerrar
                </button>
                <a id="modalPreviewDownloadBtn" href="#" class="btn-primary" style="padding: 10px 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Descargar Comprobante</span>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyDashLink() {
        const url = document.getElementById('dashPublicUrl').innerText.trim();
        navigator.clipboard.writeText(url).then(function() {
            const btnText = document.getElementById('btnDashCopyText');
            btnText.innerText = '¡Copiado!';
            setTimeout(() => {
                btnText.innerText = 'Copiar Enlace';
            }, 2500);
        }).catch(function(err) {
            alert('Enlace: ' + url);
        });
    }

    function openSoporteModal(element) {
        const d = element.dataset;
        const modal = document.getElementById('modalSoportePreview');

        document.getElementById('modalPreviewRef').innerText = d.ref;
        document.getElementById('modalPreviewCedula').innerText = d.cedula;
        document.getElementById('modalPreviewCelular').innerText = d.celular;
        
        const waLink = document.getElementById('modalPreviewWa');
        if (d.walink && d.walink.trim() !== '') {
            waLink.href = d.walink;
            waLink.style.display = 'inline-flex';
        } else {
            waLink.style.display = 'none';
        }

        document.getElementById('modalPreviewFecha').innerText = d.fecha;
        document.getElementById('modalPreviewFormato').innerText = d.formato;

        const imgEl = document.getElementById('modalPreviewImg');
        const iframeEl = document.getElementById('modalPreviewIframe');

        if (d.ispdf === '1') {
            imgEl.style.display = 'none';
            imgEl.src = '';
            iframeEl.style.display = 'block';
            iframeEl.src = d.fileurl;
        } else {
            iframeEl.style.display = 'none';
            iframeEl.src = '';
            imgEl.style.display = 'block';
            imgEl.src = d.fileurl;
        }

        const downloadBtn = document.getElementById('modalPreviewDownloadBtn');
        downloadBtn.href = d.downloadurl;
        downloadBtn.title = 'Descargar como: ' + d.downloadname;

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
    }

    function closeSoporteModal() {
        const modal = document.getElementById('modalSoportePreview');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');

        setTimeout(() => {
            document.getElementById('modalPreviewImg').src = '';
            document.getElementById('modalPreviewIframe').src = '';
        }, 200);
    }
</script>
@endpush
