<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete(); // Jika project dihapus, task juga akan dihapus
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete(); // User yang ditugaskan
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete(); // User yang membuat task
            $table->enum('status', ['todo', 'in_progress', 'done'])
                ->default('todo');
            $table->enum('priority', ['low', 'medium', 'high'])
                ->default('medium');
            $table->date('start_date')->nullable();
            $table->date('due_date');
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_hours')->nullable(); // Estimasi waktu dalam jam
            $table->integer('actual_hours')->nullable(); // Waktu aktual yang dihabiskan
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete

            // Index untuk mempercepat query
            $table->index(['project_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
