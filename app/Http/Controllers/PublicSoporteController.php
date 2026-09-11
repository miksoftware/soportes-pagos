<?php

namespace App\Http\Controllers;

use App\Models\SoportePago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicSoporteController extends Controller
{
    /**
     * Show the public mobile-first form for uploading payment support.
     */
    public function create(): View
    {
        return view('public.soporte');
    }

    /**
     * Handle the submission of payment support from a client.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cedula' => ['required', 'string', 'min:5', 'max:30'],
            'celular' => ['nullable', 'string', 'min:7', 'max:25'],
            'soporte' => ['required', 'file', 'mimes:jpeg,jpg,png,webp,pdf', 'max:12288'], // max 12MB
        ], [
            'cedula.required' => 'Por favor ingresa tu número de cédula o documento.',
            'cedula.min' => 'El número de cédula debe tener al menos 5 dígitos.',
            'soporte.required' => 'Es obligatorio adjuntar el soporte de pago (imagen o PDF).',
            'soporte.file' => 'El archivo adjunto no es válido.',
            'soporte.mimes' => 'El archivo debe ser una imagen (JPG, PNG, WEBP) o un documento PDF.',
            'soporte.max' => 'El archivo no puede exceder los 12 MB de peso.',
        ]);

        $file = $request->file('soporte');
        $extension = strtolower($file->getClientOriginalExtension());
        $originalName = $file->getClientOriginalName();
        $sizeBytes = $file->getSize();

        $sizeFormatted = $sizeBytes >= 1048576
            ? round($sizeBytes / 1048576, 2).' MB'
            : round($sizeBytes / 1024, 1).' KB';

        $tipo = in_array($extension, ['pdf']) ? 'pdf' : 'imagen';

        // Store file in public disk under 'soportes' directory
        $storedPath = $file->store('soportes', 'public');

        $soporte = SoportePago::create([
            'cedula' => trim($validated['cedula']),
            'celular' => ! empty($validated['celular']) ? trim($validated['celular']) : null,
            'archivo_path' => $storedPath,
            'archivo_nombre_original' => $originalName,
            'archivo_extension' => $extension,
            'archivo_tamano' => $sizeFormatted,
            'tipo' => $tipo,
            'estado' => 'pendiente',
            'ip' => $request->ip(),
        ]);

        return redirect()->route('soporte.exito', ['ref' => $soporte->id])
            ->with('success_soporte', [
                'id' => $soporte->id,
                'cedula' => $soporte->cedula,
                'fecha' => $soporte->created_at->format('d/m/Y h:i A'),
                'tipo' => $tipo,
            ]);
    }

    /**
     * Show confirmation success screen.
     */
    public function success(Request $request): View
    {
        $ref = $request->query('ref');
        $soporte = $ref ? SoportePago::find($ref) : null;

        return view('public.success', compact('soporte'));
    }
}
