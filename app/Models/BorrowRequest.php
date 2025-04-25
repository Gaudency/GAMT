namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BorrowRequest extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'return_date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'borrow_id')
                    ->where('receiver_id', auth()->id())
                    ->where('is_read', false);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'borrow_id');
    }
}
