<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $taskTitles = [
            'Update documentation',
            'Fix bug in login system',
            'Create new user interface',
            'Conduct user interviews',
            'Prepare presentation',
            'Review code changes',
            'Setup development environment',
            'Test new features',
            'Deploy to production',
            'Database backup',
            'Analyze user feedback',
            'Create wireframes',
            'Write test cases',
            'Optimize performance',
            'Security patch installation',
            'Data migration',
            'API integration',
            'UI/UX improvements',
            'Customer support training',
            'Budget review'
        ];

        $status = fake()->randomElement([Task::STATUS_TODO, Task::STATUS_IN_PROGRESS, Task::STATUS_DONE]);
        $startDate = fake()->dateTimeBetween('-1 month', '+1 week');
        $dueDate = fake()->dateTimeBetween($startDate, '+2 months');
        $completedAt = null;

        if ($status === Task::STATUS_DONE) {
            $completedAt = fake()->dateTimeBetween($startDate, $dueDate);
        }

        return [
            'title' => fake()->randomElement($taskTitles),
            'description' => fake()->paragraph(2),
            'project_id' => Project::factory(),
            'assigned_to' => fake()->boolean(85) ? User::factory() : null, // 85% chance to be assigned
            'created_by' => User::factory(),
            'status' => $status,
            'priority' => fake()->randomElement([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH]),
            'start_date' => fake()->boolean(70) ? $startDate : null,
            'due_date' => $dueDate,
            'completed_at' => $completedAt,
            'estimated_hours' => fake()->numberBetween(1, 40),
            'actual_hours' => $status === Task::STATUS_DONE ? fake()->numberBetween(1, 50) : null,
            'notes' => fake()->boolean(40) ? fake()->sentence() : null,
        ];
    }

    /**
     * Indicate that the task is todo
     */
    public function todo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_TODO,
            'completed_at' => null,
            'actual_hours' => null,
        ]);
    }

    /**
     * Indicate that the task is in progress
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_IN_PROGRESS,
            'completed_at' => null,
            'actual_hours' => null,
        ]);
    }

    /**
     * Indicate that the task is done
     */
    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_DONE,
            'completed_at' => fake()->dateTimeBetween($attributes['start_date'] ?? '-1 month', 'now'),
            'actual_hours' => fake()->numberBetween(1, 50),
        ]);
    }

    /**
     * Indicate that the task is overdue
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => fake()->randomElement([Task::STATUS_TODO, Task::STATUS_IN_PROGRESS]),
            'due_date' => fake()->dateTimeBetween('-2 weeks', '-1 day'),
            'completed_at' => null,
        ]);
    }
}
