<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_produk',
        'comp_b',
        'packing_size_b',
        'generic',
        'primer_topcoat',
        'category',
        'thinner',
        'packing_size',
        'price_per_l',
        'hasil_akhir',
        'kriteria',
        'performa',
        'harga',
    ];

    /**
     * Relationship to ProductBatch
     */
    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    /**
     * Relationship to ProductPacking
     */
    public function packings()
    {
        return $this->hasMany(ProductPacking::class);
    }
}