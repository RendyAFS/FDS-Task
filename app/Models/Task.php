<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'project_id',
        'assigned_to',
        'created_by',
        'status',
        'priority',
        'start_date',
        'due_date',
        'completed_at',
        'estimated_hours',
        'actual_hours',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    /**
     * Konstanta untuk status
     */
    const STATUS_TODO = 'todo';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE = 'done';

    /**
     * Konstanta untuk priority
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    /**
     * Relasi dengan project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relasi dengan user yang ditugaskan
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Relasi dengan user yang membuat task
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi dengan comments
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Scope untuk task yang belum selesai
     */
    public function scopeIncomplete($query)
    {
        return $query->whereIn('status', [self::STATUS_TODO, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Scope untuk task yang overdue
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', self::STATUS_DONE);
    }

    /**
     * Scope untuk task berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Cek apakah task overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now() && $this->status !== self::STATUS_DONE;
    }

    /**
     * Mark task as complete
     */
    public function markAsComplete()
    {
        $this->update([
            'status' => self::STATUS_DONE,
            'completed_at' => now(),
        ]);
    }

    /**
     * Update status task
     */
    public function updateStatus($status)
    {
        $updates = ['status' => $status];

        if ($status === self::STATUS_DONE) {
            $updates['completed_at'] = now();
        } elseif ($status === self::STATUS_IN_PROGRESS && !$this->start_date) {
            $updates['start_date'] = now();
        }

        $this->update($updates);
    }

    /**
     * Get progress percentage (untuk task dengan subtasks)
     */
    public function getProgressPercentage()
    {
        if ($this->status === self::STATUS_TODO) {
            return 0;
        } elseif ($this->status === self::STATUS_DONE) {
            return 100;
        } else {
            return 50; // In Progress
        }
    }
}
