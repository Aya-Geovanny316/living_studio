<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'product_id',
        'product_name_snapshot',
        'unit_price_estimate',
        'qty',
        'line_total_estimate',
    ];

    protected $casts = [
        'unit_price_estimate' => 'decimal:2',
        'line_total_estimate' => 'decimal:2',
        'qty' => 'integer',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
