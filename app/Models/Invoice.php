<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    use HasFactory;
    protected $guarded = []; // Izinkan semua kolom diisi

    // Relasi ke Penawaran (Offer) aslinya
    public function offer() {
        return $this->belongsTo(Offer::class);
    }

    // Relasi ke Pekerjaan Tambahan
    public function additions() {
        return $this->hasMany(InvoiceAddition::class);
    }

    // Relasi ke Pembayaran DP
    public function payments() {
        return $this->hasMany(InvoicePayment::class);
    }

    // Relasi ke SOA
    public function soas() {
        return $this->belongsToMany(Soa::class, 'invoice_soa')->withPivot('keterangan')->withTimestamps();
    }

    // Relasi ke transaksi cicilan pembayaran (Installments)
    public function paymentTransactions()
    {
        return $this->morphMany(PaymentTransaction::class, 'payable');
    }

    // URL File PO (Penyimpanan Lokal Public)
    public function getPoFileUrlAttribute()
    {
        if (!$this->po_file_path) {
            return null;
        }

        if (str_starts_with($this->po_file_path, 'http://') || str_starts_with($this->po_file_path, 'https://')) {
            return $this->po_file_path;
        }

        return asset('storage/' . $this->po_file_path);
    }
}