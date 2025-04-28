<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles jika belum ada
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }

        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user']);
        }

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'department' => 'IT',
            'position' => 'System Administrator',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Create regular users from different departments
        $users = collect();
        $departments = ['IT', 'HR', 'Operasional', 'Finance', 'Marketing'];

        foreach ($departments as $department) {
            $departmentUsers = User::factory(3)->create(['department' => $department]);
            foreach ($departmentUsers as $user) {
                $user->assignRole('user');
            }
            $users = $users->merge($departmentUsers);
        }

        // Add admin to users collection
        $users->push($admin);

        // Create projects with tasks
        $projectsCount = 15;

        for ($i = 0; $i < $projectsCount; $i++) {
            // Select a random manager (could be any user)
            $manager = $users->random();

            $project = Project::factory()->create([
                'manager_id' => $manager->id,
                'department' => $manager->department,
            ]);

            // Create tasks for each project (5-15 tasks per project)
            $tasksCount = rand(5, 15);

            for ($j = 0; $j < $tasksCount; $j++) {
                // Select a random creator (could be any user)
                $creator = $users->random();

                // Select a random assignee (80% chance to be from the same department)
                $assignee = null;
                if (rand(1, 100) <= 90) { // 90% chance to be assigned
                    if (rand(1, 100) <= 80) { // 80% chance same department
                        $sameDeptUsers = $users->where('department', $project->department);
                        $assignee = $sameDeptUsers->count() > 0 ? $sameDeptUsers->random() : $users->random();
                    } else {
                        $assignee = $users->random();
                    }
                }

                // Distribute task statuses realistically
                $statusRandom = rand(1, 100);
                if ($statusRandom <= 30) {
                    $status = Task::STATUS_TODO;
                } elseif ($statusRandom <= 60) {
                    $status = Task::STATUS_IN_PROGRESS;
                } else {
                    $status = Task::STATUS_DONE;
                }

                $task = Task::factory()->create([
                    'project_id' => $project->id,
                    'created_by' => $creator->id,
                    'assigned_to' => $assignee ? $assignee->id : null,
                    'status' => $status,
                ]);

                // Create comments for each task (0-5 comments per task)
                $commentsCount = rand(0, 5);

                for ($k = 0; $k < $commentsCount; $k++) {
                    $commenter = $users->random();
                    $isSystemGenerated = rand(1, 100) <= 20; // 20% chance system comment

                    TaskComment::factory()->create([
                        'task_id' => $task->id,
                        'user_id' => $commenter->id,
                        'is_system_generated' => $isSystemGenerated,
                    ]);
                }
            }
        }

        // Create some overdue tasks
        Task::factory(10)->overdue()->create([
            'project_id' => fn() => Project::inRandomOrder()->first()->id,
            'created_by' => fn() => $users->random()->id,
            'assigned_to' => fn() => rand(1, 100) <= 90 ? $users->random()->id : null,
        ]);

        // Output summary
        $this->command->info('Seeding completed!');
        $this->command->info('Created:');
        $this->command->info('- ' . User::count() . ' users');
        $this->command->info('- ' . Project::count() . ' projects');
        $this->command->info('- ' . Task::count() . ' tasks');
        $this->command->info('- ' . TaskComment::count() . ' comments');
    }
}
