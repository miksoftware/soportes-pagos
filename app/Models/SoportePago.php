<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'cedula',
    'celular',
    'archivo_path',
    'archivo_nombre_original',
    'archivo_extension',
    'archivo_tamano',
    'tipo',
    'estado',
    'ip',
])]
class SoportePago extends Model
{
    use HasFactory;

    protected $table = 'soportes_pagos';

    /**
     * Get full public URL for the file.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/'.$this->archivo_path);
    }

    /**
     * Check if file is PDF.
     */
    public function isPdf(): bool
    {
        return $this->tipo === 'pdf' || strtolower($this->archivo_extension) === 'pdf';
    }

    /**
     * Check if file is an image.
     */
    public function isImage(): bool
    {
        return $this->tipo === 'imagen';
    }
}
