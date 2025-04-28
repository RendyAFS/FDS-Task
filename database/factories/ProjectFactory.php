<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectNames = [
            'Website Redesign',
            'Mobile App Development',
            'Database Migration',
            'Network Infrastructure Upgrade',
            'Employee Training Program',
            'Marketing Campaign Q1',
            'Annual Report Preparation',
            'Customer Satisfaction Survey',
            'CRM Implementation',
            'Security Audit',
            'Office Renovation',
            'Inventory Management System',
            'Social Media Strategy',
            'Budget Planning 2025',
            'Product Launch'
        ];

        $departments = ['IT', 'HR', 'Operasional', 'Finance', 'Marketing'];
        $startDate = fake()->dateTimeBetween('-3 months', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+6 months');

        return [
            'name' => fake()->randomElement($projectNames) . ' ' . fake()->year(),
            'description' => fake()->paragraph(3),
            'start_date' => $startDate,
            'end_date' => fake()->boolean(80) ? $endDate : null, // 80% chance to have end date
            'status' => fake()->randomElement([
                Project::STATUS_PLANNING,
                Project::STATUS_IN_PROGRESS,
                Project::STATUS_COMPLETED,
                Project::STATUS_ON_HOLD,
                Project::STATUS_CANCELLED
            ]),
            'department' => fake()->randomElement($departments),
            'manager_id' => User::factory(),
            'priority' => fake()->randomElement([
                Project::PRIORITY_LOW,
                Project::PRIORITY_MEDIUM,
                Project::PRIORITY_HIGH
            ]),
            'budget' => fake()->boolean(70) ? fake()->randomFloat(2, 10000, 500000) : null,
        ];
    }

    /**
     * Indicate that the project is active
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Project::STATUS_IN_PROGRESS,
        ]);
    }

    /**
     * Indicate that the project is completed
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Project::STATUS_COMPLETED,
        ]);
    }
}
