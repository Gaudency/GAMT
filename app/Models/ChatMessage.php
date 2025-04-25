<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'is_borrow_request',
        'borrow_id'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_borrow_request' => 'boolean',
    ];

    // Relación con el remitente
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relación con el destinatario
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Relación con el préstamo (si existe)
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    // Relación con los archivos adjuntos
    public function attachments()
    {
        return $this->hasMany(ChatAttachment::class);
    }

    // Scope para obtener conversaciones no leídas
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope para obtener mensajes entre dos usuarios
    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function($q) use ($user1Id, $user2Id) {
            $q->where(function($inner) use ($user1Id, $user2Id) {
                $inner->where('sender_id', $user1Id)
                      ->where('receiver_id', $user2Id);
            })->orWhere(function($inner) use ($user1Id, $user2Id) {
                $inner->where('sender_id', $user2Id)
                      ->where('receiver_id', $user1Id);
            });
        });
    }

    // Marcar mensaje como leído
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
