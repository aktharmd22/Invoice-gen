<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['key' => 'return_days'],    ['value' => '7']);
        Setting::firstOrCreate(['key' => 'shop_name'],      ['value' => 'MR BLACK']);
        Setting::firstOrCreate(['key' => 'shop_phone'],     ['value' => '8122244387 | 8438904298']);
        Setting::firstOrCreate(['key' => 'shop_address'],   ['value' => 'Palluruthy Nada, Palluruthy, Kochi, Kerala 682006']);
        Setting::firstOrCreate(['key' => 'shop_maps_url'],  ['value' => 'https://maps.app.goo.gl/mjaH39akQXqejeKc8']);
        Setting::firstOrCreate(['key' => 'shop_instagram'], ['value' => 'https://www.instagram.com/mrblack.fashion']);
        Setting::firstOrCreate(['key' => 'shop_logo'],      ['value' => '']);
    }
}
