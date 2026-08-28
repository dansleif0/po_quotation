<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_surat',
        'project_no',
        'client_id',
        'nama_klien',
        'client_details',
        'perihal',
        'produk_nama',
        'area_dinding',
        'volume',
        'harga_per_m2',
        'jasa_nama',
        'jasa_harga',
        'satuan',
        'harga_satuan',
        'harga_jasa',
        'diskon_global',
        'pisah_kriteria_total',
        'hilangkan_grand_total',
        'opsi_paket',
        'tampilkan_comp_b',
        'jenis_penawaran',
        'total_keseluruhan',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(OfferItem::class);
    }

    public function jasaItems()
    {
        return $this->hasMany(OfferJasa::class);
    }
}