<?php

namespace App\Http\Controllers;

use App\Models\SoportePago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class SoportePagoController extends Controller
{
    /**
     * Display a listing of received payment supports.
     */
    public function index(Request $request): View
    {
        $query = SoportePago::query();

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

        return view('soportes.index', compact('soportes'));
    }

    /**
     * Download an individual receipt named: {cedula}-{fechadeenvio}.{ext}
     */
    public function download(SoportePago $soporte): BinaryFileResponse|RedirectResponse
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
