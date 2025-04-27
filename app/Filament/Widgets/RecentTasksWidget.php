<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTasksWidget extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->with(['project', 'assignedUser'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->default('Unassigned'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'todo',
                        'warning' => 'in_progress',
                        'success' => 'done',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->color(
                        fn(Task $record): string =>
                        $record->isOverdue() ? 'danger' : 'default'
                    ),
                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'danger' => 'high',
                        'warning' => 'medium',
                        'success' => 'low',
                    ])
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
            ])
            ->paginated(false);
    }
}
