<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'access_admin',
                'description' => 'Access admin panel',
                'limitations' => null,
            ],
            [
                'name' => 'create_post',
                'description' => 'Create a new post',
                'limitations' => null,
            ],
            [
                'name' => 'create_comment',
                'description' => 'Create a new comment',
                'limitations' => null,
            ],
            [
                'name' => 'react_to_post',
                'description' => 'React to a post',
                'limitations' => null,
            ],
            [
                'name' => 'have_official_icon',
                'description' => 'Have an official icon',
                'limitations' => null,
            ],            
            [
                'name' => 'delete_post',
                'description' => 'Delete a post from other people',
                'limitations' => null,
            ],
            [
                'name' => 'protect_from_delete',
                'description' => 'Protect content from deletion',
                'limitations' => null,
            ],
            [
                'name' => 'protect_from_edit',
                'description' => 'Protect content from being edited',
                'limitations' => null,
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
