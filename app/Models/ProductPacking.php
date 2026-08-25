<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPacking extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'packing_size',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
