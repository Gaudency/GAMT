<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    public $timestamps = true;

    // Definir constantes para los estados
    const ESTADO_PRESTADO = 'Prestado';
    const ESTADO_NO_PRESTADO = 'No Prestado';

    // Constantes para tipo de carpeta
    const TIPO_GENERAL = 'general';
    const TIPO_COMPROBANTE = 'comprobante';

    protected $fillable = [
        'N_codigo',
        'title',
        'ubicacion',
        'year',
        'month', // Añadir mes si es necesario
        'description',
        'tomo',
        'N_hojas',
        'pdf_file',
        'category_id',
        'estado',
        'tipo_carpeta' // Añadir campo para diferenciar el tipo de carpeta
    ];
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function category()
    {

        return $this->belongsTo(Category::class);

    }
    // Comentar o eliminar esta relación si no estás usando el modelo Loan
    // public function loans()
    // {
    //     return $this->hasMany(Loan::class);
    // }

    // Método helper para verificar disponibilidad
    public function isAvailable()
    {
        return $this->estado === self::ESTADO_NO_PRESTADO;
    }

    // Relación con comprobantes
    public function comprobantes()
    {
        return $this->hasMany(Comprobante::class);
    }

    // Método para generar código automático
    public static function generateCodigo()
    {
        $lastBook = self::orderBy('N_codigo', 'desc')->first();
        $letters = range('A', 'Z');

        // Obtener el último número
        if ($lastBook && preg_match('/(\d+)/', $lastBook->N_codigo, $matches)) {
            $lastNumber = (int)$matches[1];
            $nextNumber = max(1000, $lastNumber + 1);
        } else {
            $nextNumber = 1000;
        }

        // Generar dos letras aleatorias
        $letter1 = $letters[array_rand($letters)];
        $letter2 = $letters[array_rand($letters)];

        // Combinar número y letras
        return $nextNumber . '-' . $letter1 . $letter2;
    }

    // Método para verificar si es carpeta de comprobante
    public function isComprobante()
    {
        return $this->tipo_carpeta === self::TIPO_COMPROBANTE;
    }

    /**
     * Verifica si el libro es de tipo comprobante
     *
     * @return bool
     */
    public function isComprobanteTipo()
    {
        // Primero intentamos con el campo tipo_carpeta
        if ($this->tipo_carpeta) {
            return $this->tipo_carpeta === self::TIPO_COMPROBANTE;
        }

        // Si no existe, nos basamos en la categoría relacionada
        if ($this->category) {
            if ($this->category->tipo) {
                return $this->category->tipo === 'comprobante';
            }

            // Si tampoco hay tipo en categoría, intentamos por el título
            return strtolower($this->category->cat_title) === 'comprobante' ||
                   strpos(strtolower($this->category->cat_title), 'comprobante') !== false;
        }

        return false;
    }
}
