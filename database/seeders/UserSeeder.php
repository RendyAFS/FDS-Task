<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'department' => 'IT',
            'position' => 'System Administrator',
            'is_active' => true,
        ]);

        // Create manager users for each department
        $departments = ['IT', 'HR', 'Operasional', 'Finance', 'Marketing'];

        foreach ($departments as $department) {
            User::factory()->create([
                'name' => $department . ' Manager',
                'email' => strtolower($department) . '.manager@example.com',
                'department' => $department,
                'position' => $department . ' Manager',
                'is_active' => true,
            ]);
        }

        // Create regular users
        User::factory(20)->create();
    }
}
