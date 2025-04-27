<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskComment>
 */
class TaskCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userComments = [
            'I\'ve started working on this task.',
            'Need more information about the requirements.',
            'This task is taking longer than expected due to some technical issues.',
            'I\'ve completed the initial draft. Please review.',
            'Waiting for feedback from the client.',
            'Updated the code based on the review comments.',
            'Found a better approach to solve this problem.',
            'This is blocked waiting for the other team.',
            'Good progress so far.',
            'I need help with this part.',
            'This looks good to me.',
            'Can we schedule a meeting to discuss this?',
            'Almost done, just need to finish testing.',
            'Running into some performance issues.',
            'This might need to be moved to the next sprint.'
        ];

        $systemComments = [
            'Status changed from todo to in_progress',
            'Status changed from in_progress to done',
            'Task reassigned from John Doe to Jane Smith',
            'Priority changed from low to high',
            'Due date updated',
            'Task moved to different project',
            'Estimated hours updated'
        ];

        $isSystemGenerated = fake()->boolean(30); // 30% chance to be system generated

        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'comment' => $isSystemGenerated
                ? fake()->randomElement($systemComments)
                : fake()->randomElement($userComments),
            'is_system_generated' => $isSystemGenerated,
        ];
    }

    /**
     * Indicate that the comment is user generated
     */
    public function userComment(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system_generated' => false,
        ]);
    }

    /**
     * Indicate that the comment is system generated
     */
    public function systemComment(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_system_generated' => true,
        ]);
    }
}
