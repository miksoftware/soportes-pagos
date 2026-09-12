<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    'duplicado_de_id',
    'duplicado_motivo',
    'duplicado_at',
])]
class SoportePago extends Model
{
    use HasFactory;

    protected $table = 'soportes_pagos';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'duplicado_at' => 'datetime',
        ];
    }

    /**
     * Get the original payment support if this is a duplicate.
     */
    public function original(): BelongsTo
    {
        return $this->belongsTo(self::class, 'duplicado_de_id');
    }

    /**
     * Get all duplicates linked to this original payment support.
     */
    public function duplicados(): HasMany
    {
        return $this->hasMany(self::class, 'duplicado_de_id');
    }

    /**
     * Scope a query to only include valid (non-duplicate) payment supports.
     */
    public function scopeValidos($query)
    {
        return $query->whereNull('duplicado_de_id')
            ->where(function ($q) {
                $q->whereNull('estado')->orWhere('estado', '!=', 'duplicado');
            });
    }

    /**
     * Scope a query to only include duplicate payment supports.
     */
    public function scopeSoloDuplicados($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('duplicado_de_id')
                ->orWhere('estado', 'duplicado');
        });
    }

    /**
     * Check if this payment support is marked as duplicate.
     */
    public function isDuplicado(): bool
    {
        return ! is_null($this->duplicado_de_id) || $this->estado === 'duplicado';
    }

    /**
     * Check if other supports are marked as duplicates of this one.
     */
    public function hasDuplicados(): bool
    {
        return $this->duplicados()->exists();
    }

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
