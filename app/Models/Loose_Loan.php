<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Loose_Loan extends Model
{
    use HasFactory;

    // Especificar explícitamente el nombre de la tabla
    protected $table = 'loose_loans';

    protected $fillable = [
        'folder_code',
        'book_title',
        'sheets_count',
        'lender_name',
        'loan_date',
        'return_date',
        'description',
        'status',
        'digital_signature'
    ];

    // Definir los campos que son fechas para que sean tratados como Carbon
    protected $dates = [
        'loan_date',
        'return_date',
        'created_at',
        'updated_at'
    ];

    // Para asegurar que Laravel lea estos campos como datetime
    protected $casts = [
        'loan_date' => 'datetime',
        'return_date' => 'datetime',
        'sheets_count' => 'integer',
    ];

    // Como esta tab // Accessor para asegurar que las fecnejen como Carbon
    public function getLoanDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    public function getReturnDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}

