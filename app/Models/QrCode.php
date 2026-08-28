<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'user_id',
        'platform_id',
        'label',
        'qr_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentPlatform()
    {
        return $this->belongsTo(PaymentPlatform::class, 'platform_id');
    }
}