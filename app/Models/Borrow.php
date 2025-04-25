<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'status',
    ];


    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getReadableStatusAttribute()
    {
        return ucfirst($this->status);
    }

    public function unreadMessages()
    {
        return $this->hasMany(ChatMessage::class, 'borrow_id')
                    ->where('receiver_id', auth()->id())
                    ->where('is_read', false);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'borrow_id');
    }
}
