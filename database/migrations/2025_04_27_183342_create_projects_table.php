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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['planning', 'in_progress', 'completed', 'on_hold', 'cancelled'])
                ->default('planning');
            $table->string('department')->nullable(); // Departemen yang bertanggung jawab
            $table->foreignId('manager_id')->nullable()
                ->constrained('users')
                ->nullOnDelete(); // Project manager
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->decimal('budget', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
