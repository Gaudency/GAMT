<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['cat_title', 'tipo', 'detalles', 'status'];

    protected $casts = [
        'detalles' => 'array',
    ];

    // Accessor para asegurar que detalles siempre sea un array
    public function getDetallesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    // Mutator para asegurar que detalles se guarde como JSON
    public function setDetallesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['detalles'] = json_encode($value);
        } else {
            $this->attributes['detalles'] = $value;
        }
    }

    // Scope para filtrar por estado
    public function scopeActive($query)
    {
        return $query->where('status', 'activo');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactivo');
    }

    // Relación con libros
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Relación con documentos
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}

