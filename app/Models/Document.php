<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Document extends Model
{
    use HasFactory;

    const STATUS_PENDIENTE = 'Pendiente';
    const STATUS_PRESTADO = 'Prestado';
    const STATUS_DEVUELTO = 'Devuelto';

    protected $fillable = [
        'book_id',
        'applicant_name',
        'N_hojas',
        'N_carpeta',
        'description',
        'status',
        'fecha_prestamo',
        'fecha_devolucion',
        'category_id',
        'user_id'
    ];

    protected $dates = [
        'fecha_prestamo',
        'fecha_devolucion'
    ];

    protected $casts = [
        'fecha_prestamo' => 'datetime',
        'fecha_devolucion' => 'datetime'
    ];

    // Relación con el libro
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Usuario eliminado',
            'username' => 'admin_sistema'
        ]);
    }

    // Relación con los comprobantes
    public function comprobantes()
    {
        return $this->belongsToMany(Comprobante::class, 'document_comprobante', 'document_id', 'comprobante_id')
                    ->withPivot('estado', 'fecha_prestamo', 'fecha_devolucion')
                    ->withTimestamps();
    }

    // Obtener solo los comprobantes prestados
    public function comprobantesPrestados()
    {
        return $this->comprobantes()->wherePivot('estado', 'prestado');
    }

    // Obtener solo los comprobantes devueltos
    public function comprobantesDevueltos()
    {
        return $this->comprobantes()->wherePivot('estado', 'devuelto');
    }

    // Verificar si todos los comprobantes han sido devueltos
    public function hasTodosComprobantesDevueltos()
    {
        return $this->comprobantesPrestados()->count() === 0 && $this->comprobantes()->count() > 0;
    }

    // Verificar si el préstamo está activo (tiene al menos un comprobante prestado)
    public function isPrestamoActivo()
    {
        return $this->comprobantesPrestados()->count() > 0;
    }

    // Scope para filtrar por estado
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Modificar el scope para préstamos vencidos
    public function scopeVencidos($query)
    {
        return $query->where('status', self::STATUS_PRESTADO)
                    ->where('fecha_devolucion', '<', now());
    }

    // Métodos para manejar estados
    public function markAsPrestado()
    {
        if ($this->status === self::STATUS_PRESTADO) {
            return false; // Ya está prestado
        }

        $this->update([
            'status' => self::STATUS_PRESTADO,
            'fecha_prestamo' => now()
        ]);

        return $this->book->update(['estado' => self::STATUS_PRESTADO]);
    }

    public function markAsDevuelto()
    {
        if ($this->status === self::STATUS_DEVUELTO) {
            return false; // Ya está devuelto
        }

        $this->update([
            'status' => self::STATUS_DEVUELTO,
            'fecha_devolucion' => now()
        ]);

        return $this->book->update(['estado' => 'No Prestado']);
    }

    // Agregar método para verificar disponibilidad
    public function isAvailableForLoan()
    {
        return $this->status !== self::STATUS_PRESTADO;
    }

    // Agregar método para verificar si está vencido
    public function isVencido()
    {
        if (!$this->fecha_devolucion) {
            return false;
        }
        return $this->fecha_devolucion < now() && $this->status !== 'Devuelto';
    }

    // Agregar método para obtener días restantes o vencidos
    public function getDiasRestantes()
    {
        $fechaDevolucion = Carbon::parse($this->fecha_devolucion);
        $hoy = Carbon::now();

        if ($this->status === self::STATUS_DEVUELTO) {
            return 0;
        }

        return $hoy->diffInDays($fechaDevolucion, false);
    }
}
