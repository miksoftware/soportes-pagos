@extends('layouts.app')

@section('title', 'Soportes de Pago')
@section('page-title', 'Comprobantes de Pago')

@section('content')
    <!-- Top Bar: Shareable Link + Batch Date Download -->
    <div style="display: grid; grid-template-columns: 1fr; gap: 18px; margin-bottom: 24px;">
        
        <!-- Shareable Public Link Card -->
        <div style="background: linear-gradient(135deg, rgba(240, 84, 35, 0.08) 0%, rgba(220, 38, 106, 0.08) 100%); border: 1.5px dashed rgba(240, 84, 35, 0.35); border-radius: var(--radius-lg); padding: 18px 22px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: var(--asesco-gradient-btn); color: #ffffff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 800; color: var(--text-primary); font-size: 0.95rem;">
                        Enlace Público para Clientes:
                        <strong style="color: var(--asesco-orange);" id="publicUrlText">{{ route('soporte.create') }}</strong>
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        Comparte este enlace por WhatsApp o SMS para que los clientes suban su soporte de pago directamente desde su celular.
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-primary" id="btnCopyLink" onclick="copyPublicLink()" style="padding: 8px 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                    </svg>
                    <span id="btnCopyText">Copiar Enlace</span>
                </button>
                <a href="{{ route('soporte.create') }}" target="_blank" class="btn-secondary" style="padding: 8px 14px;" title="Abrir en móvil">
                    Abrir Formulario
                </a>
            </div>
        </div>

        <!-- Date Range Download Toolbar (ZIP) -->
        <div style="background: #ffffff; border: 1.5px solid var(--border-color); border-radius: var(--radius-lg); padding: 18px 22px; box-shadow: var(--shadow-sm); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 800; font-size: 0.95rem; color: var(--text-primary);">
                        Descargas Masivas por Rango de Fechas (ZIP)
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);">
                        Descarga los comprobantes comprimidos, cada uno nombrado como: <code style="color: var(--asesco-orange); font-weight: 700;">cedula-fechadeenvio.ext</code>
                    </div>
                </div>
            </div>

            <!-- Quick Download Buttons & Custom Form -->
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <!-- Descargar Hoy -->
                <a href="{{ route('soportes.descargarLote', ['rango' => 'hoy']) }}" class="btn-secondary" style="padding: 8px 14px; font-size: 0.85rem;" title="Descargar todos los de hoy">
                    📅 Todos los de Hoy
                </a>

                <!-- Descargar Ayer -->
                <a href="{{ route('soportes.descargarLote', ['rango' => 'ayer']) }}" class="btn-secondary" style="padding: 8px 14px; font-size: 0.85rem;" title="Descargar todos los de ayer">
                    📅 Los de Ayer
                </a>

                <!-- Custom Range Dropdown / Form Toggle -->
                <button type="button" class="btn-primary" onclick="toggleCustomRangeModal()" style="padding: 8px 16px; font-size: 0.85rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.253 18.75m3-18.75h18m-18 0h18v18H3V6.75Z" />
                    </svg>
                    <span>Rango Personalizado...</span>
                </button>
            </div>
        </div>

        <!-- Custom Date Range Form (Hidden by default, toggled via JS) -->
        <div id="customRangeBox" style="display: none; background: #ffffff; border: 1.5px solid var(--asesco-orange); border-radius: var(--radius-lg); padding: 18px 22px; box-shadow: var(--shadow-md);">
            <form action="{{ route('soportes.descargarLote') }}" method="GET" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">
                        Seleccionar fechas para el ZIP:
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="fecha_inicio" style="font-size: 0.825rem; font-weight: 600; color: var(--text-muted);">Desde:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="filter-select" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="fecha_fin" style="font-size: 0.825rem; font-weight: 600; color: var(--text-muted);">Hasta:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="filter-select" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-secondary" onclick="toggleCustomRangeModal()" style="padding: 8px 14px;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary" style="padding: 8px 18px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Descargar ZIP del Rango
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Main Table Card -->
    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Listado de Soportes Recibidos</h2>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-top: 2px;">
                    Comprobantes enviados por clientes. Puedes descargarlos individualmente o por fechas.
                </p>
            </div>

            <!-- Search and Filter Form -->
            <form action="{{ route('soportes.index') }}" method="GET" class="search-filter-form">
                <div class="search-input-group">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control" 
                        placeholder="Buscar por cédula o celular..." 
                        value="{{ request('search') }}"
                    >
                </div>

                <select name="tipo" class="filter-select" onchange="this.form.submit()">
                    <option value="">Todos los Formatos</option>
                    <option value="imagen" {{ request('tipo') === 'imagen' ? 'selected' : '' }}>Imágenes (JPG / PNG / WEBP)</option>
                    <option value="pdf" {{ request('tipo') === 'pdf' ? 'selected' : '' }}>Documentos PDF</option>
                </select>

                <select name="fecha_filtro" class="filter-select" onchange="this.form.submit()">
                    <option value="">Cualquier Fecha</option>
                    <option value="hoy" {{ request('fecha_filtro') === 'hoy' ? 'selected' : '' }}>Solo los de Hoy</option>
                    <option value="ayer" {{ request('fecha_filtro') === 'ayer' ? 'selected' : '' }}>Solo los de Ayer</option>
                </select>

                @if(request('search') || request('tipo') || request('fecha_filtro'))
                    <a href="{{ route('soportes.index') }}" class="btn-secondary" style="padding: 8px 12px;" title="Limpiar filtros">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 70px;">REF #</th>
                        <th>CÉDULA</th>
                        <th>CELULAR / WHATSAPP</th>
                        <th>COMPROBANTE ADJUNTO</th>
                        <th>FORMATO / PESO</th>
                        <th>FECHA DE ENVÍO</th>
                        <th style="text-align: right; width: 140px;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($soportes as $soporte)
                        <tr>
                            <td>
                                <span style="font-weight: 800; color: var(--asesco-orange);">#{{ $soporte->id }}</span>
                            </td>
                            <td>
                                <strong style="color: var(--text-primary); font-size: 0.95rem;">{{ $soporte->cedula }}</strong>
                            </td>
                            <td>
                                @if($soporte->celular)
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <span>{{ $soporte->celular }}</span>
                                        <a href="https://wa.me/57{{ preg_replace('/[^0-9]/', '', $soporte->celular) }}" target="_blank" style="color: #22c55e;" title="Escribir por WhatsApp">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span style="color: var(--text-light); font-style: italic;">No proporcionado</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" 
                                    class="btn-action-preview"
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
                                    style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: var(--text-primary); font-weight: 600; background: none; border: none; cursor: pointer; padding: 0; text-align: left;"
                                    title="Hacer clic para visualizar comprobante"
                                >
                                    @if($soporte->isPdf())
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; flex-shrink: 0;">
                                            PDF
                                        </div>
                                    @else
                                        <img src="{{ $soporte->file_url }}" alt="Comprobante" style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border-color); flex-shrink: 0;">
                                    @endif
                                    <span style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $soporte->archivo_nombre_original ?? 'Ver Archivo' }}
                                    </span>
                                </button>
                            </td>
                            <td>
                                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">
                                    {{ $soporte->archivo_extension }} &bull; {{ $soporte->archivo_tamano }}
                                </span>
                            </td>
                            <td>
                                {{ $soporte->created_at ? $soporte->created_at->format('d/m/Y h:i A') : '-' }}
                            </td>
                            <td>
                                <div class="action-buttons-group" style="justify-content: flex-end;">
                                    <!-- Botón de Descarga Directa: cedula-fechadeenvio.ext -->
                                    <a href="{{ route('soportes.descargar', $soporte) }}" class="btn-action" style="color: var(--asesco-orange); border-color: rgba(240, 84, 35, 0.4);" title="Descargar como: {{ $soporte->cedula }}-{{ $soporte->created_at->format('d-m-Y_h-ia') }}.{{ $soporte->archivo_extension }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>

                                    <!-- Botón Visualizar en Modal -->
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
                                        title="Abrir en modal"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    <!-- Eliminar con SweetAlert2 (Solo Admin) -->
                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('soportes.destroy', $soporte) }}" method="POST" onsubmit="return confirmDelete(event, '¿Eliminar soporte de pago?', 'Se eliminará permanentemente el comprobante de la cédula {{ $soporte->cedula }}.')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Eliminar registro">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                No se encontraron soportes de pago con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($soportes->hasPages())
            <div style="padding: 18px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                {{ $soportes->links() }}
            </div>
        @endif
    </div>

    <!-- ==========================================================================
         MODAL DE VISUALIZACIÓN DE COMPROBANTE
         ========================================================================== -->
    <div class="modal-overlay" id="modalSoportePreview" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog modal-dialog-lg">
            <!-- Modal Header -->
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

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Info Bar -->
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

                <!-- Media Viewer (Image or PDF) -->
                <div class="preview-media-wrapper">
                    <!-- Image Tag -->
                    <img id="modalPreviewImg" src="" alt="Comprobante" class="preview-media-img" style="display: none;">
                    
                    <!-- PDF Iframe -->
                    <iframe id="modalPreviewIframe" src="" class="preview-media-iframe" style="display: none;" title="Visor de PDF"></iframe>
                </div>
            </div>

            <!-- Modal Footer with direct download button -->
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
    function copyPublicLink() {
        const url = document.getElementById('publicUrlText').innerText.trim();
        navigator.clipboard.writeText(url).then(function() {
            const btnText = document.getElementById('btnCopyText');
            btnText.innerText = '¡Enlace Copiado!';
            setTimeout(() => {
                btnText.innerText = 'Copiar Enlace';
            }, 2500);
        }).catch(function(err) {
            alert('Enlace: ' + url);
        });
    }

    function toggleCustomRangeModal() {
        const box = document.getElementById('customRangeBox');
        box.style.display = box.style.display === 'none' ? 'block' : 'none';
        if (box.style.display === 'block') {
            box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Modal de Previsualización de Comprobante
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

        // Stop video or pdf iframe background activity
        setTimeout(() => {
            document.getElementById('modalPreviewImg').src = '';
            document.getElementById('modalPreviewIframe').src = '';
        }, 200);
    }
</script>
@endpush
