<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestamoComprobante extends Model
{
    use HasFactory;

    protected $table = 'prestamo_comprobantes';

    protected $fillable = [
        'borrow_id',
        'comprobante_id',
        'status',
        'approved_at',
        'returned_at',
        'rejected_at',
        'observaciones'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    // Relación con el préstamo principal
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    // Relación con el comprobante
    public function comprobante()
    {
        return $this->belongsTo(Comprobante::class);
    }

    // Métodos de utilidad
    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    public function isReturned()
    {
        return $this->status === 'Returned';
    }

    public function isRejected()
    {
        return $this->status === 'Rejected';
    }

    public function isPending()
    {
        return $this->status === 'Applied';
    }

    // Método para aprobar el préstamo del comprobante
    public function approve()
    {
        $this->status = 'Approved';
        $this->approved_at = now();
        $this->save();

        // Actualizar estado del comprobante
        $this->comprobante->update(['estado' => 'inactivo']);
    }

    // Método para marcar como devuelto
    public function markAsReturned()
    {
        $this->status = 'Returned';
        $this->returned_at = now();
        $this->save();

        // Actualizar estado del comprobante
        $this->comprobante->update(['estado' => 'activo']);
    }

    // Método para rechazar
    public function reject()
    {
        $this->status = 'Rejected';
        $this->rejected_at = now();
        $this->save();
    }
}
