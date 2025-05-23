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
    ];

    /**
     * @var array
     */
    protected $casts = [
        'receivers' => 'array',
        'status' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * notifications sender
     */
    function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
