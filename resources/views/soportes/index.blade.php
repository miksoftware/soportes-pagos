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
                    <div style="font-weight: 600; color: #1f2937; font-size: 0.875rem;">
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
                    <div style="font-weight: 600; font-size: 0.875rem; color: #1f2937;">
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

            <!-- Dynamic Search and Filter Form -->
            <form action="{{ route('soportes.index') }}" method="GET" class="search-filter-form" id="searchFilterForm" onsubmit="event.preventDefault(); triggerSearch();">
                <div class="search-input-group" id="liveSearchGroup">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        id="liveSearchInput"
                        class="form-control" 
                        placeholder="Buscar por cédula o celular en tiempo real..." 
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    <button type="button" class="search-clear-btn" id="liveSearchClear" onclick="clearLiveSearch()" title="Limpiar búsqueda">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                    </button>
                    <div class="search-spinner" id="searchSpinner"></div>
                </div>

                <!-- Filtro por Estado / Duplicados -->
                <select name="estado" id="filterEstado" class="filter-select" onchange="triggerSearch()">
                    <option value="">Todos los Estados</option>
                    <option value="validos" {{ request('estado') === 'validos' ? 'selected' : '' }}>Solo Válidos (Sin Duplicados)</option>
                    <option value="duplicados" {{ request('estado') === 'duplicados' ? 'selected' : '' }}>Solo Duplicados</option>
                </select>

                <!-- Filtro por Tipo de Archivo -->
                <select name="tipo" id="filterTipo" class="filter-select" onchange="triggerSearch()">
                    <option value="">Todos los Formatos</option>
                    <option value="imagen" {{ request('tipo') === 'imagen' ? 'selected' : '' }}>Imágenes (JPG / PNG / WEBP)</option>
                    <option value="pdf" {{ request('tipo') === 'pdf' ? 'selected' : '' }}>Documentos PDF</option>
                </select>

                <!-- Filtro por Fecha -->
                <select name="fecha_filtro" id="filterFecha" class="filter-select" onchange="triggerSearch()">
                    <option value="">Cualquier Fecha</option>
                    <option value="hoy" {{ request('fecha_filtro') === 'hoy' ? 'selected' : '' }}>Solo los de Hoy</option>
                    <option value="ayer" {{ request('fecha_filtro') === 'ayer' ? 'selected' : '' }}>Solo los de Ayer</option>
                </select>

                <button type="button" id="btnClearAllFilters" class="btn-secondary" style="padding: 8px 12px; display: {{ (request('search') || request('tipo') || request('estado') || request('fecha_filtro')) ? 'inline-flex' : 'none' }};" onclick="resetAllFilters()" title="Restablecer todos los filtros">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Dynamic Table Container (Re-rendered via AJAX) -->
        <div id="soportesTableContainer">
            @include('soportes._table')
        </div>
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

    <!-- ==========================================================================
         MODAL PARA MARCAR COMO DUPLICADO
         ========================================================================== -->
    <div class="modal-overlay" id="modalMarcarDuplicado" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="color: #ea580c;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                    </svg>
                    <span>Vincular como Duplicado</span>
                    <span id="modalMarcarTargetRef" style="background: #ffedd5; color: #ea580c; padding: 3px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">#</span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeMarcarModal()" title="Cerrar modal (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Info banner del comprobante a marcar -->
                <div style="background: #f8fafc; border: 1.5px solid var(--border-color); border-radius: var(--radius-md); padding: 14px 16px; margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Comprobante seleccionado para marcar:</div>
                        <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-primary); margin-top: 2px;">
                            Cédula: <span id="marcarTargetCedula" style="color: var(--asesco-orange);">-</span>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);" id="marcarTargetFecha">-</div>
                    </div>
                    <span style="font-size: 0.75rem; background: #fff7ed; color: #c2410c; padding: 4px 10px; border-radius: 12px; font-weight: 700; border: 1px solid #fdba74;">
                        Será Duplicado
                    </span>
                </div>

                <!-- Selección de original -->
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">
                            1. Selecciona el Comprobante Original:
                        </label>
                        <span style="font-size: 0.75rem; color: var(--text-muted);" id="candidateCountLabel">Cargando...</span>
                    </div>

                    <!-- Lista de candidatos (otros soportes de la misma cédula) -->
                    <div class="candidate-cards-list" id="candidateCardsContainer">
                        <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 0.85rem;">
                            Buscando comprobantes con la misma cédula...
                        </div>
                    </div>
                </div>

                <!-- O ingresar ID manual si la cédula es diferente -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 14px; margin-bottom: 14px;">
                    <label for="inputManualOriginalId" style="display: block; font-size: 0.825rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                        O ingresar manualmente el Ref # del Original (si no aparece arriba):
                    </label>
                    <input 
                        type="number" 
                        id="inputManualOriginalId" 
                        class="form-control" 
                        placeholder="Ejemplo: 12" 
                        oninput="onManualIdInput(this.value)"
                        style="height: 38px; font-size: 0.875rem;"
                    >
                </div>

                <!-- Motivo u observación opcional -->
                <div>
                    <label for="inputMotivoDuplicado" style="display: block; font-size: 0.825rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 4px;">
                        Motivo u Observación (Opcional):
                    </label>
                    <input 
                        type="text" 
                        id="inputMotivoDuplicado" 
                        class="form-control" 
                        placeholder="Ej: Mismo comprobante de pago reenviado por el cliente"
                        style="height: 38px; font-size: 0.875rem;"
                    >
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeMarcarModal()">
                    Cancelar
                </button>
                <button type="button" class="btn-primary" id="btnConfirmMarcarDuplicado" onclick="confirmMarcarDuplicado()" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    <span>Confirmar y Vincular Duplicado</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
         MODAL DE COMPARACIÓN LADO A LADO (DIFF VIEWER)
         ========================================================================== -->
    <div class="modal-overlay" id="modalCompararDuplicados" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog modal-dialog-xl">
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="color: #7c3aed;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    <span>Comparativa: Comprobante Original vs. Duplicado</span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeCompareModal()" title="Cerrar modal (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" id="compareModalBody">
                <div style="text-align: center; padding: 40px; color: var(--text-muted);" id="compareLoadingState">
                    <div class="search-spinner" style="display: inline-block; position: static; width: 28px; height: 28px; margin-bottom: 10px;"></div>
                    <div>Cargando datos de comparación...</div>
                </div>

                <div class="comparison-grid" id="compareGridContainer" style="display: none;">
                    <!-- Columna Izquierda: Original -->
                    <div class="comparison-column col-original">
                        <div class="comparison-col-header">
                            <div class="comparison-col-title" style="color: #1e40af;">
                                <span>⭐ COMPROBANTE ORIGINAL</span>
                                <span id="compOrigRef" style="background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">#</span>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #16a34a; background: #dcfce7; padding: 2px 8px; border-radius: 10px;">Pago Principal</span>
                        </div>

                        <div class="comparison-media-box">
                            <img id="compOrigImg" src="" alt="Original" class="comparison-media-img" style="display: none;">
                            <iframe id="compOrigIframe" src="" class="comparison-media-iframe" style="display: none;" title="PDF Original"></iframe>
                        </div>

                        <div class="comparison-meta-box">
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Cédula:</span>
                                <span class="comparison-meta-val" id="compOrigCedula">-</span>
                            </div>
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Celular:</span>
                                <span class="comparison-meta-val" id="compOrigCelular">-</span>
                            </div>
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Fecha de Envío:</span>
                                <span class="comparison-meta-val" id="compOrigFecha">-</span>
                            </div>
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Archivo:</span>
                                <span class="comparison-meta-val" id="compOrigArchivo" style="max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">-</span>
                            </div>
                        </div>

                        <a id="compOrigDownloadBtn" href="#" class="btn-secondary" style="width: 100%; justify-content: center; gap: 6px; padding: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Descargar Original
                        </a>
                    </div>

                    <!-- Columna Derecha: Duplicado -->
                    <div class="comparison-column col-duplicate">
                        <div class="comparison-col-header">
                            <div class="comparison-col-title" style="color: #c2410c;">
                                <span>🔁 COMPROBANTE DUPLICADO</span>
                                <span id="compDupRef" style="background: #ffedd5; color: #c2410c; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">#</span>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #ea580c; background: #ffedd5; padding: 2px 8px; border-radius: 10px;">Duplicado</span>
                        </div>

                        <div class="comparison-media-box">
                            <img id="compDupImg" src="" alt="Duplicado" class="comparison-media-img" style="display: none;">
                            <iframe id="compDupIframe" src="" class="comparison-media-iframe" style="display: none;" title="PDF Duplicado"></iframe>
                        </div>

                        <div class="comparison-meta-box">
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Cédula:</span>
                                <span class="comparison-meta-val" id="compDupCedula">-</span>
                            </div>
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Celular:</span>
                                <span class="comparison-meta-val" id="compDupCelular">-</span>
                            </div>
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Fecha de Envío:</span>
                                <span class="comparison-meta-val" id="compDupFecha">-</span>
                            </div>
                            <div class="comparison-meta-row">
                                <span class="comparison-meta-label">Observación:</span>
                                <span class="comparison-meta-val" id="compDupMotivo" style="color: #ea580c; font-style: italic;">-</span>
                            </div>
                        </div>

                        <a id="compDupDownloadBtn" href="#" class="btn-secondary" style="width: 100%; justify-content: center; gap: 6px; padding: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Descargar Duplicado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="justify-content: space-between;">
                <button type="button" class="btn-secondary" id="btnDesmarcarCompare" onclick="desmarcarFromCompareModal()" style="color: #dc2626; border-color: rgba(220, 38, 38, 0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    <span>Desmarcar como Duplicado</span>
                </button>
                <button type="button" class="btn-secondary" onclick="closeCompareModal()">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    // ==========================================================================
    // DYNAMIC REAL-TIME LIVE SEARCH & FILTERS (DEBOUNCED FETCH)
    // ==========================================================================
    let searchDebounceTimer = null;
    let currentCompareDuplicateId = null;
    let currentMarcarTargetId = null;
    let selectedOriginalCandidateId = null;

    const liveSearchInput = document.getElementById('liveSearchInput');
    const liveSearchClear = document.getElementById('liveSearchClear');
    const liveSearchGroup = document.getElementById('liveSearchGroup');
    const btnClearAllFilters = document.getElementById('btnClearAllFilters');

    if (liveSearchInput) {
        liveSearchInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                liveSearchClear.style.display = 'inline-flex';
            } else {
                liveSearchClear.style.display = 'none';
            }

            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                triggerSearch();
            }, 280);
        });

        // Trigger on enter key without reload
        liveSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchDebounceTimer);
                triggerSearch();
            }
        });
    }

    function clearLiveSearch() {
        if (liveSearchInput) {
            liveSearchInput.value = '';
            liveSearchClear.style.display = 'none';
            liveSearchInput.focus();
            triggerSearch();
        }
    }

    function resetAllFilters() {
        if (liveSearchInput) liveSearchInput.value = '';
        if (liveSearchClear) liveSearchClear.style.display = 'none';
        document.getElementById('filterTipo').value = '';
        document.getElementById('filterEstado').value = '';
        document.getElementById('filterFecha').value = '';
        triggerSearch();
    }

    function triggerSearch(customUrl = null) {
        const search = liveSearchInput ? liveSearchInput.value.trim() : '';
        const tipo = document.getElementById('filterTipo').value;
        const estado = document.getElementById('filterEstado').value;
        const fecha_filtro = document.getElementById('filterFecha').value;

        // Update clear button visibility
        if (btnClearAllFilters) {
            if (search || tipo || estado || fecha_filtro) {
                btnClearAllFilters.style.display = 'inline-flex';
            } else {
                btnClearAllFilters.style.display = 'none';
            }
        }

        let targetUrl = customUrl;
        if (!targetUrl) {
            const params = new URLSearchParams();
            if (search) params.set('search', search);
            if (tipo) params.set('tipo', tipo);
            if (estado) params.set('estado', estado);
            if (fecha_filtro) params.set('fecha_filtro', fecha_filtro);
            targetUrl = `{{ route('soportes.index') }}?${params.toString()}`;
        }

        if (liveSearchGroup) liveSearchGroup.classList.add('is-loading');

        fetch(targetUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al consultar datos');
            return response.text();
        })
        .then(html => {
            const container = document.getElementById('soportesTableContainer');
            if (container) {
                container.innerHTML = html;
                bindPaginationEvents();
            }
            window.history.replaceState({}, '', targetUrl);
        })
        .catch(err => {
            console.error('Error filtrando:', err);
        })
        .finally(() => {
            if (liveSearchGroup) liveSearchGroup.classList.remove('is-loading');
        });
    }

    // Intercept AJAX pagination clicks
    function bindPaginationEvents() {
        const container = document.getElementById('soportesTableContainer');
        if (!container) return;

        const links = container.querySelectorAll('.pagination a, #tablePaginationLinks a');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url && url !== '#') {
                    triggerSearch(url);
                }
            });
        });
    }

    // Initialize pagination listener on load
    document.addEventListener('DOMContentLoaded', () => {
        bindPaginationEvents();
        if (liveSearchInput && liveSearchInput.value.trim() !== '') {
            liveSearchClear.style.display = 'inline-flex';
        }
    });

    // ==========================================================================
    // MODAL DE MARCAR COMO DUPLICADO
    // ==========================================================================
    function openMarcarModal(soporteId) {
        currentMarcarTargetId = soporteId;
        selectedOriginalCandidateId = null;

        const modal = document.getElementById('modalMarcarDuplicado');
        document.getElementById('modalMarcarTargetRef').innerText = '#' + soporteId;
        document.getElementById('inputManualOriginalId').value = '';
        document.getElementById('inputMotivoDuplicado').value = '';
        document.getElementById('candidateCountLabel').innerText = 'Cargando...';

        const container = document.getElementById('candidateCardsContainer');
        container.innerHTML = `
            <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 0.85rem;">
                <div class="search-spinner" style="display: inline-block; position: static; width: 20px; height: 20px; margin-bottom: 6px;"></div>
                <div>Buscando comprobantes para sugerir el original...</div>
            </div>
        `;

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        // Fetch candidate matches
        fetch(`{{ url('soportes') }}/${soporteId}/posibles-duplicados`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.actual) {
                document.getElementById('marcarTargetCedula').innerText = data.actual.cedula;
                document.getElementById('marcarTargetFecha').innerText = data.actual.fecha + ' (' + data.actual.extension + ')';
            }

            const candidates = data.posibles || [];
            document.getElementById('candidateCountLabel').innerText = `${candidates.length} encontrados con esta cédula`;

            if (candidates.length === 0) {
                container.innerHTML = `
                    <div style="padding: 16px; background: #fffbeb; border: 1px dashed #fde68a; border-radius: var(--radius-md); text-align: center; color: #b45309; font-size: 0.85rem;">
                        No se encontraron otros comprobantes con la cédula <strong>${data.actual?.cedula || ''}</strong>.<br>
                        Puedes ingresar el <strong>Ref #</strong> del original en la casilla inferior si fue digitado con otra cédula.
                    </div>
                `;
            } else {
                let html = '';
                candidates.forEach((cand, idx) => {
                    const isSelected = idx === 0 ? 'selected' : '';
                    if (idx === 0) selectedOriginalCandidateId = cand.id;

                    const thumb = cand.is_pdf
                        ? `<div class="candidate-pdf-badge">PDF</div>`
                        : `<img src="${cand.file_url}" alt="Preview" class="candidate-thumb">`;

                    html += `
                        <div class="candidate-card ${isSelected}" onclick="selectCandidateCard(this, ${cand.id})" id="candCard_${cand.id}">
                            ${thumb}
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <strong style="color: var(--text-primary); font-size: 0.9rem;">Ref #${cand.id}</strong>
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">${cand.extension} • ${cand.tamano}</span>
                                    ${cand.is_duplicado ? '<span style="font-size: 0.7rem; color: #ea580c; background: #fff7ed; padding: 1px 6px; border-radius: 6px;">Ya es duplicado</span>' : ''}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 2px;">
                                    Enviado: <strong>${cand.fecha}</strong>
                                </div>
                            </div>
                            <div style="font-size: 0.8rem; font-weight: 700; color: var(--asesco-orange);">
                                ${isSelected ? '✓ Seleccionado' : 'Seleccionar'}
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = `<div style="color: #dc2626; padding: 16px; text-align: center;">Error al cargar sugerencias.</div>`;
        });
    }

    function selectCandidateCard(el, originalId) {
        document.querySelectorAll('.candidate-card').forEach(c => {
            c.classList.remove('selected');
            const statusLabel = c.querySelector('div:last-child');
            if (statusLabel) statusLabel.innerText = 'Seleccionar';
        });

        el.classList.add('selected');
        const statusLabel = el.querySelector('div:last-child');
        if (statusLabel) statusLabel.innerText = '✓ Seleccionado';

        selectedOriginalCandidateId = originalId;
        document.getElementById('inputManualOriginalId').value = '';
    }

    function onManualIdInput(val) {
        if (val.trim() !== '') {
            selectedOriginalCandidateId = parseInt(val.trim());
            document.querySelectorAll('.candidate-card').forEach(c => {
                c.classList.remove('selected');
                const statusLabel = c.querySelector('div:last-child');
                if (statusLabel) statusLabel.innerText = 'Seleccionar';
            });
        }
    }

    function confirmMarcarDuplicado() {
        const originalId = selectedOriginalCandidateId || parseInt(document.getElementById('inputManualOriginalId').value);
        const motivo = document.getElementById('inputMotivoDuplicado').value.trim();

        if (!originalId || isNaN(originalId)) {
            Swal.fire({
                icon: 'warning',
                title: 'Selecciona el comprobante original',
                text: 'Por favor selecciona uno de los comprobantes sugeridos o ingresa el número de referencia manualmente.',
                confirmButtonText: 'Entendido',
                customClass: { popup: 'asesco-swal-popup', confirmButton: 'asesco-swal-confirm' },
                buttonsStyling: false
            });
            return;
        }

        if (originalId === currentMarcarTargetId) {
            Swal.fire({
                icon: 'error',
                title: 'Referencia inválida',
                text: 'Un comprobante no puede ser duplicado de sí mismo.',
                confirmButtonText: 'Entendido',
                customClass: { popup: 'asesco-swal-popup', confirmButton: 'asesco-swal-confirm' },
                buttonsStyling: false
            });
            return;
        }

        const btn = document.getElementById('btnConfirmMarcarDuplicado');
        btn.disabled = true;
        btn.innerText = 'Guardando...';

        fetch(`{{ url('soportes') }}/${currentMarcarTargetId}/marcar-duplicado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                original_id: originalId,
                motivo: motivo
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<span>Confirmar y Vincular Duplicado</span>';

            if (data.success) {
                closeMarcarModal();
                Swal.fire({
                    icon: 'success',
                    title: '¡Comprobante Vinculado!',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false,
                    customClass: { popup: 'asesco-swal-popup' }
                });
                triggerSearch();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo registrar el duplicado.',
                    confirmButtonText: 'Cerrar',
                    customClass: { popup: 'asesco-swal-popup', confirmButton: 'asesco-swal-confirm' },
                    buttonsStyling: false
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<span>Confirmar y Vincular Duplicado</span>';
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'Ocurrió un inconveniente al procesar la solicitud.',
                confirmButtonText: 'Cerrar',
                customClass: { popup: 'asesco-swal-popup', confirmButton: 'asesco-swal-confirm' },
                buttonsStyling: false
            });
        });
    }

    function closeMarcarModal() {
        const modal = document.getElementById('modalMarcarDuplicado');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        currentMarcarTargetId = null;
        selectedOriginalCandidateId = null;
    }

    // ==========================================================================
    // MODAL DE COMPARACIÓN LADO A LADO
    // ==========================================================================
    function openCompareModal(soporteId) {
        const modal = document.getElementById('modalCompararDuplicados');
        const loading = document.getElementById('compareLoadingState');
        const grid = document.getElementById('compareGridContainer');

        loading.style.display = 'block';
        grid.style.display = 'none';

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        fetch(`{{ url('soportes') }}/${soporteId}/comparar-datos`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Error al cargar datos');
            return res.json();
        })
        .then(data => {
            loading.style.display = 'none';
            grid.style.display = 'grid';

            const orig = data.original;
            const dup = data.duplicado;
            currentCompareDuplicateId = dup.id;

            // Columna Original
            document.getElementById('compOrigRef').innerText = '#' + orig.id;
            document.getElementById('compOrigCedula').innerText = orig.cedula;
            document.getElementById('compOrigCelular').innerText = orig.celular;
            document.getElementById('compOrigFecha').innerText = orig.fecha;
            document.getElementById('compOrigArchivo').innerText = orig.archivo_nombre;
            document.getElementById('compOrigDownloadBtn').href = orig.download_url;

            const origImg = document.getElementById('compOrigImg');
            const origIframe = document.getElementById('compOrigIframe');
            if (orig.is_pdf) {
                origImg.style.display = 'none';
                origIframe.style.display = 'block';
                origIframe.src = orig.file_url;
            } else {
                origIframe.style.display = 'none';
                origImg.style.display = 'block';
                origImg.src = orig.file_url;
            }

            // Columna Duplicado
            document.getElementById('compDupRef').innerText = '#' + dup.id;
            document.getElementById('compDupCedula').innerText = dup.cedula;
            document.getElementById('compDupCelular').innerText = dup.celular;
            document.getElementById('compDupFecha').innerText = dup.fecha;
            document.getElementById('compDupMotivo').innerText = dup.motivo || 'Marcado como duplicado';
            document.getElementById('compDupDownloadBtn').href = dup.download_url;

            const dupImg = document.getElementById('compDupImg');
            const dupIframe = document.getElementById('compDupIframe');
            if (dup.is_pdf) {
                dupImg.style.display = 'none';
                dupIframe.style.display = 'block';
                dupIframe.src = dup.file_url;
            } else {
                dupIframe.style.display = 'none';
                dupImg.style.display = 'block';
                dupImg.src = dup.file_url;
            }
        })
        .catch(err => {
            console.error(err);
            loading.innerHTML = `<div style="color: #dc2626;">Error al cargar la comparación de comprobantes.</div>`;
        });
    }

    function closeCompareModal() {
        const modal = document.getElementById('modalCompararDuplicados');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');

        setTimeout(() => {
            document.getElementById('compOrigImg').src = '';
            document.getElementById('compOrigIframe').src = '';
            document.getElementById('compDupImg').src = '';
            document.getElementById('compDupIframe').src = '';
            currentCompareDuplicateId = null;
        }, 200);
    }

    function desmarcarFromCompareModal() {
        if (!currentCompareDuplicateId) return;
        desmarcarDuplicado(currentCompareDuplicateId);
    }

    function desmarcarDuplicado(soporteId) {
        Swal.fire({
            title: '¿Desmarcar como duplicado?',
            text: `El comprobante #${soporteId} volverá a ser un registro regular e independiente.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, desmarcar',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'asesco-swal-popup',
                confirmButton: 'asesco-swal-confirm',
                cancelButton: 'asesco-swal-cancel'
            },
            buttonsStyling: false
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`{{ url('soportes') }}/${soporteId}/desmarcar-duplicado`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    closeCompareModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Desmarcado',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'asesco-swal-popup' }
                    });
                    triggerSearch();
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo desmarcar el comprobante.',
                        customClass: { popup: 'asesco-swal-popup' }
                    });
                });
            }
        });
    }

    // ==========================================================================
    // UTILITIES
    // ==========================================================================
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

        setTimeout(() => {
            document.getElementById('modalPreviewImg').src = '';
            document.getElementById('modalPreviewIframe').src = '';
        }, 200);
    }
</script>
@endpush
