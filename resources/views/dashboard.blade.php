@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Panel de Control')

@section('content')
    <!-- Top Executive Header: Saludo + Enlace Rápido de Clientes -->
    <div class="dash-compact-header">
        <div>
            <div class="dash-welcome-text">
                ¡Hola, <span class="text-gradient">{{ auth()->user()->name }}</span>! 👋
            </div>
            <div class="dash-welcome-sub">
                Monitoreo y gestión de comprobantes de pago en tiempo real
            </div>
        </div>

        <!-- Enlace Móvil Rápido -->
        <div class="dash-link-pill">
            <div class="dash-link-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            </div>
            <div class="dash-link-info">
                <span class="dash-link-label">Link para Clientes</span>
                <span class="dash-link-url" id="dashPublicUrl">{{ route('soporte.create') }}</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn-primary" onclick="copyDashLink()" id="btnDashCopy" style="padding: 6px 12px; font-size: 0.8rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                    </svg>
                    <span id="btnDashCopyText">Copiar</span>
                </button>
                <a href="{{ route('soporte.create') }}" target="_blank" class="btn-secondary" style="padding: 6px 10px; font-size: 0.8rem;" title="Abrir formulario en móvil">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="14" height="14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KPI Cards: Métricas Clave de Negocio -->
    <div class="dash-kpi-grid">
        <!-- KPI 1: Total Soportes -->
        <div class="dash-kpi-card">
            <div class="kpi-left">
                <span class="kpi-label">Total Comprobantes</span>
                <div class="kpi-value">{{ number_format($totalSoportes) }}</div>
                <span class="kpi-sub">Histórico acumulado</span>
            </div>
            <div class="kpi-icon kpi-icon-orange" style="background: linear-gradient(135deg, #f05423, #ea580c);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
        </div>

        <!-- KPI 2: Recibidos Hoy -->
        <div class="dash-kpi-card">
            <div class="kpi-left">
                <span class="kpi-label">Recibidos Hoy</span>
                <div class="kpi-value">{{ number_format($soportesHoy) }}</div>
                <span class="kpi-badge {{ $soportesHoy >= $soportesAyer ? 'kpi-badge-up' : 'kpi-badge-neutral' }}">
                    {{ $soportesAyer }} recibidos ayer
                </span>
            </div>
            <div class="kpi-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 18.75m3-18.75h18m-18 0h18v18H3V6.75Z" />
                </svg>
            </div>
        </div>

        <!-- KPI 3: Este Mes -->
        <div class="dash-kpi-card">
            <div class="kpi-left">
                <span class="kpi-label">Recaudos Este Mes</span>
                <div class="kpi-value">{{ number_format($soportesMes) }}</div>
                <span class="kpi-sub">{{ date('F Y') }}</span>
            </div>
            <div class="kpi-icon" style="background: linear-gradient(135deg, #dc266a, #be123c);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                </svg>
            </div>
        </div>

        <!-- KPI 4: Formatos de Archivo -->
        <div class="dash-kpi-card">
            <div class="kpi-left" style="width: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="kpi-label">Formatos Adjuntos</span>
                    <span style="font-size: 0.72rem; font-weight: 700; color: var(--text-light);">{{ $totalSoportes }} archivos</span>
                </div>
                <div class="kpi-formats-breakdown">
                    <span class="format-pill format-img">🖼️ {{ $totalImagenes }} Fotos</span>
                    <span class="format-pill format-pdf">📄 {{ $totalPdfs }} PDFs</span>
                </div>
                <div class="kpi-progress-track">
                    <div class="kpi-progress-bar" style="width: {{ $totalSoportes > 0 ? round(($totalImagenes / $totalSoportes) * 100) : 50 }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Gráfico de Tendencia (Izquierda) + Comprobantes Recientes (Derecha) -->
    <div class="dash-main-grid">
        <!-- Gráfico de Tendencia de 7 Días -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18" style="color: var(--asesco-orange);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                    <span>Tendencia de Recepción (Últimos 7 Días)</span>
                </div>
                <span style="font-size: 0.75rem; font-weight: 700; background: rgba(240, 84, 35, 0.1); color: var(--asesco-orange); padding: 3px 10px; border-radius: 12px;">
                    Envíos Diarios
                </span>
            </div>

            <!-- Canvas del Gráfico -->
            <div class="chart-container-box">
                <canvas id="supportsTrendChart"></canvas>
            </div>

            <!-- Métricas Resumen al Pie del Gráfico -->
            <div class="chart-mini-summary">
                <div class="chart-mini-item">
                    <div class="chart-mini-label">Total 7 Días</div>
                    <div class="chart-mini-val" style="color: var(--asesco-orange);">{{ array_sum($chartData) }}</div>
                </div>
                <div class="chart-mini-item">
                    <div class="chart-mini-label">Promedio Diario</div>
                    <div class="chart-mini-val">{{ round(array_sum($chartData) / 7, 1) }} / día</div>
                </div>
                <div class="chart-mini-item">
                    <div class="chart-mini-label">Pico Máximo</div>
                    <div class="chart-mini-val" style="color: #10b981;">{{ max($chartData) }} soportes</div>
                </div>
            </div>
        </div>

        <!-- Lista Compacta de Últimos Comprobantes -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="dash-card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18" style="color: var(--asesco-magenta);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>Comprobantes Recientes</span>
                </div>
                <a href="{{ route('soportes.index') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--asesco-orange); text-decoration: none;">
                    Ver todos ({{ $totalSoportes }}) &rarr;
                </a>
            </div>

            <div class="dash-feed-list">
                @forelse($recentSoportes as $soporte)
                    <div class="dash-feed-item">
                        <div class="dash-feed-left">
                            @if($soporte->isPdf())
                                <div class="dash-feed-pdf">PDF</div>
                            @else
                                <img src="{{ $soporte->file_url }}" alt="Thumb" class="dash-feed-thumb">
                            @endif
                            <div>
                                <div class="dash-feed-cedula">{{ $soporte->cedula }}</div>
                                <div class="dash-feed-meta">
                                    <span>{{ $soporte->created_at->format('d/m h:i A') }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $soporte->archivo_tamano }}</span>
                                    @if($soporte->celular)
                                        <a href="https://wa.me/57{{ preg_replace('/[^0-9]/', '', $soporte->celular) }}" target="_blank" style="color: #22c55e; display: inline-flex;" title="Escribir al cliente">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="13" height="13">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 6px;">
                            <!-- Botón Ver en Modal -->
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
                                style="width: 32px; height: 32px;"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="15" height="15">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>

                            <!-- Botón Descargar Individual -->
                            <a href="{{ route('soportes.descargar', $soporte) }}" class="btn-action" style="color: var(--asesco-orange); width: 32px; height: 32px;" title="Descargar como: {{ $soporte->cedula }}-{{ $soporte->created_at->format('d-m-Y_h-ia') }}.{{ $soporte->archivo_extension }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="15" height="15">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 36px 14px; color: var(--text-muted);">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; color: var(--text-light);">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="22" height="22">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-primary);">Aún no hay comprobantes</div>
                        <div style="font-size: 0.75rem; color: var(--text-light); margin-top: 2px;">Envía el enlace público a tus clientes para recibir el primero.</div>
                    </div>
                @endforelse
            </div>
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
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function copyDashLink() {
        const url = document.getElementById('dashPublicUrl').innerText.trim();
        navigator.clipboard.writeText(url).then(function() {
            const btnText = document.getElementById('btnDashCopyText');
            btnText.innerText = '¡Copiado!';
            setTimeout(() => {
                btnText.innerText = 'Copiar';
            }, 2500);
        }).catch(function(err) {
            alert('Enlace: ' + url);
        });
    }

    // Modal de Previsualización
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

    // Inicialización del Gráfico de Tendencia (Chart.js)
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('supportsTrendChart');
        if (!ctx) return;

        const chartCtx = ctx.getContext('2d');
        
        // Gradiente bajo la curva
        const gradient = chartCtx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(240, 84, 35, 0.35)');
        gradient.addColorStop(0.7, 'rgba(220, 38, 106, 0.08)');
        gradient.addColorStop(1, 'rgba(240, 84, 35, 0.0)');

        const labels = {!! json_encode($chartLabels) !!};
        const data = {!! json_encode($chartData) !!};

        new Chart(chartCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Comprobantes Recibidos',
                    data: data,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#f05423',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#f05423',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#dc266a',
                    pointHoverBorderColor: '#ffffff',
                    tension: 0.38
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111722',
                        titleColor: '#ffffff',
                        bodyColor: '#f05423',
                        titleFont: {
                            family: "'Inter', sans-serif",
                            size: 12,
                            weight: '700'
                        },
                        bodyFont: {
                            family: "'Inter', sans-serif",
                            size: 13,
                            weight: '800'
                        },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' comprobante(s)';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11,
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 1,
                            color: '#94a3b8',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 11
                            },
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
