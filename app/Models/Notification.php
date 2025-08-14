<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'receivers',
        'target_group',
        'is_mass',
        'sent_at',
        'status',
        'read_at'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'receivers' => 'array',
        'sent_at' => 'datetime',
        'read_at' => 'datetime'
    ];

    /**
     * notifications sender
     */
    function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * notifications receivers
     */
    public function receivers() {
        return User::whereIn('id', $this->receivers ? : [])->get();
    }

    /***
     * mark notification as read
     */
    public function markAsRead() {
        return $this->update(['read_at' => now()]);
    }
}
