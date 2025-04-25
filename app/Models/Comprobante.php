<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comprobante extends Model
{
    use HasFactory;

    protected $table = 'comprobantes';

    protected $fillable = [
        'book_id',
        'numero_comprobante',
        'codigo_personalizado',
        'n_hojas',
        'pdf_file',
        'descripcion',
        'costo',
        'estado'
    ];

    protected $casts = [
        'detalles' => 'array',
        'fecha_emision' => 'date'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_comprobante', 'comprobante_id', 'document_id')
                    ->withPivot('estado', 'fecha_prestamo', 'fecha_devolucion')
                    ->withTimestamps();
    }

    // Obtener solo los préstamos activos (donde el comprobante está prestado)
    public function prestamosActivos()
    {
        return $this->documents()->wherePivot('estado', 'prestado');
    }

    // Verificar si el comprobante está prestado
    public function isPrestado()
    {
        return $this->prestamosActivos()->count() > 0;
    }

    // Obtener el préstamo actual si está prestado
    public function prestamoActual()
    {
        return $this->prestamosActivos()->first();
    }

    // Verificar si el comprobante está disponible para préstamo
    public function isDisponible()
    {
        return !$this->isPrestado();
    }

    /**
     * Método para sincronizar el estado del comprobante basado en sus préstamos activos
     *
     * @return boolean
     */
    public function sincronizarEstado()
    {
        $estaPrestado = $this->isPrestado();

        // Si está prestado, el estado debe ser 'inactivo'
        // Si no está prestado, el estado debe ser 'activo'
        $nuevoEstado = $estaPrestado ? 'inactivo' : 'activo';

        // Solo actualizar si el estado es diferente
        if ($this->estado != $nuevoEstado) {
            $this->update(['estado' => $nuevoEstado]);
            return true;
        }

        return false;
    }

    /**
     * Método para verificar si se puede prestar el comprobante
     *
     * @return array [puede_prestar, mensaje]
     */
    public function puedePrestarse()
    {
        if ($this->estado != 'activo') {
            return [false, 'El comprobante no está activo.'];
        }

        if ($this->isPrestado()) {
            return [false, 'El comprobante ya está prestado.'];
        }

        return [true, 'El comprobante está disponible para préstamo.'];
    }

    /**
     * Método para devolver un comprobante desde un préstamo específico
     *
     * @param int $document_id
     * @return boolean
     */
    public function devolverDesde($document_id)
    {
        $prestamo = DB::table('document_comprobante')
                      ->where('comprobante_id', $this->id)
                      ->where('document_id', $document_id)
                      ->where('estado', 'prestado')
                      ->first();

        if (!$prestamo) {
            return false;
        }

        DB::table('document_comprobante')
            ->where('comprobante_id', $this->id)
            ->where('document_id', $document_id)
            ->update([
                'estado' => 'devuelto',
                'fecha_devolucion' => now()
            ]);

        // Sincronizar el estado basado en otros préstamos activos
        $this->sincronizarEstado();

        return true;
    }
}

