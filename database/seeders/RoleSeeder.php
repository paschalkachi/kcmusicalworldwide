<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        Role::updateOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        Role::updateOrCreate(['slug' => 'user'], ['name' => 'User']);
    }
}
