<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentPlatform;

class PaymentPlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            'Maybank',
            'Touch \'n Go',
            'Bank Islam',
            'CIMB',
            'Public Bank',
            'RHB',
            'ShopeePay',
            'GrabPay',
        ];

        foreach ($platforms as $platform) {
            PaymentPlatform::create(['platform_name' => $platform]);
        }
    }
}