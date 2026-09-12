<div class="table-responsive">
    <table class="table-custom">
        <thead>
            <tr>
                <th style="width: 70px;">REF #</th>
                <th>CÉDULA</th>
                <th>ESTADO / VINCULACIÓN</th>
                <th>CELULAR / WHATSAPP</th>
                <th>COMPROBANTE ADJUNTO</th>
                <th>FORMATO / PESO</th>
                <th>FECHA DE ENVÍO</th>
                <th style="text-align: right; width: 170px;">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @forelse($soportes as $soporte)
                <tr class="{{ $soporte->isDuplicado() ? 'row-duplicate' : '' }}">
                    <td>
                        <span style="font-weight: 600; font-size: 0.8125rem; color: var(--asesco-orange);">#{{ $soporte->id }}</span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                            <span style="color: #111827; font-weight: 500; font-size: 0.8125rem;">{{ $soporte->cedula }}</span>
                            @if(isset($cedulasRepetidas[$soporte->cedula]))
                                <span class="badge-multi-envio" title="Esta cédula ha enviado múltiples soportes">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" width="11" height="11">
                                        <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.794A1.5 1.5 0 0 1 1.495 11.25l5.206-9ZM8 4c-.28 0-.5.22-.5.5v3.5a.5.5 0 0 0 1 0V4.5c0-.28-.22-.5-.5-.5Zm0 7a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                    </svg>
                                    Múltiple
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($soporte->isDuplicado())
                            <button type="button" 
                                class="badge-duplicado" 
                                onclick="openCompareModal({{ $soporte->id }})" 
                                title="Hacer clic para comparar lado a lado con el soporte original #{{ $soporte->duplicado_de_id }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" width="12" height="12">
                                    <path fill-rule="evenodd" d="M13.78 4.22a.75.75 0 0 1 0 1.06l-2.5 2.5a.75.75 0 0 1-1.06-1.06L11.44 5.5H3.75a.75.75 0 0 1 0-1.5h7.69L10.22 2.78a.75.75 0 0 1 1.06-1.06l2.5 2.5Zm-11.56 7.56a.75.75 0 0 1 0-1.06l2.5-2.5a.75.75 0 1 1 1.06 1.06L4.56 10.5h7.69a.75.75 0 0 1 0 1.5H4.56l1.22 1.22a.75.75 0 0 1-1.06 1.06l-2.5-2.5Z" clip-rule="evenodd" />
                                </svg>
                                <span>Duplicado de #{{ $soporte->duplicado_de_id ?? '?' }}</span>
                            </button>
                        @elseif($soporte->duplicados_count > 0)
                            <button type="button" 
                                class="badge-has-duplicates" 
                                onclick="openCompareModal({{ $soporte->id }})" 
                                title="Ver comprobante(s) duplicado(s) vinculados a este original"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" width="12" height="12">
                                    <path fill-rule="evenodd" d="M8.5 2a1.5 1.5 0 0 0-1.5 1.5v3.5h-3.5a1.5 1.5 0 0 0 0 3H7v3.5a1.5 1.5 0 0 0 3 0V10h3.5a1.5 1.5 0 0 0 0-3H10V3.5A1.5 1.5 0 0 0 8.5 2Z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $soporte->duplicados_count }} duplicado{{ $soporte->duplicados_count > 1 ? 's' : '' }}</span>
                            </button>
                        @elseif(isset($cedulasRepetidas[$soporte->cedula]))
                            <button type="button" 
                                class="badge-duplicado" 
                                style="background: #fffbeb; color: #b45309; border-color: #fde68a;"
                                onclick="openMarcarModal({{ $soporte->id }})" 
                                title="Esta cédula tiene varios envíos. Clic para vincular como duplicado si aplica"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" width="12" height="12">
                                    <path d="M4 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H4Zm1.5 5.5a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm3 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0ZM5.5 10a.75.75 0 0 1 .75-.75h3.5a.75.75 0 0 1 0 1.5h-3.5A.75.75 0 0 1 5.5 10Z"/>
                                </svg>
                                <span>Revisar duplicados</span>
                            </button>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; color: #16a34a; font-weight: 600; background: #f0fdf4; padding: 2px 8px; border-radius: 10px; border: 1px solid #bbf7d0;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: #22c55e;"></span>
                                Válido
                            </span>
                        @endif
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
                            <span style="max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
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
                        <span style="font-size: 0.85rem;">
                            {{ $soporte->created_at ? $soporte->created_at->format('d/m/Y h:i A') : '-' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons-group" style="justify-content: flex-end;">
                            <!-- Botón de Descarga Directa: cedula-fechadeenvio.ext -->
                            <a href="{{ route('soportes.descargar', $soporte) }}" class="btn-action" style="color: var(--asesco-orange); border-color: rgba(240, 84, 35, 0.4);" title="Descargar como: {{ $soporte->cedula }}-{{ $soporte->created_at ? $soporte->created_at->format('d-m-Y_h-ia') : 'soporte' }}.{{ $soporte->archivo_extension }}">
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
                                title="Abrir comprobante en modal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>

                            <!-- Acciones de Duplicado -->
                            @if($soporte->isDuplicado() || $soporte->duplicados_count > 0)
                                <!-- Comparar lado a lado -->
                                <button type="button" 
                                    class="btn-action btn-action-compare" 
                                    onclick="openCompareModal({{ $soporte->id }})" 
                                    title="Comparar original y duplicado lado a lado"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                </button>
                            @else
                                <!-- Marcar como Duplicado -->
                                <button type="button" 
                                    class="btn-action btn-action-duplicate" 
                                    onclick="openMarcarModal({{ $soporte->id }})" 
                                    title="Marcar o vincular este comprobante como duplicado de otro"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                    </svg>
                                </button>
                            @endif

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
                    <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" style="text-align: center; padding: 46px 20px; color: var(--text-muted);">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="38" height="38" style="color: var(--text-light);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <span style="font-weight: 600; font-size: 0.95rem;">No se encontraron soportes de pago</span>
                            <span style="font-size: 0.825rem; color: var(--text-light);">Intenta cambiar los términos de búsqueda o los filtros aplicados.</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($soportes->hasPages())
    <div style="padding: 18px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;" id="tablePaginationLinks">
        {{ $soportes->links() }}
    </div>
@endif
