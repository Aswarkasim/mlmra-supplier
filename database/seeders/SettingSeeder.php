<?php

namespace Database\Seeders;

use App\Enums\SettingType;
use App\Enums\StatusType;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'setting_type'  => SettingType::REFERAL_PAID,
            'setting_value' => 100000,
            'status'        => StatusType::ACTIVE
        ]);
    }
}
