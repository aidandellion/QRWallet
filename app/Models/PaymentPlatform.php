<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_name',
    ];

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'platform_id');
    }
}