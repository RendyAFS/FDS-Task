<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepartmentOverviewWidget extends BaseWidget
{
    use HasWidgetShield;
    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        $departments = ['IT', 'HR', 'Operasional', 'Finance', 'Marketing'];
        $stats = [];

        foreach ($departments as $department) {
            $projectCount = Project::where('department', $department)->count();
            $activeProjects = Project::where('department', $department)
                ->where('status', 'in_progress')
                ->count();

            $userIds = User::where('department', $department)->pluck('id');
            $taskCount = Task::whereIn('assigned_to', $userIds)
                ->where('status', '!=', 'done')
                ->count();

            $stats[] = Stat::make($department, $projectCount . ' projects')
                ->description($activeProjects . ' active, ' . $taskCount . ' pending tasks')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary');
        }

        return $stats;
    }
}
