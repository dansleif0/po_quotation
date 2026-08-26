<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_number',
        'offer_id',
        'client_id',
        'nama_klien',
        'client_details',
        'supplier_name',
        'supplier_address',
        'deliver_to_name',
        'deliver_to_address',
        'currency',
        'delivery_date',
        'offer_letter',
        'payment_term',
        'job_project',
        'issued_by',
        'approved_by',
        'tanggal_po',
        'total_nilai',
        'status',
        'catatan',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
