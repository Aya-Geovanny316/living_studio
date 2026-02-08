<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_number',
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'notes',
        'response_message',
        'status',
        'subtotal_estimate',
        'response_total_estimate',
    ];

    protected $casts = [
        'subtotal_estimate' => 'decimal:2',
        'response_total_estimate' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
