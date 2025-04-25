<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_message_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    // Relación con el mensaje
    public function message()
    {
        return $this->belongsTo(ChatMessage::class, 'chat_message_id');
    }

    // Obtener URL del archivo
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Verificar si el archivo es una imagen
    public function isImage()
    {
        return in_array($this->file_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    // Verificar si el archivo es un documento
    public function isDocument()
    {
        return in_array($this->file_type, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    // Obtener tamaño formateado
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
