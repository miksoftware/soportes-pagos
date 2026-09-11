<?php

namespace App\Http\Controllers;

use App\Models\SoportePago;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class SoportePagoController extends Controller
{
    /**
     * Display a listing of received payment supports.
     */
    public function index(Request $request): View|string
    {
        $query = SoportePago::query()->with(['original', 'duplicados'])->withCount('duplicados');

        // Search by cédula or phone number
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('cedula', 'like', "%{$search}%")
                    ->orWhere('celular', 'like', "%{$search}%");
            });
        }

        // Filter by file type
        if ($tipo = $request->input('tipo')) {
            if (in_array($tipo, ['imagen', 'pdf'], true)) {
                $query->where('tipo', $tipo);
            }
        }

        // Filter by status / duplicates
        if ($estado = $request->input('estado')) {
            if ($estado === 'validos') {
                $query->whereNull('duplicado_de_id')->where('estado', '!=', 'duplicado');
            } elseif ($estado === 'duplicados') {
                $query->where(function ($q) {
                    $q->whereNotNull('duplicado_de_id')->orWhere('estado', 'duplicado');
                });
            }
        }

        // Filter by date presets or custom range
        if ($fechaFiltro = $request->input('fecha_filtro')) {
            if ($fechaFiltro === 'hoy') {
                $query->whereDate('created_at', today());
            } elseif ($fechaFiltro === 'ayer') {
                $query->whereDate('created_at', today()->subDay());
            }
        } elseif ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('created_at', [
                $request->fecha_inicio.' 00:00:00',
                $request->fecha_fin.' 23:59:59',
            ]);
        }

        $soportes = $query->latest()->paginate(15)->withQueryString();

        // Calculate duplicate cédula counts to proactively flag multi-submissions
        $cedulasRepetidas = SoportePago::select('cedula')
            ->groupBy('cedula')
            ->havingRaw('count(*) > 1')
            ->pluck('cedula')
            ->flip()
            ->toArray();

        if ($request->ajax()) {
            return view('soportes._table', compact('soportes', 'cedulasRepetidas'))->render();
        }

        return view('soportes.index', compact('soportes', 'cedulasRepetidas'));
    }

    /**
     * Download an individual receipt named: {cedula}-{fechadeenvio}.{ext}
     */
    public function download(SoportePago $soporte): StreamedResponse|RedirectResponse
    {
        if (! $soporte->archivo_path || ! Storage::disk('public')->exists($soporte->archivo_path)) {
            return back()->with('error', 'El archivo no fue encontrado en el servidor.');
        }

        // Format: {cedula}-{fechadeenvio}.{ext}
        $fechaFormatted = $soporte->created_at->format('d-m-Y_h-ia');
        $fileName = "{$soporte->cedula}-{$fechaFormatted}.{$soporte->archivo_extension}";

        return Storage::disk('public')->download($soporte->archivo_path, $fileName);
    }

    /**
     * Mark a payment support as duplicate of an original one.
     */
    public function marcarDuplicado(Request $request, SoportePago $soporte): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'original_id' => ['required', 'integer', 'exists:soportes_pagos,id', 'different:'.$soporte->id],
            'motivo' => ['nullable', 'string', 'max:255'],
        ], [
            'original_id.required' => 'Debes seleccionar el comprobante original.',
            'original_id.exists' => 'El comprobante original seleccionado no existe.',
            'original_id.different' => 'Un comprobante no puede ser duplicado de sí mismo.',
        ]);

        $original = SoportePago::findOrFail($validated['original_id']);

        $soporte->update([
            'duplicado_de_id' => $original->id,
            'estado' => 'duplicado',
            'duplicado_motivo' => $validated['motivo'] ?? 'Comprobante duplicado por el usuario',
            'duplicado_at' => now(),
        ]);

        $message = "El comprobante #{$soporte->id} fue marcado exitosamente como duplicado del #{$original->id}.";

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'soporte_id' => $soporte->id,
                'original_id' => $original->id,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Unmark a duplicate payment support back to pending/valid.
     */
    public function desmarcarDuplicado(Request $request, SoportePago $soporte): JsonResponse|RedirectResponse
    {
        $soporte->update([
            'duplicado_de_id' => null,
            'estado' => 'pendiente',
            'duplicado_motivo' => null,
            'duplicado_at' => null,
        ]);

        $message = "El comprobante #{$soporte->id} ha sido desmarcado como duplicado y restaurado a estado regular.";

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Return possible original matches for marking a duplicate.
     */
    public function posiblesDuplicados(Request $request, SoportePago $soporte): JsonResponse
    {
        $query = SoportePago::where('id', '!=', $soporte->id);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('cedula', 'like', "%{$search}%");
            });
        } else {
            $query->where('cedula', $soporte->cedula);
        }

        $posibles = $query->latest()->take(10)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'cedula' => $item->cedula,
                'celular' => $item->celular ?? 'No registrado',
                'fecha' => $item->created_at ? $item->created_at->format('d/m/Y h:i A') : '-',
                'archivo_nombre' => $item->archivo_nombre_original ?? 'Comprobante',
                'extension' => strtoupper($item->archivo_extension ?? 'IMG'),
                'tamano' => $item->archivo_tamano ?? '',
                'file_url' => $item->file_url,
                'is_pdf' => $item->isPdf(),
                'is_duplicado' => $item->isDuplicado(),
            ];
        });

        return response()->json([
            'posibles' => $posibles,
            'actual' => [
                'id' => $soporte->id,
                'cedula' => $soporte->cedula,
                'celular' => $soporte->celular ?? 'No registrado',
                'fecha' => $soporte->created_at ? $soporte->created_at->format('d/m/Y h:i A') : '-',
                'archivo_nombre' => $soporte->archivo_nombre_original ?? 'Comprobante',
                'extension' => strtoupper($soporte->archivo_extension ?? 'IMG'),
                'tamano' => $soporte->archivo_tamano ?? '',
                'file_url' => $soporte->file_url,
                'is_pdf' => $soporte->isPdf(),
            ],
        ]);
    }

    /**
     * Return comparison data between duplicate and original for side-by-side view.
     */
    public function compararDatos(SoportePago $soporte): JsonResponse
    {
        $soporte->load(['original', 'duplicados']);

        // Determine which is original and which is duplicate
        $original = null;
        $duplicado = null;

        if ($soporte->isDuplicado() && $soporte->original) {
            $original = $soporte->original;
            $duplicado = $soporte;
        } elseif ($soporte->duplicados->isNotEmpty()) {
            $original = $soporte;
            $duplicado = $soporte->duplicados->first();
        } else {
            return response()->json(['error' => 'No se encontró relación de duplicado para este comprobante.'], 404);
        }

        $formatSoporte = function (SoportePago $item) {
            return [
                'id' => $item->id,
                'cedula' => $item->cedula,
                'celular' => $item->celular ?? 'No registrado',
                'walink' => $item->celular ? 'https://wa.me/57'.preg_replace('/[^0-9]/', '', $item->celular) : '',
                'fecha' => $item->created_at ? $item->created_at->format('d/m/Y h:i A') : '-',
                'archivo_nombre' => $item->archivo_nombre_original ?? 'Comprobante',
                'extension' => strtoupper($item->archivo_extension ?? 'IMG'),
                'tamano' => $item->archivo_tamano ?? '',
                'file_url' => $item->file_url,
                'is_pdf' => $item->isPdf(),
                'download_url' => route('soportes.descargar', $item),
                'download_name' => "{$item->cedula}-".($item->created_at ? $item->created_at->format('d-m-Y_h-ia') : 'soporte').".{$item->archivo_extension}",
                'motivo' => $item->duplicado_motivo,
                'duplicado_at' => $item->duplicado_at ? $item->duplicado_at->format('d/m/Y h:i A') : null,
            ];
        };

        return response()->json([
            'original' => $formatSoporte($original),
            'duplicado' => $formatSoporte($duplicado),
            'desmarcar_url' => route('soportes.desmarcarDuplicado', $duplicado),
        ]);
    }

    /**
     * Download multiple receipts by date range packaged in a ZIP file.
     */
    public function downloadBatch(Request $request): BinaryFileResponse|RedirectResponse
    {
        $rango = $request->input('rango'); // 'hoy', 'ayer', 'personalizado'
        $query = SoportePago::query();
        $zipName = 'soportes_pagos_'.date('Y-m-d_His').'.zip';

        if ($rango === 'hoy') {
            $query->whereDate('created_at', today());
            $zipName = 'soportes_hoy_'.today()->format('Y-m-d').'.zip';
        } elseif ($rango === 'ayer') {
            $query->whereDate('created_at', today()->subDay());
            $zipName = 'soportes_ayer_'.today()->subDay()->format('Y-m-d').'.zip';
        } elseif ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('created_at', [
                $request->fecha_inicio.' 00:00:00',
                $request->fecha_fin.' 23:59:59',
            ]);
            $zipName = "soportes_{$request->fecha_inicio}_al_{$request->fecha_fin}.zip";
        } else {
            return back()->with('error', 'Por favor selecciona un rango de fechas válido.');
        }

        $soportes = $query->get();

        if ($soportes->isEmpty()) {
            return back()->with('error', 'No se encontraron soportes de pago en el rango de fechas seleccionado.');
        }

        if (! class_exists('ZipArchive')) {
            return back()->with('error', 'La extensión Zip no está habilitada en el servidor.');
        }

        $tempZip = tempnam(sys_get_temp_dir(), 'soportes_batch_');
        $zip = new ZipArchive;

        if ($zip->open($tempZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo generar el archivo ZIP de descarga.');
        }

        $addedCount = 0;
        foreach ($soportes as $soporte) {
            $fullPath = Storage::disk('public')->path($soporte->archivo_path);
            if (file_exists($fullPath)) {
                // Name format: {cedula}-{fechadeenvio}-{id}.{ext}
                $fecha = $soporte->created_at->format('d-m-Y_h-ia');
                $entryName = "{$soporte->cedula}-{$fecha}-#{$soporte->id}.{$soporte->archivo_extension}";
                $zip->addFile($fullPath, $entryName);
                $addedCount++;
            }
        }

        $zip->close();

        if ($addedCount === 0) {
            @unlink($tempZip);

            return back()->with('error', 'Los archivos asociados no están disponibles en el almacenamiento.');
        }

        return response()->download($tempZip, $zipName)->deleteFileAfterSend(true);
    }

    /**
     * Delete payment support and associated file (Admin only).
     */
    public function destroy(SoportePago $soporte): RedirectResponse
    {
        if (! Auth::user()->isAdmin()) {
            return back()->with('error', 'Solo los administradores tienen permiso para eliminar comprobantes.');
        }

        if ($soporte->archivo_path && Storage::disk('public')->exists($soporte->archivo_path)) {
            Storage::disk('public')->delete($soporte->archivo_path);
        }

        $soporte->delete();

        return back()->with('success', 'Soporte de pago eliminado exitosamente.');
    }
}
