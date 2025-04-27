<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'department',
        'manager_id',
        'priority',
        'budget',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    /**
     * Konstanta untuk status
     */
    const STATUS_PLANNING = 'planning';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Konstanta untuk priority
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    /**
     * Relasi dengan manager (user)
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relasi dengan tasks
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get tasks berdasarkan status
     */
    public function tasksByStatus($status)
    {
        return $this->tasks()->where('status', $status)->get();
    }

    /**
     * Get jumlah tasks berdasarkan status
     */
    public function getTasksCountByStatus()
    {
        return [
            'todo' => $this->tasks()->where('status', 'todo')->count(),
            'in_progress' => $this->tasks()->where('status', 'in_progress')->count(),
            'done' => $this->tasks()->where('status', 'done')->count(),
        ];
    }

    /**
     * Scope untuk project yang active (sedang berjalan)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope untuk filter berdasarkan department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->tasks()->where('status', 'done')->count();
        return round(($completedTasks / $totalTasks) * 100, 2);
    }
}
