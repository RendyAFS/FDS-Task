<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'is_system_generated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_system_generated' => 'boolean',
    ];

    /**
     * Relasi dengan task
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Relasi dengan user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk comment yang bukan system generated
     */
    public function scopeUserComments($query)
    {
        return $query->where('is_system_generated', false);
    }

    /**
     * Scope untuk comment yang system generated
     */
    public function scopeSystemComments($query)
    {
        return $query->where('is_system_generated', true);
    }

    /**
     * Create system comment untuk perubahan status
     */
    public static function createStatusChangeComment($task, $user, $oldStatus, $newStatus)
    {
        return static::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => "Status changed from {$oldStatus} to {$newStatus}",
            'is_system_generated' => true,
        ]);
    }

    /**
     * Create system comment untuk perubahan assignee
     */
    public static function createAssignmentChangeComment($task, $user, $oldAssignee, $newAssignee)
    {
        $oldName = $oldAssignee ? $oldAssignee->name : 'Unassigned';
        $newName = $newAssignee ? $newAssignee->name : 'Unassigned';

        return static::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => "Task reassigned from {$oldName} to {$newName}",
            'is_system_generated' => true,
        ]);
    }
}
