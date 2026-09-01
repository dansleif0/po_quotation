<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'payable_id',
        'payable_type',
        'amount',
        'payment_receipt'
    ];

    public function payable()
    {
        return $this->morphTo();
    }
}
