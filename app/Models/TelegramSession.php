<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramSession extends Model
{
    protected $fillable = [
        'telegram_chat_id',
        'temp_email',
        'step',
        'temp_image_path',
        'temp_label',
    ];
}