<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = ['IT', 'HR', 'Operasional', 'Finance', 'Marketing'];
        $positions = [
            'IT' => ['Developer', 'System Analyst', 'IT Support', 'Network Engineer', 'Database Administrator'],
            'HR' => ['HR Manager', 'Recruiter', 'HR Staff', 'Training Specialist'],
            'Operasional' => ['Operations Manager', 'Operations Staff', 'Quality Control', 'Logistics Staff'],
            'Finance' => ['Finance Manager', 'Accountant', 'Finance Staff', 'Auditor'],
            'Marketing' => ['Marketing Manager', 'Content Writer', 'Social Media Specialist', 'SEO Specialist']
        ];

        $department = $this->faker->randomElement($departments);
        $position = $this->faker->randomElement($positions[$department]);

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'department' => $department,
            'position' => $position,
            'phone' => fake()->phoneNumber(),
            'is_active' => fake()->boolean(90), // 90% chance to be active
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create a user with specific department
     */
    public function withDepartment($department): static
    {
        return $this->state(fn(array $attributes) => [
            'department' => $department,
        ]);
    }
}
