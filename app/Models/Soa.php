<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soa extends Model
{
    protected $fillable = [
        'no_soa',
        'nama_klien',
        'tanggal_soa',
        'is_paid',
        'paid_amount',
        'payment_receipt'
    ];

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_soa')->withPivot('keterangan')->withTimestamps();
    }

    public function paymentTransactions()
    {
        return $this->morphMany(PaymentTransaction::class, 'payable');
    }
}
