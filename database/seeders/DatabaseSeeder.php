<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the individual seeders
        $this->call([
            CecilianUserSeeder::class,
            OrganizationSeeder::class,
            OrganizationRoleSeeder::class,
            PermissionSeeder::class,
            PortalNotificationSeeder::class,
            StudentSeeder::class,
            SystemSettingsSeeder::class,
            BsitProspectusSeeder::class,
        ]);
    }
}
