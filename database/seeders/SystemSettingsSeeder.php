<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Set the current school year and semester by their IDs
        SystemSetting::create(['key' => 'current_school_year_id', 'value' => 1]);
        SystemSetting::create(['key' => 'current_semester_id', 'value' => 1]);

        // Other system settings
        SystemSetting::create(['key' => 'app_name', 'value' => 'Cecilian Portal']);
        SystemSetting::create(['key' => 'maintenance_mode', 'value' => false]);

        // Add registration setting
        SystemSetting::create(['key' => 'registration_enabled', 'value' => true]);

        // Add login setting
        SystemSetting::create(['key' => 'login_enabled', 'value' => true]);
    }
}
