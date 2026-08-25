<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_klien',
        'client_details',
        'email',
        'telepon',
        'alamat',
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
