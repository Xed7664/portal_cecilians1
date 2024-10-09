<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Supreme Student Council organization by its name
        $supremeStudentCouncil = Organization::where('name', 'Supreme Student Council')->first();

        if ($supremeStudentCouncil) {
            // Create the "President" role associated with the Supreme Student Council organization
            OrganizationRole::create([
                'name' => 'President',
                'organization_id' => $supremeStudentCouncil->id,
                // Add other fields as needed
            ]);
        }
    }
}
